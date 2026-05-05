<div class="max-w-3xl mx-auto py-8 px-4">

    {{-- Header misión --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="text-4xl">🏔️</div>
            <div>
                <h1 class="text-xl font-semibold text-gray-800">{{ $mision->titulo }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $mision->pregunta_investigacion }}</p>
            </div>
        </div>

        {{-- Barra de fases --}}
        <div class="flex gap-2 mt-6">
            @foreach($mision->fases as $fase)
                @php
                    $faseCompletada = $progreso->xp_ganado > 0 &&
                        $mision->fases->search(fn($f) => $f->id === $fase->id) 
                        $mision->fases->search(fn($f) => $f->id === $faseActual?->id);
                    $esFaseActual = $faseActual && $fase->id === $faseActual->id;
                @endphp
                <div class="flex-1 text-center">
                    <div class="h-2 rounded-full mb-1 {{ $faseCompletada ? 'bg-green-500' : ($esFaseActual ? 'bg-indigo-500' : 'bg-gray-200') }}"></div>
                    <span class="text-xs text-gray-400">{{ $fase->nombre_quechua }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Misión completada --}}
    @if($misionCompletada)
        <div class="bg-white rounded-xl shadow p-8 text-center">
            <div class="text-6xl mb-4">🦙</div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">¡Misión completada!</h2>
            <p class="text-gray-500 mb-2">Has completado <strong>{{ $mision->titulo }}</strong></p>
            <p class="text-indigo-600 font-medium text-lg mb-6">
                +{{ $progreso->xp_ganado }} XP ganados
            </p>
            <a href="{{ route('estudiante.misiones') }}"
               class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Volver a misiones
            </a>
        </div>

    {{-- Juego activo --}}
    @else
        {{-- Fase actual --}}
        <div class="bg-white rounded-xl shadow p-6 mb-4">
            <div class="flex items-center gap-3 mb-4">
                <span class="bg-indigo-100 text-indigo-700 text-xs font-medium px-3 py-1 rounded-full">
                    Fase {{ $faseActual->orden }} de {{ $mision->fases->count() }}
                </span>
                <h2 class="font-semibold text-gray-800">
                    {{ $faseActual->nombre }}
                    <span class="text-gray-400 font-normal text-sm">· {{ $faseActual->nombre_quechua }}</span>
                </h2>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed">{{ $faseActual->instruccion }}</p>
        </div>

        {{-- Tupaq habla --}}
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-4 flex gap-4">
            <div class="text-3xl flex-shrink-0">🦙</div>
            <div>
                <p class="text-xs font-medium text-amber-700 mb-1">Tupaq dice:</p>
                @if($respuestaTupaq)
                    <p class="text-sm text-amber-900 leading-relaxed">{{ $respuestaTupaq }}</p>
                    @if($faseAprobada)
                        <div class="mt-3 flex items-center gap-2">
                            <span class="text-green-600 text-xs font-medium">
                                ✓ Nivel {{ $nivelLogrado }} — +{{ $faseActual->xp_recompensa }} XP
                            </span>
                        </div>
                    @else
                        <p class="text-xs text-amber-600 mt-2">Intenta mejorar tu respuesta para avanzar.</p>
                    @endif
                @else
                    <p class="text-sm text-amber-800 leading-relaxed">{{ $faseActual->pista_tupaq }}</p>
                @endif
            </div>
        </div>

        {{-- Respuesta del estudiante --}}
        @if(!$faseAprobada)
            <div class="bg-white rounded-xl shadow p-6 mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tu respuesta:
                </label>
                <textarea
                    wire:model="respuestaEstudiante"
                    rows="5"
                    placeholder="Escribe aquí tu respuesta..."
                    class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"
                ></textarea>
                <div class="flex justify-end mt-3">
                    <button
                        wire:click="enviarRespuesta"
                        wire:loading.attr="disabled"
                        class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700 transition disabled:opacity-50">
                        <span wire:loading.remove>Enviar a Tupaq 🦙</span>
                        <span wire:loading>Tupaq está pensando...</span>
                    </button>
                </div>
            </div>
        @endif

        {{-- Botón siguiente fase --}}
        @if($faseAprobada)
            <div class="text-center">
                <button
                    wire:click="siguienteFase"
                    class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-medium">
                    Siguiente fase →
                </button>
            </div>
        @endif
    @endif

    {{-- XP actual --}}
    <div class="mt-6 text-center text-sm text-gray-400">
        XP en esta misión: <span class="font-medium text-indigo-600">{{ $progreso->xp_ganado }}</span>
    </div>

</div>