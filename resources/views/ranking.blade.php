<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color:#1D2458;">
            🏆 Ranking Global — Niños indagadores
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Mi posición --}}
            @if(auth()->user()->isEstudiante())
                <div class="rounded-xl p-4 mb-6 flex items-center justify-between"
                    style="background:#EEF7FC;border:1px solid rgba(28,171,226,0.2);">
                    <div>
                        <p class="text-sm font-bold" style="color:#1D2458;">Tu posición actual</p>
                        <p class="text-xs mt-1" style="color:#4A7A9A;">
                            {{ auth()->user()->nivelActual() }} —
                            {{ auth()->user()->xpTotal() }} XP
                        </p>
                    </div>
                    <div class="text-3xl font-bold" style="color:#1CABE2;">
                        {{ $miPosicion ? '#'.$miPosicion : 'Sin ranking aún' }}
                    </div>
                </div>
            @endif

            {{-- Top 3 --}}
            @if($ranking->count() >= 3)
                <div class="grid grid-cols-3 gap-4 mb-6">
                    @foreach([1, 0, 2] as $idx)
                        @php $estudiante = $ranking[$idx] ?? null; @endphp
                        @if($estudiante)
                        <div class="bg-white rounded-xl shadow p-4 text-center {{ $idx === 0 ? 'ring-2' : '' }}"
                            style="{{ $idx === 0 ? 'ring-color:#1CABE2;order:-1;' : '' }}">
                            <div class="text-3xl mb-1">{{ ['🥇','🥈','🥉'][$idx] }}</div>
                            <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center text-white font-bold text-sm"
                                style="background:#1D2458;">
                                {{ strtoupper(substr($estudiante->name, 0, 2)) }}
                            </div>
                            <p class="font-bold text-sm" style="color:#1D2458;">
                                {{ Str::limit($estudiante->name, 15) }}
                            </p>
                            <p class="text-xs mt-1" style="color:#4A7A9A;">
                                {{ Str::limit($estudiante->institucion ?? 'Sin IE', 20) }}
                            </p>
                            <p class="font-bold mt-2" style="color:#1CABE2;">
                                {{ $estudiante->progresos_sum_xp_ganado ?? 0 }} XP
                            </p>
                            <p class="text-xs mt-1" style="color:#4A7A9A;">
                                {{ $estudiante->nivelActual() }}
                            </p>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Tabla ranking completo --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#EEF7FC;border-bottom:1px solid rgba(28,171,226,0.15);">
                            <th class="text-left py-3 px-4 font-bold" style="color:#1D2458;">#</th>
                            <th class="text-left py-3 px-4 font-bold" style="color:#1D2458;">Estudiante</th>
                            <th class="text-left py-3 px-4 font-bold" style="color:#1D2458;">Institución</th>
                            <th class="text-left py-3 px-4 font-bold" style="color:#1D2458;">Grado</th>
                            <th class="text-left py-3 px-4 font-bold" style="color:#1D2458;">Nivel</th>
                            <th class="text-left py-3 px-4 font-bold" style="color:#1D2458;">XP</th>
                            <th class="text-left py-3 px-4 font-bold" style="color:#1D2458;">Insignias</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ranking as $idx => $estudiante)
                            @php
                                $esMiPerfil = auth()->id() === $estudiante->id;
                                $xp = $estudiante->progresos_sum_xp_ganado ?? 0;
                            @endphp
                            <tr class="{{ $esMiPerfil ? 'font-bold' : '' }}"
                                style="border-bottom:1px solid rgba(28,171,226,0.08);
                                       {{ $esMiPerfil ? 'background:#EEF7FC;' : '' }}
                                       transition:background 0.15s;">
                                <td class="py-3 px-4">
                                    <span class="font-bold" style="color:{{ $idx < 3 ? '#1CABE2' : '#4A7A9A' }};">
                                        {{ $idx === 0 ? '🥇' : ($idx === 1 ? '🥈' : ($idx === 2 ? '🥉' : '#'.($idx+1))) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                            style="background:#1D2458;">
                                            {{ strtoupper(substr($estudiante->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-xs" style="color:#1D2458;">
                                                {{ $estudiante->name }}
                                                @if($esMiPerfil)
                                                    <span style="color:#1CABE2;"> (Tú)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-xs" style="color:#4A7A9A;">
                                    {{ Str::limit($estudiante->institucion ?? '—', 25) }}
                                </td>
                                <td class="py-3 px-4 text-xs" style="color:#4A7A9A;">
                                    {{ $estudiante->grado ? $estudiante->grado.'° '.ucfirst($estudiante->nivel_educativo ?? '') : '—' }}
                                </td>
                                <td class="py-3 px-4 text-xs font-bold" style="color:#1CABE2;">
                                    {{ $estudiante->nivelActual() }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-bold text-sm" style="color:#1D2458;">{{ $xp }}</span>
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    @if($estudiante->insignias_count > 0)
                                        <span class="font-bold" style="color:#1CABE2;">{{ $estudiante->insignias_count }} 🏆</span>
                                    @else
                                        <span style="color:#C8DCE8;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-sm" style="color:#4A7A9A;">
                                    Aún no hay estudiantes en el ranking. ¡Sé el primero!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Nota al pie --}}
            <p class="text-xs text-center mt-4" style="color:#4A7A9A;">
                Mostrando los top 50 estudiantes de Huancavelica ordenados por XP acumulado.
            </p>

        </div>
    </div>
</x-app-layout>