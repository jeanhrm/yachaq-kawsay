<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard docente
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @forelse($aulas as $aula)
                <div class="bg-white rounded-xl shadow p-6">

                    {{-- Header aula --}}
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $aula->nombre }}</h3>
                            <p class="text-sm text-gray-400">{{ $aula->institucion }} · Código: <span class="font-mono font-bold text-indigo-600">{{ $aula->codigo }}</span></p>
                        </div>
                        <span class="text-sm text-gray-400">{{ $aula->estudiantes_count }} estudiante(s)</span>
                    </div>

                    {{-- Tabla estudiantes --}}
                    @if($aula->estudiantes->isEmpty())
                        <p class="text-sm text-gray-400 text-center py-4">
                            Ningún estudiante se ha unido aún. Comparte el código <strong>{{ $aula->codigo }}</strong>.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100">
                                        <th class="text-left py-2 px-3 text-gray-400 font-medium">Estudiante</th>
                                        <th class="text-left py-2 px-3 text-gray-400 font-medium">Nivel</th>
                                        <th class="text-left py-2 px-3 text-gray-400 font-medium">XP</th>
                                        <th class="text-left py-2 px-3 text-gray-400 font-medium">Misión 1</th>
                                        <th class="text-left py-2 px-3 text-gray-400 font-medium">Misión 2</th>
                                        <th class="text-left py-2 px-3 text-gray-400 font-medium">Insignias</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($aula->estudiantes as $estudiante)
                                        @php
                                            $m1 = $estudiante->progresos->where('mision_id', 1)->first();
                                            $m2 = $estudiante->progresos->where('mision_id', 2)->first();
                                        @endphp
                                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                                            <td class="py-3 px-3">
                                                <div class="font-medium text-gray-800">{{ $estudiante->name }}</div>
                                                <div class="text-xs text-gray-400">{{ $estudiante->email }}</div>
                                            </td>
                                            <td class="py-3 px-3">
                                                <span class="text-indigo-600 font-medium">{{ $estudiante->nivelActual() }}</span>
                                            </td>
                                            <td class="py-3 px-3">
                                                <span class="font-mono">{{ $estudiante->xpTotal() }}</span>
                                            </td>
                                            <td class="py-3 px-3">
                                                @if($m1)
                                                    @if($m1->completada)
                                                        <span class="text-green-600">✓ Completada</span>
                                                    @else
                                                        <div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden">
                                                            <div class="h-2 bg-indigo-400 rounded-full" style="width: {{ min(100, ($m1->xp_ganado / 115) * 100) }}%"></div>
                                                        </div>
                                                        <span class="text-xs text-gray-400">{{ $m1->xp_ganado }} XP</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-300">Sin iniciar</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-3">
                                                @if($m2)
                                                    @if($m2->completada)
                                                        <span class="text-green-600">✓ Completada</span>
                                                    @else
                                                        <div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden">
                                                            <div class="h-2 bg-indigo-400 rounded-full" style="width: {{ min(100, ($m2->xp_ganado / 115) * 100) }}%"></div>
                                                        </div>
                                                        <span class="text-xs text-gray-400">{{ $m2->xp_ganado }} XP</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-300">Sin iniciar</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-3">
                                                <span class="text-lg">
                                                    @foreach($estudiante->insignias as $insignia)
                                                        {{ $insignia->emoji }}
                                                    @endforeach
                                                    @if($estudiante->insignias->isEmpty())
                                                        <span class="text-gray-300 text-sm">Ninguna aún</span>
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-xl shadow p-8 text-center text-gray-400">
                    <p>No tienes aulas creadas.</p>
                    <a href="{{ route('docente.aulas') }}" class="text-indigo-600 text-sm mt-2 inline-block">
                        Crear mi primera aula →
                    </a>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>