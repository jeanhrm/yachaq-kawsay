<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $mision->titulo }}
        </h2>
    </x-slot>

    <livewire:mision-juego :mision="$mision" :lugar-id="$lugarId ?? null" />
</x-app-layout>