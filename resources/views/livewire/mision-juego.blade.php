<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex gap-6">

        {{-- COLUMNA PRINCIPAL (izquierda) --}}
        <div class="flex-1 min-w-0">

            {{-- Header misión --}}
            <div class="bg-white rounded-xl shadow p-6 mb-4">
                <div class="flex items-center gap-4">
                    <div class="text-4xl">🏔️</div>
                    <div>
                        <h1 class="text-xl font-semibold" style="color:#1D2458;">{{ $mision->titulo }}</h1>
                        <p class="text-sm mt-1" style="color:#4A7A9A;">{{ $mision->pregunta_investigacion }}</p>
                    </div>
                </div>

                {{-- Barra de fases --}}
                <div class="flex gap-2 mt-6">
                    @foreach($mision->fases as $fase)
                        @php
                            $indiceFase = $mision->fases->search(fn($f) => $f->id === $fase->id);
                            $indiceActual = $faseActual ? $mision->fases->search(fn($f) => $f->id === $faseActual->id) : -1;
                            $faseCompletada = $progreso->xp_ganado > 0 && $indiceFase < $indiceActual;
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
                    <h2 class="text-2xl font-semibold mb-2" style="color:#1D2458;">¡Misión completada!</h2>
                    <p class="mb-2" style="color:#4A7A9A;">Has completado <strong>{{ $mision->titulo }}</strong></p>
                    <p class="font-bold text-lg mb-6" style="color:#1CABE2;">+{{ $progreso->xp_ganado }} XP ganados</p>
                    <a href="{{ route('estudiante.misiones') }}"
                       class="text-white px-6 py-2 rounded-lg transition font-bold"
                       style="background:#1D2458;">
                        Volver a misiones
                    </a>
                </div>

            @else
                {{-- Fase actual --}}
                <div class="bg-white rounded-xl shadow p-6 mb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-xs font-bold px-3 py-1 rounded-full"
                            style="background:#EEF7FC;color:#1CABE2;">
                            Fase {{ $faseActual->orden }} de {{ $mision->fases->count() }}
                        </span>
                        <h2 class="font-bold" style="color:#1D2458;">
                            {{ $faseActual->nombre }}
                            <span class="font-normal text-sm" style="color:#4A7A9A;">· {{ $faseActual->nombre_quechua }}</span>
                        </h2>
                    </div>
                    <p class="text-sm leading-relaxed" style="color:#4A7A9A;">{{ $faseActual->instruccion }}</p>
                </div>

                {{-- Tupaq habla --}}
                <div class="rounded-xl p-5 mb-4 flex gap-4"
                    style="background:#EEF7FC;border:1px solid rgba(28,171,226,0.25);">
                    <img src="{{ asset('images/tupac.png') }}"
                         alt="Tupaq"
                         style="width:52px;height:52px;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid rgba(28,171,226,0.3);">
                    <div>
                        <p class="text-xs font-bold mb-1" style="color:#1CABE2;">Tupaq dice:</p>
                        @if($respuestaTupaq)
                            <p class="text-sm leading-relaxed" style="color:#1D2458;">{{ $respuestaTupaq }}</p>
                            @if($faseAprobada)
                                <div class="mt-2">
                                    <span class="text-xs font-bold" style="color:#1CABE2;">
                                        ✓ Nivel {{ $nivelLogrado }} — +{{ $faseActual->xp_recompensa }} XP
                                    </span>
                                </div>
                            @else
                                <p class="text-xs mt-2" style="color:#4A7A9A;">Intenta mejorar tu respuesta para avanzar.</p>
                            @endif
                        @else
                            <p class="text-sm leading-relaxed" style="color:#1D2458;">{{ $faseActual->pista_tupaq }}</p>
                        @endif
                    </div>
                </div>

                {{-- Respuesta del estudiante --}}
                @if(!$faseAprobada)
                    <div class="bg-white rounded-xl shadow p-6 mb-4">
                        <label class="block text-sm font-bold mb-2" style="color:#1D2458;">Tu respuesta:</label>
                        <textarea
                            wire:model="respuestaEstudiante"
                            rows="5"
                            placeholder="Escribe aquí tu respuesta..."
                            class="w-full border rounded-lg px-4 py-3 text-sm resize-none focus:outline-none"
                            style="border-color:rgba(28,171,226,0.3);color:#1D2458;"
                        ></textarea>
                        <div class="flex justify-end mt-3">
                            <button
                                wire:click="enviarRespuesta"
                                wire:loading.attr="disabled"
                                class="text-white px-5 py-2 rounded-lg text-sm font-bold transition disabled:opacity-50"
                                style="background:#1D2458;">
                                <span wire:loading.remove>Enviar a Tupaq 🦙</span>
                                <span wire:loading class="tupaq-thinking">Tupaq está pensando... 🦙</span>
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Botón siguiente fase --}}
                @if($faseAprobada)
                    <div class="text-center">
                        <button
                            wire:click="siguienteFase"
                            class="text-white px-8 py-3 rounded-lg font-bold transition"
                            style="background:#1CABE2;">
                            Siguiente fase →
                        </button>
                    </div>
                @endif
            @endif

            {{-- XP actual --}}
            <div class="mt-6 text-center text-sm" style="color:#4A7A9A;">
                XP en esta misión: <span class="font-bold" style="color:#1CABE2;">{{ $progreso->xp_ganado }}</span>
            </div>

        </div>

        {{-- PANEL HISTORIAL (derecha) --}}
        <div class="w-80 flex-shrink-0">
            <div class="bg-white rounded-xl shadow p-4 sticky top-4">
                <h3 class="font-bold text-sm mb-4" style="color:#1D2458;">
                    📋 Historial de respuestas
                </h3>

                @php
                    $todasInteracciones = \App\Models\InteraccionIA::where('user_id', auth()->id())
                        ->where('mision_id', $mision->id)
                        ->with('fase')
                        ->orderBy('created_at', 'asc')
                        ->get();
                @endphp

                @if($todasInteracciones->isEmpty())
                    <div class="text-center py-6">
                        <div class="text-3xl mb-2">📝</div>
                        <p class="text-xs" style="color:#4A7A9A;">
                            Aquí verás tus respuestas y los comentarios de Tupaq en cada fase.
                        </p>
                    </div>
                @else
                    <div class="space-y-4 max-h-screen overflow-y-auto pr-1"
                         style="max-height:calc(100vh - 200px);">
                        @foreach($todasInteracciones->groupBy('fase_id') as $faseId => $interacciones)
                            @php $nombreFase = $interacciones->first()->fase; @endphp
                            <div>
                                {{-- Header fase --}}
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="h-px flex-1" style="background:rgba(28,171,226,0.2);"></div>
                                    <span class="text-xs font-bold px-2" style="color:#1CABE2;">
                                        {{ $nombreFase?->nombre ?? 'Fase' }}
                                    </span>
                                    <div class="h-px flex-1" style="background:rgba(28,171,226,0.2);"></div>
                                </div>

                                @foreach($interacciones as $idx => $interaccion)
                                    <div class="rounded-lg p-3 mb-2"
                                        style="background:#F8FBFE;border:1px solid rgba(28,171,226,0.12);">

                                        {{-- Intento --}}
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-xs font-bold" style="color:#4A7A9A;">
                                                Intento {{ $idx + 1 }}
                                            </span>
                                            <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                                                style="background:{{ $interaccion->fase_aprobada ? '#EEF7FC' : '#FFF5F5' }};
                                                       color:{{ $interaccion->fase_aprobada ? '#1CABE2' : '#E53E3E' }};">
                                                {{ $interaccion->fase_aprobada ? '✓ Aprobada' : '✗ Nivel '.$interaccion->nivel_logrado }}
                                            </span>
                                        </div>

                                        {{-- Tu respuesta --}}
                                        <div class="mb-2">
                                            <p class="text-xs font-bold mb-1" style="color:#1D2458;">Tú:</p>
                                            <p class="text-xs leading-relaxed" style="color:#2D3748;">
                                                {{ Str::limit($interaccion->respuesta_estudiante, 120) }}
                                            </p>
                                        </div>

                                        {{-- Tupaq --}}
                                        <div class="pt-2" style="border-top:1px solid rgba(28,171,226,0.1);">
                                            <p class="text-xs font-bold mb-1" style="color:#1CABE2;">🦙 Tupaq:</p>
                                            <p class="text-xs leading-relaxed" style="color:#2D3748;">
                                                {{ Str::limit($interaccion->respuesta_tupaq, 120) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>