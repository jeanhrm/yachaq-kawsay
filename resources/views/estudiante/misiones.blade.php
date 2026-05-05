<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Misiones
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 p-4 bg-indigo-50 rounded-lg">
                <p class="text-sm text-indigo-700">
                    Nivel: <strong>{{ auth()->user()->nivelActual() }}</strong> ·
                    XP total: <strong>{{ auth()->user()->xpTotal() }}</strong>
                </p>
            </div>

            @php $misiones = \App\Models\Mision::where('activa', true)->orderBy('orden')->get(); @endphp

            <div class="grid gap-4 md:grid-cols-2">
                @foreach($misiones as $mision)
                    @php
                        $progreso = auth()->user()->progresos->where('mision_id', $mision->id)->first();
                    @endphp
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-start gap-4">
                            <div class="text-4xl">🏔️</div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">{{ $mision->titulo }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $mision->descripcion }}</p>

                                @if($progreso)
                                    <div class="mt-3">
                                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                                            <span>{{ $progreso->completada ? 'Completada' : 'En progreso' }}</span>
                                            <span>{{ $progreso->xp_ganado }} XP</span>
                                        </div>
                                        <div class="h-2 bg-gray-100 rounded-full">
                                            <div class="h-2 bg-indigo-500 rounded-full"
                                                style="width: {{ $progreso->completada ? '100' : '50' }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('estudiante.mision.jugar', $mision) }}"
                                   class="inline-block mt-4 bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                    {{ $progreso ? ($progreso->completada ? 'Ver misión' : 'Continuar') : 'Comenzar misión' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>