<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mision;

class LimpiarMisiones extends Command
{
    protected $signature = 'misiones:limpiar';
    protected $description = 'Elimina misiones duplicadas';

    public function handle()
    {
        Mision::truncate();
        $this->call('db:seed', ['--class' => 'MisionesSeeder', '--force' => true]);
        $this->info('Misiones limpiadas y recreadas correctamente.');
    }
}