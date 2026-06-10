<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lugar;

class LugaresSeeder extends Seeder
{
    public function run(): void
    {
        $lugares = [
            [
                'nombre'      => 'Río Ichu',
                'slug'        => 'rio-ichu',
                'descripcion' => 'El río Ichu es la principal fuente de agua de Huancavelica. Aquí puedes observar cómo el agua cambia después de las lluvias.',
                'ubicacion'   => 'Huancavelica, Huancavelica',
                'latitud'     => -12.7869,
                'longitud'    => -74.9758,
                'mision_id'   => 1,
                'activo'      => true,
            ],
            [
                'nombre'      => 'Jardín de la IE Santa Ana',
                'slug'        => 'jardin-santa-ana',
                'descripcion' => 'El jardín de la institución educativa Santa Ana. Aquí puedes observar cómo el helaje afecta a las plantas.',
                'ubicacion'   => 'IE Santa Ana, Huancavelica',
                'latitud'     => -12.7850,
                'longitud'    => -74.9741,
                'mision_id'   => 2,
                'activo'      => true,
            ],
            [
                'nombre'      => 'Mercado Central',
                'slug'        => 'mercado-central',
                'descripcion' => 'En el mercado central puedes encontrar las papas nativas de Huancavelica y aprender sobre sus variedades.',
                'ubicacion'   => 'Mercado Central, Huancavelica',
                'latitud'     => -12.7863,
                'longitud'    => -74.9752,
                'mision_id'   => 2,
                'activo'      => true,
            ],
            [
                'nombre'      => 'Plaza de Armas',
                'slug'        => 'plaza-de-armas',
                'descripcion' => 'La Plaza de Armas de Huancavelica. Observa la vegetación y cómo las plantas se adaptan al clima andino.',
                'ubicacion'   => 'Plaza de Armas, Huancavelica',
                'latitud'     => -12.7856,
                'longitud'    => -74.9745,
                'mision_id'   => 1,
                'activo'      => true,
            ],
        ];

        foreach ($lugares as $lugar) {
            Lugar::create($lugar);
        }
    }
}