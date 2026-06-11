<?php

namespace App\Http\Controllers;

use App\Models\Mision;
use App\Models\FaseMision;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MisionController extends Controller
{
    public function index()
    {
        $misiones = Mision::withCount('fases')->orderBy('orden')->get();
        return view('docente.misiones', compact('misiones'));
    }

    public function crear(Request $request)
    {
        $request->validate([
            'titulo'                 => 'required|string|max:200',
            'descripcion'            => 'required|string',
            'contexto_andino'        => 'required|string',
            'pregunta_investigacion' => 'required|string|max:300',
        ]);

        $slug = \Illuminate\Support\Str::slug($request->titulo);
        $slugBase = $slug;
        $i = 1;
        while (\App\Models\Mision::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $i++;
        }

        $mision = Mision::create([
            'titulo'                 => $request->titulo,
            'slug'                   => $slug,
            'descripcion'            => $request->descripcion,
            'contexto_andino'        => $request->contexto_andino,
            'pregunta_investigacion' => $request->pregunta_investigacion,
            'orden'                  => Mision::max('orden') + 1,
            'activa'                 => true,
        ]);

        // Generar fases con IA
        $fases = $this->generarFasesConIA($mision);

        foreach ($fases as $fase) {
            FaseMision::create([
                'mision_id'      => $mision->id,
                'nombre'         => $fase['nombre'],
                'nombre_quechua' => $fase['nombre_quechua'],
                'instruccion'    => $fase['instruccion'],
                'pista_tupaq'    => $fase['pista_tupaq'],
                'orden'          => $fase['orden'],
                'xp_recompensa'  => $fase['xp_recompensa'],
            ]);
        }

        return back()->with('success', '¡Misión creada con fases generadas por IA!');
    }

    private function generarFasesConIA(Mision $mision): array
    {
        $fasesDef = [
            ['nombre' => 'Problematización', 'nombre_quechua' => 'Tapukuy',      'orden' => 1, 'xp_recompensa' => 15],
            ['nombre' => 'Hipótesis',         'nombre_quechua' => 'Yuyaychakuy', 'orden' => 2, 'xp_recompensa' => 20],
            ['nombre' => 'Recojo de datos',   'nombre_quechua' => "Hap'iy",      'orden' => 3, 'xp_recompensa' => 25],
            ['nombre' => 'Análisis',          'nombre_quechua' => 'Yachaqay',    'orden' => 4, 'xp_recompensa' => 25],
            ['nombre' => 'Conclusión',        'nombre_quechua' => 'Tukuchiy',    'orden' => 5, 'xp_recompensa' => 30],
        ];

        $prompt = <<<PROMPT
    Eres un experto en diseño curricular alineado al Currículo Nacional de Educación Básica (CNEB) del Perú, enfoque STEAM y contexto andino de Huancavelica.

    Se ha creado una misión de indagación con estos datos:
    - Título: {$mision->titulo}
    - Descripción: {$mision->descripcion}
    - Contexto andino: {$mision->contexto_andino}
    - Pregunta de investigación: {$mision->pregunta_investigacion}

    Genera instrucciones y pistas para las 5 fases del ciclo de indagación científica.
    Cada fase debe:
    - Tener instrucciones claras y motivadoras para el estudiante
    - Conectar con el contexto andino de Huancavelica
    - Estar alineada a la Competencia 20 del CNEB
    - Tener una pista de Tupaq que guíe sin revelar la respuesta
    - Usar ocasionalmente palabras en quechua
    - Ser adecuada para estudiantes de primaria y secundaria

    Responde ÚNICAMENTE con este JSON válido, sin texto adicional:
    {
    "fases": [
        {
        "nombre": "Problematización",
        "instruccion": "instrucción completa para el estudiante (2-3 oraciones)",
        "pista_tupaq": "pista motivadora de Tupaq (2-3 oraciones)"
        },
        {
        "nombre": "Hipótesis",
        "instruccion": "...",
        "pista_tupaq": "..."
        },
        {
        "nombre": "Recojo de datos",
        "instruccion": "...",
        "pista_tupaq": "..."
        },
        {
        "nombre": "Análisis",
        "instruccion": "...",
        "pista_tupaq": "..."
        },
        {
        "nombre": "Conclusión",
        "instruccion": "...",
        "pista_tupaq": "..."
        }
    ]
    }
    PROMPT;

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'x-api-key'         => config('services.anthropic.key'),
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-sonnet-4-20250514',
                'max_tokens' => 1500,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

            $texto = $response->json('content.0.text');
            $json  = json_decode($texto, true);

            if (!$json || !isset($json['fases'])) {
                throw new \Exception('JSON inválido');
            }

            // Combinar definición base con contenido generado por IA
            foreach ($fasesDef as $idx => $fase) {
                $fasesDef[$idx]['instruccion'] = $json['fases'][$idx]['instruccion'] ?? 'Observa y describe lo que ves en tu entorno.';
                $fasesDef[$idx]['pista_tupaq'] = $json['fases'][$idx]['pista_tupaq'] ?? 'Tupaq te acompaña en este camino del saber.';
            }

        } catch (\Exception $e) {
            // Si falla la IA usa instrucciones genéricas contextualizadas
            foreach ($fasesDef as $idx => $fase) {
                $fasesDef[$idx]['instruccion'] = "Explora la pregunta '{$mision->pregunta_investigacion}' desde la fase de {$fase['nombre']}. Describe lo que observas en tu entorno andino.";
                $fasesDef[$idx]['pista_tupaq'] = "Yachay wawqey, en esta fase de {$fase['nombre']} presta atención a los detalles de tu entorno. Cada observación cuenta en el camino del saber.";
            }
        }

        return $fasesDef;
    }
    

    public function regenerarFases(Mision $mision)
    {
        // Eliminar fases actuales
        $mision->fases()->delete();

        // Regenerar con IA
        $fases = $this->generarFasesConIA($mision);

        foreach ($fases as $fase) {
            FaseMision::create([
                'mision_id'      => $mision->id,
                'nombre'         => $fase['nombre'],
                'nombre_quechua' => $fase['nombre_quechua'],
                'instruccion'    => $fase['instruccion'],
                'pista_tupaq'    => $fase['pista_tupaq'],
                'orden'          => $fase['orden'],
                'xp_recompensa'  => $fase['xp_recompensa'],
            ]);
        }

        return back()->with('success', '¡Fases regeneradas con IA correctamente!');
    }


    public function eliminar(Mision $mision)
    {
        $mision->delete();
        return back()->with('success', 'Misión eliminada.');
    }
}