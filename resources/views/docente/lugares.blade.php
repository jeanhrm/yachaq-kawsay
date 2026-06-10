<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lugares y códigos QR
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 p-4 rounded-xl" style="background:#EEF7FC;border:1px solid rgba(28,171,226,0.2);">
                <p class="text-sm font-bold" style="color:#1D2458;">
                    🏔️ Descarga el QR de cada lugar e imprímelo. Cuando un estudiante lo escanee será dirigido directamente a la misión correspondiente.
                </p>
            </div>

            {{-- Formulario crear lugar --}}
            <div class="bg-white rounded-xl shadow p-6 mb-6">
                <h3 class="font-bold mb-4" style="color:#1D2458;">+ Agregar nuevo lugar</h3>
                <form method="POST" action="{{ route('docente.lugares.crear') }}">
                    @csrf
                    @if(session('success'))
                        <div class="mb-4 p-3 rounded-lg text-sm font-bold"
                            style="background:#EEF7FC;color:#1CABE2;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color:#1D2458;">Nombre del lugar</label>
                            <input type="text" name="nombre"
                                value="{{ old('nombre') }}"
                                placeholder="Ej: Río Ichu, Plaza de Armas..."
                                class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                                style="border-color:#C8DCE8;color:#1D2458;"
                                required>
                            @error('nombre')<p class="text-xs mt-1" style="color:#E53E3E;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color:#1D2458;">Ubicación</label>
                            <input type="text" name="ubicacion"
                                value="{{ old('ubicacion') }}"
                                placeholder="Ej: Huancavelica, Huancavelica"
                                class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                                style="border-color:#C8DCE8;color:#1D2458;">
                            @error('ubicacion')<p class="text-xs mt-1" style="color:#E53E3E;">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-xs font-bold mb-1" style="color:#1D2458;">Descripción</label>
                        <textarea name="descripcion" rows="2"
                            placeholder="Describe qué pueden observar los estudiantes en este lugar..."
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none resize-none"
                            style="border-color:#C8DCE8;color:#1D2458;">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color:#1D2458;">Misión asociada</label>
                            <select name="mision_id"
                                class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                                style="border-color:#C8DCE8;color:#1D2458;"
                                required>
                                <option value="">Selecciona...</option>
                                @foreach($misiones as $mision)
                                    <option value="{{ $mision->id }}" {{ old('mision_id') == $mision->id ? 'selected' : '' }}>
                                        {{ $mision->titulo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mision_id')<p class="text-xs mt-1" style="color:#E53E3E;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color:#1D2458;">Latitud <span style="color:#4A7A9A;font-weight:400;">(opcional)</span></label>
                            <input type="text" name="latitud"
                                value="{{ old('latitud') }}"
                                placeholder="-12.7869"
                                class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                                style="border-color:#C8DCE8;color:#1D2458;">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color:#1D2458;">Longitud <span style="color:#4A7A9A;font-weight:400;">(opcional)</span></label>
                            <input type="text" name="longitud"
                                value="{{ old('longitud') }}"
                                placeholder="-74.9758"
                                class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                                style="border-color:#C8DCE8;color:#1D2458;">
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit"
                            class="text-white px-6 py-2 rounded-lg text-sm font-bold transition"
                            style="background:#1D2458;">
                            Crear lugar y generar QR
                        </button>
                    </div>
                </form>
            </div>


            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($lugares as $lugar)
                    <div class="bg-white rounded-xl shadow p-6">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-sm" style="color:#1D2458;">{{ $lugar->nombre }}</h3>
                                <p class="text-xs mt-1" style="color:#4A7A9A;">{{ $lugar->ubicacion }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full font-bold"
                                style="background:#EEF7FC;color:#1CABE2;">
                                {{ $lugar->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <p class="text-xs mb-4" style="color:#4A7A9A;line-height:1.6;">
                            {{ $lugar->descripcion }}
                        </p>

                        <div class="text-xs mb-4 font-bold" style="color:#1D2458;">
                            🔬 Misión: {{ $lugar->mision->titulo }}
                        </div>

                        {{-- Preview QR --}}
                        <div class="flex justify-center mb-4">
                            <object 
                                data="{{ route('qr.generar', $lugar->slug) }}"
                                type="image/svg+xml"
                                style="width:150px;height:150px;border:2px solid rgba(28,171,226,0.2);border-radius:8px;">
                            </object>
                        </div>

                        <div class="text-center text-xs mb-4" style="color:#4A7A9A;">
                            {{ url('/lugar/'.$lugar->slug) }}
                        </div>

                        <a href="{{ route('docente.lugares.qr', $lugar) }}"
                           class="block text-center text-white text-sm font-bold py-2 rounded-lg transition"
                           style="background:#1D2458;"
                           download="qr-{{ $lugar->slug }}.png">
                            ⬇️ Descargar QR
                        </a>
                        {{-- Botón eliminar --}}
                        <form method="POST" action="{{ route('docente.lugares.eliminar', $lugar) }}"
                            onsubmit="return confirm('¿Seguro que quieres eliminar este lugar?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full text-center text-sm font-bold py-2 rounded-lg transition"
                                style="background:#FFF5F5;color:#E53E3E;border:1px solid rgba(229,83,62,0.2);">
                                🗑️ Eliminar lugar
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>