<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color:#1D2458;">
            🔬 Gestión de Misiones
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 rounded-lg text-sm font-bold"
                    style="background:#EEF7FC;color:#1CABE2;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Formulario crear misión --}}
            <div class="bg-white rounded-xl shadow p-6 mb-6">
                <h3 class="font-bold mb-4" style="color:#1D2458;">+ Nueva misión de indagación</h3>
                <p class="text-xs mb-4" style="color:#4A7A9A;">
                    Las 5 fases del ciclo de indagación (Tapukuy, Yuyaychakuy, Hap'iy, Yachaqay, Tukuchiy) se generan automáticamente con IA según el contexto de tu misión. Si ya tienes misiones con fases placeholder, usa el botón 🤖 Regenerar para actualizarlas.                </p>

                <form method="POST" action="{{ route('docente.misiones.crear') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-xs font-bold mb-1" style="color:#1D2458;">
                            Título de la misión
                        </label>
                        <input type="text" name="titulo"
                            value="{{ old('titulo') }}"
                            placeholder="Ej: El suelo de las chakras andinas"
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#C8DCE8;color:#1D2458;"
                            required>
                        @error('titulo')<p class="text-xs mt-1" style="color:#E53E3E;">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold mb-1" style="color:#1D2458;">
                            Pregunta de investigación
                        </label>
                        <input type="text" name="pregunta_investigacion"
                            value="{{ old('pregunta_investigacion') }}"
                            placeholder="Ej: ¿Por qué el suelo de la chakra es más oscuro que el de los cerros?"
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#C8DCE8;color:#1D2458;"
                            required>
                        @error('pregunta_investigacion')<p class="text-xs mt-1" style="color:#E53E3E;">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold mb-1" style="color:#1D2458;">
                            Descripción breve
                        </label>
                        <textarea name="descripcion" rows="2"
                            placeholder="Describe en qué consiste la misión..."
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none resize-none"
                            style="border-color:#C8DCE8;color:#1D2458;"
                            required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')<p class="text-xs mt-1" style="color:#E53E3E;">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold mb-1" style="color:#1D2458;">
                            Contexto andino
                        </label>
                        <textarea name="contexto_andino" rows="3"
                            placeholder="Describe el contexto cultural y geográfico andino de esta misión..."
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none resize-none"
                            style="border-color:#C8DCE8;color:#1D2458;"
                            required>{{ old('contexto_andino') }}</textarea>
                        @error('contexto_andino')<p class="text-xs mt-1" style="color:#E53E3E;">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="text-white px-6 py-2 rounded-lg text-sm font-bold transition"
                            style="background:#1D2458;">
                            Crear misión con 5 fases →
                        </button>
                    </div>
                </form>
            </div>

            {{-- Lista de misiones --}}
            <div class="space-y-4">
                @forelse($misiones as $mision)
                    <div class="bg-white rounded-xl shadow p-5">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                                        style="background:#EEF7FC;color:#1CABE2;">
                                        Misión {{ $mision->orden }}
                                    </span>
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                                        style="background:{{ $mision->activa ? '#E6FAF0' : '#FFF5F5' }};
                                               color:{{ $mision->activa ? '#0E7C4A' : '#E53E3E' }};">
                                        {{ $mision->activa ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </div>
                                <h3 class="font-bold" style="color:#1D2458;">{{ $mision->titulo }}</h3>
                                <p class="text-xs mt-1" style="color:#4A7A9A;">
                                    {{ $mision->pregunta_investigacion }}
                                </p>
                                <p class="text-xs mt-2" style="color:#4A7A9A;">
                                    {{ $mision->fases_count }} fases · {{ $mision->descripcion }}
                                </p>
                            </div>
                            <div class="flex gap-2 ml-4 flex-wrap">
                            <form method="POST"
                                action="{{ route('docente.misiones.regenerar', $mision) }}">
                                @csrf
                                <button type="submit"
                                    class="text-xs font-bold px-3 py-2 rounded-lg"
                                    style="background:#EEF7FC;color:#1CABE2;border:1px solid rgba(28,171,226,0.2);">
                                    🤖 Regenerar fases con IA
                                </button>
                            </form>
                            <form method="POST"
                                action="{{ route('docente.misiones.eliminar', $mision) }}"
                                onsubmit="return confirm('¿Eliminar esta misión y todas sus fases?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-xs font-bold px-3 py-2 rounded-lg"
                                    style="background:#FFF5F5;color:#E53E3E;border:1px solid rgba(229,83,62,0.2);">
                                    🗑️ Eliminar
                                </button>
                            </form>
                        </div>

                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow p-8 text-center" style="color:#4A7A9A;">
                        No hay misiones creadas aún.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>