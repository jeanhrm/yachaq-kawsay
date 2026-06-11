<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mi Perfil
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            {{-- Tarjeta de nivel --}}
            <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                <div class="flex items-center gap-3 sm:gap-6">
                    <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-full flex items-center justify-center text-3xl sm:text-4xl flex-shrink-0"
                        style="background:#EEF7FC;">
                        🏔️
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg sm:text-2xl font-semibold" style="color:#1D2458;">
                            {{ auth()->user()->name }}
                        </h2>
                        <a href="{{ route('estudiante.perfil.editar') }}"
                            class="text-xs font-bold px-3 py-1 rounded-lg"
                            style="background:#EEF7FC;color:#1CABE2;border:1px solid rgba(28,171,226,0.2);">
                            ✏️ Editar
                        </a>
                        <p class="text-xs sm:text-sm font-bold mt-1" style="color:#1CABE2;">
                            {{ auth()->user()->nivelActual() }}
                        </p>
                        <p class="text-xs mt-1" style="color:#4A7A9A;">
                            {{ auth()->user()->gradoCompleto() }}
                        </p>
                        <p class="text-xs" style="color:#4A7A9A;">
                            {{ auth()->user()->institucion ?? 'Sin institución' }}
                        </p>

                        {{-- Barra XP --}}
                        @php
                            $xp = auth()->user()->xpTotal();
                            $niveles = [0, 100, 250, 450, 700];
                            $nivelIdx = collect($niveles)->filter(fn($n) => $xp >= $n)->count() - 1;
                            $xpActual = $xp - $niveles[$nivelIdx];
                            $xpSiguiente = isset($niveles[$nivelIdx + 1]) ? $niveles[$nivelIdx + 1] - $niveles[$nivelIdx] : 100;
                            $porcentaje = min(100, round(($xpActual / $xpSiguiente) * 100));
                        @endphp

                        <div class="mt-3">
                            <div class="flex justify-between text-xs mb-1" style="color:#4A7A9A;">
                                <span>{{ $xp }} XP total</span>
                                @if($nivelIdx < 4)
                                    <span>Siguiente: {{ $niveles[$nivelIdx + 1] }} XP</span>
                                @else
                                    <span>¡Nivel máximo!</span>
                                @endif
                            </div>
                            <div class="h-3 rounded-full overflow-hidden" style="background:#EEF7FC;">
                                <div class="h-3 rounded-full" style="width:{{ $porcentaje }}%;background:#1CABE2;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Niveles --}}
                <div class="grid grid-cols-5 gap-1 sm:gap-2 mt-4 sm:mt-6">
                    @foreach(['Musuq Yachaq', 'Tapuq', 'Qawaq', 'Yachaq', 'Apu Yachaq'] as $idx => $nivel)
                        @php $alcanzado = $nivelIdx >= $idx; @endphp
                        <div class="text-center">
                            <div class="text-lg sm:text-xl mb-1">{{ ['🌱','🔍','👁️','🦙','🏔️'][$idx] }}</div>
                            <div class="text-xs {{ $alcanzado ? 'font-bold' : '' }}"
                                style="color:{{ $alcanzado ? '#1CABE2' : '#C8DCE8' }};font-size:0.6rem;">
                                {{ $nivel }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Progreso en misiones --}}
            <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                <h3 class="font-bold mb-4" style="color:#1D2458;">Progreso en misiones</h3>
                @forelse(auth()->user()->progresos()->with('mision')->get() as $progreso)
                    <div class="flex items-center gap-3 mb-4">
                        <div class="text-xl sm:text-2xl flex-shrink-0">🏔️</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between mb-1">
                                <span class="text-xs sm:text-sm font-bold truncate" style="color:#1D2458;">
                                    {{ $progreso->mision->titulo }}
                                </span>
                                <span class="text-xs font-bold ml-2 flex-shrink-0" style="color:#1CABE2;">
                                    {{ $progreso->xp_ganado }} XP
                                </span>
                            </div>
                            <div class="h-2 rounded-full overflow-hidden" style="background:#EEF7FC;">
                                <div class="h-2 rounded-full"
                                    style="width:{{ $progreso->completada ? '100' : '50' }}%;
                                           background:{{ $progreso->completada ? '#1CABE2' : '#1D2458' }};"></div>
                            </div>
                            <div class="text-xs mt-1" style="color:#4A7A9A;">
                                {{ $progreso->completada ? '✓ Completada' : 'En progreso' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm" style="color:#4A7A9A;">Aún no has comenzado ninguna misión.</p>
                @endforelse
            </div>

            {{-- Insignias --}}
            <div class="bg-white rounded-xl shadow p-4 sm:p-6">
                <h3 class="font-bold mb-1" style="color:#1D2458;">Mis insignias</h3>
                <p class="text-xs mb-4" style="color:#4A7A9A;">
                    {{ $user->insignias->count() }} de {{ $todasInsignias->count() }} desbloqueadas
                </p>

                @foreach(['mision' => 'Misiones', 'habilidad' => 'Habilidades', 'constancia' => 'Constancia'] as $cat => $label)
                    <div class="mb-6">
                        <h4 class="text-xs font-bold uppercase tracking-wide mb-3" style="color:#4A7A9A;">
                            {{ $label }}
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3">
                            @foreach($todasInsignias->where('categoria', $cat) as $insignia)
                                @php $desbloqueada = $user->insignias->contains($insignia->id); @endphp
                                <div class="rounded-xl p-3 sm:p-4 {{ $desbloqueada ? '' : 'opacity-50' }}"
                                    style="border:1px solid {{ $desbloqueada ? 'rgba(28,171,226,0.3)' : '#E8E8E8' }};
                                           background:{{ $desbloqueada ? '#EEF7FC' : '#F8F8F8' }};">
                                    <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">
                                        {{ $desbloqueada ? $insignia->emoji : '🔒' }}
                                    </div>
                                    <div class="text-xs font-bold"
                                        style="color:{{ $desbloqueada ? '#1CABE2' : '#9A9A9A' }};">
                                        {{ $insignia->nombre_quechua }}
                                    </div>
                                    <div class="text-xs mt-0.5" style="color:#4A7A9A;">
                                        {{ $insignia->nombre }}
                                    </div>
                                    @if($desbloqueada)
                                        <div class="text-xs mt-1" style="color:#1CABE2;">
                                            ✓ {{ $user->insignias->find($insignia->id)->pivot->desbloqueada_en->format('d/m/Y') }}
                                        </div>
                                    @else
                                        <div class="text-xs mt-1" style="color:#9A9A9A;line-height:1.3;">
                                            {{ $insignia->descripcion }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>