<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Aulas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Crear aula --}}
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-medium mb-4">Crear nueva aula</h3>
                <form method="POST" action="{{ route('docente.aulas.crear') }}">
                    @csrf
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <input type="text" name="nombre" placeholder="Nombre del aula (ej: 5to A)"
                                class="w-full border rounded px-3 py-2 text-sm"
                                value="{{ old('nombre') }}" required />
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex-1">
                            <input type="text" name="institucion" placeholder="Institución educativa"
                                class="w-full border rounded px-3 py-2 text-sm"
                                value="{{ old('institucion') }}" />
                        </div>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">
                            Crear aula
                        </button>
                    </div>
                </form>
            </div>

            {{-- Lista de aulas --}}
            @forelse($aulas as $aula)
                <div class="bg-white p-6 rounded-lg shadow mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-800">{{ $aula->nombre }}</h4>
                        <p class="text-sm text-gray-500">{{ $aula->institucion }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $aula->estudiantes_count }} estudiante(s)
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400 mb-1">Código de aula</p>
                        <span class="text-2xl font-bold tracking-widest text-indigo-600">
                            {{ $aula->codigo }}
                        </span>
                        <p class="text-xs text-gray-400 mt-1">Comparte este código con tus estudiantes</p>
                    </div>
                </div>
            @empty
                <div class="bg-white p-6 rounded-lg shadow text-center text-gray-400">
                    Aún no tienes aulas creadas. ¡Crea tu primera aula arriba!
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>