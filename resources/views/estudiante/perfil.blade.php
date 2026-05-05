<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mi Perfil
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Tarjeta de nivel --}}
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center text-4xl">
                        🏔️
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold text-gray-800">{{ $user->name }}</h2>
                        <p class="text-indigo-600 font-medium mt-1">{{ $user->nivelActual() }}</p>

                        {{-- Barra XP --}}
                        @php
                            $xp = $user->xpTotal();
                            $niveles = [0, 100, 250, 450, 700];
                            $nivelIdx = collect($niveles)->filter(fn($n) => $xp >= $n)->count() - 1;
                            $xpActual = $xp - $niveles[$nivelIdx];
                            $xpSiguiente = isset($niveles[$nivelIdx + 1]) ? $niveles[$nivelIdx + 1] - $niveles[$nivelIdx] : 100;
                            $porcentaje = min(100, round(($xpActual / $xpSiguiente) * 100));
                        @endphp

                        <div class="mt-3">
                            <div class="flex justify-between text-xs text-gray-400 mb-1">
                                <span>{{ $xp }} XP total</span>
                                @if($nivelIdx < 4)
                                    <span>Siguiente nivel: {{ $niveles[$nivelIdx + 1] ?? '—' }} XP</span>
                                @else
                                    <span>¡Nivel máximo!</span>
                                @endif
                            </div>
                            <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-3 bg-indigo-500 rounded-full transition-all"
                                    style="width: {{ $porcentaje }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Niveles --}}
                <div class="grid grid-cols-5 gap-2 mt-6">
                    @foreach(['Musuq Yachaq', 'Tapuq', 'Qawaq', 'Yachaq', 'Apu Yachaq'] as $idx => $nivel)
                        @php $alcanzado = $nivelIdx >= $idx; @endphp
                        <div class="text-center">
                            <div class="text-xl mb-1">{{ ['🌱','🔍','👁️','🦙','🏔️'][$idx] }}</div>
                            <div class="text-xs {{ $alcanzado ? 'text-indigo-600 font-medium' : 'text-gray-300' }}">
                                {{ $nivel }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Progreso en misiones --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Progreso en misiones</h3>
                @forelse($user->progresos as $progreso)
                    <div class="flex items-center gap-4 mb-4">
                        <div class="text-2xl">🏔️</div>
                        <div class="flex-1">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">
                                    {{ $progreso->mision->titulo }}
                                </span>
                                <span class="text-xs text-indigo-600">{{ $progreso->xp_ganado }} XP</span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-2 rounded-full {{ $progreso->completada ? 'bg-green-500' : 'bg-indigo-400' }}"
                                    style="width: {{ $progreso->completada ? '100' : '50' }}%"></div>
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $progreso->completada ? '✓ Completada' : 'En progreso' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Aún no has comenzado ninguna misión.</p>
                @endforelse
            </div>

            {{-- Insignias --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-2">Mis insignias</h3>
                <p class="text-xs text-gray-400 mb-4">
                    {{ $user->insignias->count() }} de {{ $todasInsignias->count() }} desbloqueadas
                </p>

                @foreach(['mision' => 'Misiones', 'habilidad' => 'Habilidades', 'constancia' => 'Constancia'] as $cat => $label)
                    <div class="mb-6">
                        <h4 class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3">{{ $label }}</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($todasInsignias->where('categoria', $cat) as $insignia)
                                @php $desbloqueada = $user->insignias->contains($insignia->id); @endphp
                                <div class="border rounded-xl p-4 {{ $desbloqueada ? 'border-indigo-200 bg-indigo-50' : 'border-gray-100 bg-gray-50 opacity-50' }}">
                                    <div class="text-3xl mb-2">{{ $desbloqueada ? $insignia->emoji : '🔒' }}</div>
                                    <div class="text-sm font-medium {{ $desbloqueada ? 'text-indigo-700' : 'text-gray-400' }}">
                                        {{ $insignia->nombre_quechua }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $insignia->nombre }}</div>
                                    @if($desbloqueada)
                                        <div class="text-xs text-indigo-400 mt-2">
                                            ✓ {{ $user->insignias->find($insignia->id)->pivot->desbloqueada_en->format('d/m/Y') }}
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-300 mt-2">{{ $insignia->descripcion }}</div>
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