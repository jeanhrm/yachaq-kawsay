<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mision;
use App\Models\FaseMision;
use App\Models\ProgresoEstudiante;
use App\Models\InteraccionIA;
use Illuminate\Support\Facades\Http;

class MisionJuego extends Component
{
    public Mision $mision;
    public ?FaseMision $faseActual = null;
    public ?ProgresoEstudiante $progreso = null;
    public string $respuestaEstudiante = '';
    public ?string $respuestaTupaq = null;
    public bool $cargando = false;
    public bool $faseAprobada = false;
    public bool $misionCompletada = false;
    public int $nivelLogrado = 0;

    public function mount(Mision $mision): void
    {
        $this->mision = $mision->load('fases');

        $this->progreso = ProgresoEstudiante::firstOrCreate(
            [
                'user_id'  => auth()->id(),
                'mision_id' => $mision->id,
            ],
            [
                'fase_actual_id' => $mision->fases->first()?->id,
                'xp_ganado'      => 0,
                'completada'     => false,
                'iniciada_en'    => now(),
            ]
        );

        $this->faseActual = $this->progreso->faseActual
            ?? $mision->fases->first();

        $this->misionCompletada = $this->progreso->completada;
    }

    public function enviarRespuesta(): void
    {
        if (empty(trim($this->respuestaEstudiante))) return;

        $this->cargando = true;
        $this->respuestaTupaq = null;
        $this->faseAprobada = false;

        try {
            $respuesta = $this->consultarTupaq();

            $this->respuestaTupaq = $respuesta['mensaje'];
            $this->nivelLogrado   = $respuesta['nivel'];
            $this->faseAprobada   = $respuesta['aprobada'];

            InteraccionIA::create([
                'user_id'                => auth()->id(),
                'mision_id'              => $this->mision->id,
                'fase_id'                => $this->faseActual->id,
                'respuesta_estudiante'   => $this->respuestaEstudiante,
                'respuesta_tupaq'        => $this->respuestaTupaq,
                'nivel_logrado'          => $this->nivelLogrado,
                'evaluacion_competencias' => $respuesta['competencias'],
                'fase_aprobada'          => $this->faseAprobada,
            ]);

            if ($this->faseAprobada) {
                $this->progreso->increment('xp_ganado', $this->faseActual->xp_recompensa);
            }

        } catch (\Exception $e) {
            $this->respuestaTupaq = 'Tupaq no puede responderte ahora. Intenta de nuevo en un momento.';
        }

        $this->cargando = false;
    }

    public function siguienteFase(): void
    {
        $fases = $this->mision->fases;
        $indiceActual = $fases->search(fn($f) => $f->id === $this->faseActual->id);
        $siguienteFase = $fases->get($indiceActual + 1);

        if ($siguienteFase) {
            $this->progreso->update(['fase_actual_id' => $siguienteFase->id]);
            $this->faseActual = $siguienteFase;
        } else {
            $this->progreso->update([
                'completada'     => true,
                'completada_en'  => now(),
                'fase_actual_id' => null,
            ]);
            $this->misionCompletada = true;
        }

        $this->respuestaEstudiante = '';
        $this->respuestaTupaq = null;
        $this->faseAprobada = false;
        $this->nivelLogrado = 0;
    }

    private function consultarTupaq(): array
    {
        $prompt = $this->construirPrompt();

        $response = Http::withHeaders([
            'x-api-key'         => config('services.anthropic.key'),
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model'      => 'claude-sonnet-4-20250514',
            'max_tokens' => 600,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ]);

        $texto = $response->json('content.0.text');
        $json  = json_decode($texto, true);

        return [
            'mensaje'      => $json['mensaje'] ?? $texto,
            'nivel'        => $json['nivel'] ?? 1,
            'aprobada'     => ($json['nivel'] ?? 1) >= 2,
            'competencias' => $json['competencias'] ?? [],
        ];
    }

    private function construirPrompt(): string
    {
        return <<<PROMPT
Eres Tupaq, un sabio andino y guía científico para estudiantes de secundaria de Huancavelica, Perú. 
Hablas con calidez, usando ocasionalmente palabras en quechua. 
Siempre conectas la ciencia con el contexto andino y el conocimiento ancestral.

MISIÓN: {$this->mision->titulo}
PREGUNTA DE INVESTIGACIÓN: {$this->mision->pregunta_investigacion}

FASE ACTUAL: {$this->faseActual->nombre} ({$this->faseActual->nombre_quechua})
INSTRUCCIÓN DE LA FASE: {$this->faseActual->instruccion}

RESPUESTA DEL ESTUDIANTE:
{$this->respuestaEstudiante}

Evalúa la respuesta del estudiante con estos criterios:
- Nivel 1 (Inicio): Respuesta muy vaga, sin relación clara con la fase
- Nivel 2 (Proceso): Respuesta básica, muestra comprensión parcial  
- Nivel 3 (Logrado): Respuesta clara y completa para la fase
- Nivel 4 (Destacado): Respuesta excepcional con conexiones profundas

Responde ÚNICAMENTE con este JSON válido, sin texto adicional:
{
  "mensaje": "Tu respuesta como Tupaq aquí (máximo 120 palabras, cálido y motivador)",
  "nivel": 2,
  "competencias": {
    "problematiza": 2,
    "hipotesis": 0,
    "recojo_datos": 0,
    "analiza": 0,
    "comunica": 1
  }
}

Si el nivel es 2 o superior, el estudiante puede avanzar a la siguiente fase.
PROMPT;
    }

    public function render()
    {
        return view('livewire.mision-juego');
    }
}