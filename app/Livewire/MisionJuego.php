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
    public ?int $lugarId = null;
    public $historialFase = [];

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
                'lugar_id'       => $this->lugarId,
            ]
        );

        $this->faseActual = $this->progreso->faseActual
            ?? $mision->fases->first();

        $this->misionCompletada = $this->progreso->completada;
        $this->cargarHistorial();
    }
    private function cargarHistorial(): void
    {
        if (!$this->faseActual) return;

        $this->historialFase = InteraccionIA::where('user_id', auth()->id())
            ->where('mision_id', $this->mision->id)
            ->where('fase_id', $this->faseActual->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
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
        $this->historialFase = [];
        // Verificar insignias desbloqueadas
        $user = auth()->user()->load('insignias', 'progresos', 'interacciones');
        $service = new \App\Services\InsigniaService();
        $nuevas = $service->verificarYDesbloquear($user);

        if (!empty($nuevas)) {
            $this->dispatch('insignias-desbloqueadas', 
                insignias: collect($nuevas)->map(fn($i) => [
                    'nombre' => $i->nombre_quechua,
                    'emoji'  => $i->emoji,
                ])->toArray()
            );
        }
    
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

        $this->cargarHistorial();
    }

    public function historialCompleto(): array
    {
        return InteraccionIA::where('user_id', auth()->id())
            ->where('mision_id', $this->mision->id)
            ->with('fase')
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('fase_id')
            ->toArray();
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
    $user = auth()->user();
    $gradoInfo = $user->gradoCompleto();
    $ciclo = $user->cicloEBR();

    $nivelLenguaje = match(true) {
        $user->nivel_educativo === 'primaria' && $user->grado <= 2 =>
            'Usa lenguaje muy simple, frases cortas, vocabulario básico. Como hablarías con un niño de 6-7 años.',
        $user->nivel_educativo === 'primaria' && $user->grado <= 4 =>
            'Usa lenguaje simple y claro. Puedes usar analogías con elementos cotidianos andinos.',
        $user->nivel_educativo === 'primaria' && $user->grado <= 6 =>
            'Usa lenguaje accesible. Puedes introducir términos científicos básicos explicándolos.',
        $user->nivel_educativo === 'secundaria' && $user->grado <= 2 =>
            'Usa lenguaje claro con términos científicos básicos. Conecta con su contexto adolescente andino.',
        $user->nivel_educativo === 'secundaria' && $user->grado >= 3 =>
            'Usa lenguaje más técnico y científico. Exige mayor rigor en las hipótesis y conclusiones.',
        default => 'Usa lenguaje claro y motivador adaptado al contexto andino.'
    };

    $nivelExigencia = match(true) {
        $user->nivel_educativo === 'primaria' && $user->grado <= 2 =>
            'Nivel 2 es suficiente para aprobar. Valora el esfuerzo y la observación básica.',
        $user->nivel_educativo === 'primaria' && $user->grado <= 4 =>
            'Nivel 2 es suficiente. Espera descripciones simples con alguna relación causa-efecto.',
        $user->nivel_educativo === 'primaria' && $user->grado <= 6 =>
            'Nivel 2 mínimo. Espera hipótesis con algún fundamento y conclusiones básicas.',
        $user->nivel_educativo === 'secundaria' && $user->grado <= 2 =>
            'Nivel 2 mínimo. Espera hipótesis fundamentadas y uso de vocabulario científico básico.',
        $user->nivel_educativo === 'secundaria' && $user->grado >= 3 =>
            'Nivel 3 mínimo para aprobar. Exige hipótesis con variables, análisis con evidencias y conclusiones fundamentadas.',
        default => 'Nivel 2 es suficiente para aprobar.'
    };

    $competenciaCNEB = match(true) {
        $user->nivel_educativo === 'primaria' && $user->grado <= 2 =>
            'Competencia 20 CNEB — Ciclo III: Observa hechos y fenómenos. Formula preguntas y posibles respuestas. Registra datos.',
        $user->nivel_educativo === 'primaria' && $user->grado <= 4 =>
            'Competencia 20 CNEB — Ciclo IV: Problematiza situaciones, diseña estrategias, genera datos, analiza y evalúa.',
        $user->nivel_educativo === 'primaria' && $user->grado <= 6 =>
            'Competencia 20 CNEB — Ciclo V: Problematiza con mayor precisión, formula hipótesis, recoge evidencias, comunica conclusiones.',
        $user->nivel_educativo === 'secundaria' && $user->grado <= 2 =>
            'Competencia 20 CNEB — Ciclo VI: Formula hipótesis con variables, diseña procedimientos, analiza con modelos, comunica con argumentos.',
        $user->nivel_educativo === 'secundaria' && $user->grado >= 3 =>
            'Competencia 20 CNEB — Ciclo VII: Investiga con rigor científico, evalúa hipótesis, usa modelos complejos, comunica con evidencias sólidas.',
        default => 'Competencia 20 CNEB — Indagación científica.'
    };

    return <<<PROMPT
    Eres Tupaq, un sabio andino y guía científico para estudiantes de Huancavelica, Perú.
    Hablas con calidez, usando ocasionalmente palabras en quechua.
    Siempre conectas la ciencia con el contexto andino y el conocimiento ancestral.

    DATOS DEL ESTUDIANTE:
    - Grado: {$gradoInfo}
    - Ciclo EBR: {$ciclo}
    - Currículo: {$competenciaCNEB}

    ADAPTACIÓN DE LENGUAJE: {$nivelLenguaje}
    NIVEL DE EXIGENCIA: {$nivelExigencia}

    MISIÓN: {$this->mision->titulo}
    PREGUNTA DE INVESTIGACIÓN: {$this->mision->pregunta_investigacion}

    FASE ACTUAL: {$this->faseActual->nombre} ({$this->faseActual->nombre_quechua})
    INSTRUCCIÓN DE LA FASE: {$this->faseActual->instruccion}

    RESPUESTA DEL ESTUDIANTE:
    {$this->respuestaEstudiante}

    Evalúa considerando el grado y ciclo del estudiante:
    - Nivel 1 (Inicio): No cumple lo esperado para su grado
    - Nivel 2 (Proceso): Cumple parcialmente lo esperado para su grado
    - Nivel 3 (Logrado): Cumple lo esperado para su grado
    - Nivel 4 (Destacado): Supera lo esperado para su grado

    Responde ÚNICAMENTE con este JSON válido, sin texto adicional:
    {
    "mensaje": "Tu respuesta como Tupaq aquí (máximo 120 palabras, adaptada al grado del estudiante)",
    "nivel": 2,
    "competencias": {
        "problematiza": 2,
        "hipotesis": 0,
        "recojo_datos": 0,
        "analiza": 0,
        "comunica": 1
    }
    }
    PROMPT;
    }




    public function render()
    {
        return view('livewire.mision-juego');
    }
}