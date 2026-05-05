<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nombre --}}
        <div>
            <x-input-label for="name" :value="__('Nombre completo')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text"
                name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="mt-4">
            <x-input-label for="email" :value="__('Correo electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email"
                name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Rol --}}
        <div class="mt-4">
            <x-input-label :value="__('Soy...')" />
            <div class="flex gap-4 mt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="role" value="docente"
                        {{ old('role') === 'docente' ? 'checked' : '' }}
                        onclick="document.getElementById('campo-codigo').classList.add('hidden')"
                        class="text-indigo-600" />
                    <span class="text-sm text-gray-700">Docente</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="role" value="estudiante"
                        {{ old('role') === 'estudiante' ? 'checked' : '' }}
                        onclick="document.getElementById('campo-codigo').classList.remove('hidden')"
                        class="text-indigo-600" />
                    <span class="text-sm text-gray-700">Estudiante</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        {{-- Código de aula (solo estudiante) --}}
        <div id="campo-codigo" class="{{ old('role') === 'estudiante' ? '' : 'hidden' }} mt-4">
            <x-input-label for="codigo" :value="__('Código de aula')" />
            <x-text-input id="codigo" class="block mt-1 w-full uppercase" type="text"
                name="codigo" :value="old('codigo')" placeholder="Ej: ABC123" />
            <x-input-error :messages="$errors->get('codigo')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirmar password --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                ¿Ya tienes cuenta?
            </a>
            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>