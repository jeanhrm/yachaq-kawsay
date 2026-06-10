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
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>