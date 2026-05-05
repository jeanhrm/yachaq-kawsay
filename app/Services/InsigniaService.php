<?php

namespace App\Services;

use App\Models\User;
use App\Models\Insignia;
use App\Models\InsigniaUsuario;

class InsigniaService
{
    public function verificarYDesbloquear(User $user): array
    {
        $nuevas = [];
        $insignias = Insignia::all();

        foreach ($insignias as $insignia) {
            if ($user->insignias->contains($insignia->id)) continue;

            if ($this->condicionCumplida($user, $insignia)) {
                InsigniaUsuario::create([
                    'user_id'         => $user->id,
                    'insignia_id'     => $insignia->id,
                    'desbloqueada_en' => now(),
                ]);
                $nuevas[] = $insignia;
            }
        }

        return $nuevas;
    }

    private function condicionCumplida(User $user, Insignia $insignia): bool
    {
        $progresos = $user->progresos()->with('mision')->get();
        $interacciones = $user->interacciones()->where('fase_aprobada', true)->get();

        return match($insignia->condicion) {
            'mision_completada' => $progresos
                ->where('mision_id', $insignia->valor_condicion)
                ->where('completada', true)
                ->isNotEmpty(),

            'misiones_completadas' => $progresos
                ->where('completada', true)
                ->count() >= $insignia->valor_condicion,

            'fases_completadas' => $interacciones->count() >= $insignia->valor_condicion,

            'fases_logradas' => $interacciones
                ->where('nivel_logrado', '>=', 3)
                ->count() >= $insignia->valor_condicion,

            'hipotesis_logradas' => $interacciones
                ->where('nivel_logrado', '>=', 3)
                ->count() >= $insignia->valor_condicion,

            'conclusiones_logradas' => $interacciones
                ->where('nivel_logrado', '>=', 3)
                ->count() >= $insignia->valor_condicion,

            'xp_total' => $user->xpTotal() >= $insignia->valor_condicion,

            default => false,
        };
    }
}