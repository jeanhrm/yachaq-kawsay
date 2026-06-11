<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color:#1D2458;">
            Editar mi perfil
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-lg mx-auto px-3 sm:px-6">
            <div class="bg-white rounded-xl shadow p-5 sm:p-6">

                <form method="POST" action="{{ route('perfil.actualizar') }}">
                    @csrf
                    @method('PUT')

                    @if(session('success'))
                        <div class="mb-4 p-3 rounded-lg text-sm font-bold"
                            style="background:#EEF7FC;color:#1CABE2;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-xs font-bold mb-1" style="color:#1D2458;">
                            Nombre completo
                        </label>
                        <input type="text" name="name"
                            value="{{ old('name', auth()->user()->name) }}"
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#C8DCE8;color:#1D2458;"
                            required>
                        @error('name')<p class="text-xs mt-1" style="color:#E53E3E;">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold mb-1" style="color:#1D2458;">
                            Institución educativa
                        </label>
                        <input type="text" name="institucion"
                            value="{{ old('institucion', auth()->user()->institucion) }}"
                            placeholder="Ej: IE Santa Ana"
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#C8DCE8;color:#1D2458;">
                        @error('institucion')<p class="text-xs mt-1" style="color:#E53E3E;">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold mb-1" style="color:#1D2458;">
                            Nivel educativo
                        </label>
                        <select name="nivel_educativo" id="select-nivel"
                            onchange="actualizarGrados(this.value)"
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#C8DCE8;color:#1D2458;">
                            <option value="">Selecciona...</option>
                            <option value="primaria" {{ auth()->user()->nivel_educativo === 'primaria' ? 'selected' : '' }}>
                                Primaria
                            </option>
                            <option value="secundaria" {{ auth()->user()->nivel_educativo === 'secundaria' ? 'selected' : '' }}>
                                Secundaria
                            </option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color:#1D2458;">Grado</label>
                            <select name="grado" id="select-grado"
                                class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                                style="border-color:#C8DCE8;color:#1D2458;">
                                <option value="">Selecciona...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1" style="color:#1D2458;">Sección</label>
                            <input type="text" name="seccion"
                                value="{{ old('seccion', auth()->user()->seccion) }}"
                                placeholder="Ej: A"
                                class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                                style="border-color:#C8DCE8;color:#1D2458;text-transform:uppercase;">
                        </div>
                    </div>

                    <div class="mb-4" style="border-top:1px solid #EEF7FC;padding-top:1rem;">
                        <label class="block text-xs font-bold mb-1" style="color:#1D2458;">
                            Nueva contraseña <span style="color:#4A7A9A;font-weight:400;">(dejar en blanco para no cambiar)</span>
                        </label>
                        <input type="password" name="password"
                            placeholder="Mínimo 8 caracteres"
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none mb-2"
                            style="border-color:#C8DCE8;color:#1D2458;">
                        <input type="password" name="password_confirmation"
                            placeholder="Confirmar nueva contraseña"
                            class="w-full border-2 rounded-lg px-3 py-2 text-sm focus:outline-none"
                            style="border-color:#C8DCE8;color:#1D2458;">
                        @error('password')<p class="text-xs mt-1" style="color:#E53E3E;">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex gap-3 justify-end mt-4">
                        <a href="{{ route('estudiante.perfil') }}"
                            class="px-4 py-2 rounded-lg text-sm font-bold"
                            style="background:#F5F5F5;color:#4A7A9A;">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-6 py-2 rounded-lg text-sm font-bold text-white"
                            style="background:#1D2458;">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function actualizarGrados(nivel) {
        const select = document.getElementById('select-grado');
        select.innerHTML = '<option value="">Selecciona...</option>';
        const max = nivel === 'primaria' ? 6 : 5;
        for (let i = 1; i <= max; i++) {
            const selected = {{ auth()->user()->grado ?? 0 }} === i ? 'selected' : '';
            select.innerHTML += `<option value="${i}" ${selected}>${i}° ${nivel.charAt(0).toUpperCase() + nivel.slice(1)}</option>`;
        }
    }

    // Cargar grados al inicio
    const nivelActual = '{{ auth()->user()->nivel_educativo }}';
    if (nivelActual) actualizarGrados(nivelActual);
    </script>
</x-app-layout>