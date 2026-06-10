<?php

namespace App\Http\Controllers;

use App\Models\User;

class RankingController extends Controller
{
    public function index()
    {
        $ranking = User::where('role', 'estudiante')
            ->withSum('progresos', 'xp_ganado')
            ->withCount(['insignias'])
            ->orderByDesc('progresos_sum_xp_ganado')
            ->take(50)
            ->get();

        $miPosicion = null;
        $userId = auth()->id();

        foreach ($ranking as $idx => $user) {
            if ($user->id === $userId) {
                $miPosicion = $idx + 1;
                break;
            }
        }

        return view('ranking', compact('ranking', 'miPosicion'));
    }
}