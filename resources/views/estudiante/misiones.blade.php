<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Misiones
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow text-center text-gray-400">
                🏔️ Las misiones andinas llegarán pronto, {{ auth()->user()->name }}.
            </div>
        </div>
    </div>
</x-app-layout>