<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color:#1D2458;">
            Mis Misiones
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

            {{-- Nivel y XP --}}
            <div class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-xl flex items-center justify-between"
                style="background:#EEF7FC;border:1px solid rgba(28,171,226,0.2);">
                <div>
                    <p class="text-sm font-bold" style="color:#1D2458;">
                        🏔️ {{ auth()->user()->nivelActual() }}
                    </p>
                    <p class="text-xs mt-1" style="color:#4A7A9A;">
                        {{ auth()->user()->gradoCompleto() }} — {{ auth()->user()->institucion ?? 'Sin institución' }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xl sm:text-2xl font-bold" style="color:#1CABE2;">
                        {{ auth()->user()->xpTotal() }} XP
                    </p>
                    <a href="{{ route('ranking') }}" class="text-xs font-bold" style="color:#1D2458;">
                        Ver ranking →
                    </a>
                </div>
            </div>

            {{-- Misiones --}}
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach($misiones as $mision)
                    @php $progreso = $progresos[$mision->id] ?? null; @endphp
                    <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div class="text-3xl sm:text-4xl flex-shrink-0">🏔️</div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-sm sm:text-base" style="color:#1D2458;">
                                    {{ $mision->titulo }}
                                </h3>
                                <p class="text-xs sm:text-sm mt-1" style="color:#4A7A9A;">
                                    {{ $mision->descripcion }}
                                </p>

                                @if($progreso)
                                    <div class="mt-3">
                                        <div class="flex justify-between text-xs mb-1" style="color:#4A7A9A;">
                                            <span>{{ $progreso->completada ? '✓ Completada' : 'En progreso' }}</span>
                                            <span class="font-bold" style="color:#1CABE2;">
                                                {{ $progreso->xp_ganado }} XP
                                            </span>
                                        </div>
                                        <div class="h-2 rounded-full overflow-hidden" style="background:#EEF7FC;">
                                            <div class="h-2 rounded-full"
                                                style="width:{{ $progreso->completada ? '100' : min(90, round(($progreso->xp_ganado/115)*100)) }}%;
                                                       background:#1CABE2;">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('estudiante.mision.jugar', $mision) }}"
                                   class="inline-block mt-3 sm:mt-4 text-white text-xs sm:text-sm px-3 sm:px-4 py-2 rounded-lg font-bold transition"
                                   style="background:#1D2458;">
                                    {{ $progreso ? ($progreso->completada ? 'Revisar misión' : 'Continuar') : 'Comenzar misión' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>