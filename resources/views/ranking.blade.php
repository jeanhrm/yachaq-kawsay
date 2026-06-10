<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color:#1D2458;">
            🏆 Ranking Global — Niños indagadores
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">

            {{-- Mi posición --}}
            @if(auth()->user()->isEstudiante())
                <div class="rounded-xl p-3 sm:p-4 mb-4 sm:mb-6 flex items-center justify-between"
                    style="background:#EEF7FC;border:1px solid rgba(28,171,226,0.2);">
                    <div>
                        <p class="text-sm font-bold" style="color:#1D2458;">Tu posición actual</p>
                        <p class="text-xs mt-1" style="color:#4A7A9A;">
                            {{ auth()->user()->nivelActual() }} — {{ auth()->user()->xpTotal() }} XP
                        </p>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold" style="color:#1CABE2;">
                        {{ $miPosicion ? '#'.$miPosicion : 'Sin ranking' }}
                    </div>
                </div>
            @endif

            {{-- Top 3 -- solo en pantallas medianas+ --}}
            @if($ranking->count() >= 3)
                <div class="hidden sm:grid grid-cols-3 gap-4 mb-6">
                    @foreach([1, 0, 2] as $idx)
                        @php $estudiante = $ranking[$idx] ?? null; @endphp
                        @if($estudiante)
                        <div class="bg-white rounded-xl shadow p-4 text-center">
                            <div class="text-3xl mb-1">{{ ['🥇','🥈','🥉'][$idx] }}</div>
                            <div class="w-10 h-10 rounded-full mx-auto mb-2 flex items-center justify-center text-white font-bold text-xs"
                                style="background:#1D2458;">
                                {{ strtoupper(substr($estudiante->name, 0, 2)) }}
                            </div>
                            <p class="font-bold text-xs" style="color:#1D2458;">
                                {{ Str::limit($estudiante->name, 15) }}
                            </p>
                            <p class="text-xs mt-1" style="color:#4A7A9A;">
                                {{ Str::limit($estudiante->institucion ?? 'Sin IE', 20) }}
                            </p>
                            <p class="font-bold mt-1" style="color:#1CABE2;">
                                {{ $estudiante->progresos_sum_xp_ganado ?? 0 }} XP
                            </p>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Tabla ranking -- scroll horizontal en móvil --}}
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#EEF7FC;border-bottom:1px solid rgba(28,171,226,0.15);">
                                <th class="text-left py-3 px-3 font-bold text-xs" style="color:#1D2458;">#</th>
                                <th class="text-left py-3 px-3 font-bold text-xs" style="color:#1D2458;">Estudiante</th>
                                <th class="text-left py-3 px-3 font-bold text-xs hidden sm:table-cell" style="color:#1D2458;">Institución</th>
                                <th class="text-left py-3 px-3 font-bold text-xs hidden sm:table-cell" style="color:#1D2458;">Grado</th>
                                <th class="text-left py-3 px-3 font-bold text-xs" style="color:#1D2458;">Nivel</th>
                                <th class="text-left py-3 px-3 font-bold text-xs" style="color:#1D2458;">XP</th>
                                <th class="text-left py-3 px-3 font-bold text-xs hidden sm:table-cell" style="color:#1D2458;">Insignias</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ranking as $idx => $estudiante)
                                @php
                                    $esMiPerfil = auth()->id() === $estudiante->id;
                                    $xp = $estudiante->progresos_sum_xp_ganado ?? 0;
                                @endphp
                                <tr style="border-bottom:1px solid rgba(28,171,226,0.08);
                                           {{ $esMiPerfil ? 'background:#EEF7FC;' : '' }}">
                                    <td class="py-3 px-3">
                                        <span class="font-bold text-xs"
                                            style="color:{{ $idx < 3 ? '#1CABE2' : '#4A7A9A' }};">
                                            {{ $idx === 0 ? '🥇' : ($idx === 1 ? '🥈' : ($idx === 2 ? '🥉' : '#'.($idx+1))) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                                style="background:#1D2458;">
                                                {{ strtoupper(substr($estudiante->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-xs" style="color:#1D2458;">
                                                    {{ Str::limit($estudiante->name, 12) }}
                                                    @if($esMiPerfil)
                                                        <span style="color:#1CABE2;"> (Tú)</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 text-xs hidden sm:table-cell" style="color:#4A7A9A;">
                                        {{ Str::limit($estudiante->institucion ?? '—', 20) }}
                                    </td>
                                    <td class="py-3 px-3 text-xs hidden sm:table-cell" style="color:#4A7A9A;">
                                        {{ $estudiante->grado ? $estudiante->grado.'° '.ucfirst($estudiante->nivel_educativo ?? '') : '—' }}
                                    </td>
                                    <td class="py-3 px-3 text-xs font-bold" style="color:#1CABE2;">
                                        {{ $estudiante->nivelActual() }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="font-bold text-xs" style="color:#1D2458;">{{ $xp }}</span>
                                    </td>
                                    <td class="py-3 px-3 text-xs hidden sm:table-cell">
                                        @if($estudiante->insignias_count > 0)
                                            <span class="font-bold" style="color:#1CABE2;">
                                                {{ $estudiante->insignias_count }} 🏆
                                            </span>
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
            </div>

            <p class="text-xs text-center mt-4" style="color:#4A7A9A;">
                Top 50 estudiantes de Huancavelica ordenados por XP acumulado.
            </p>

        </div>
    </div>
</x-app-layout>