<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name',
    'email',
    'password',
    'role',
    'aula_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aula_id');
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class, 'docente_id');
    }

    public function isDocente(): bool
    {
        return $this->role === 'docente';
    }

    public function isEstudiante(): bool
    {
        return $this->role === 'estudiante';
    }

    public function progresos()
    {
        return $this->hasMany(ProgresoEstudiante::class);
    }

    public function interacciones()
    {
        return $this->hasMany(InteraccionIA::class);
    }

    public function xpTotal(): int
    {
        return $this->progresos()->sum('xp_ganado');
    }

    public function nivelActual(): string
    {
        $xp = $this->xpTotal();

        return match(true) {
            $xp >= 700 => 'Apu Yachaq',
            $xp >= 450 => 'Yachaq',
            $xp >= 250 => 'Qawaq',
            $xp >= 100 => 'Tapuq',
            default    => 'Musuq Yachaq',
        };
    }
    public function insignias()
    {
        return $this->belongsToMany(Insignia::class, 'insignia_usuario')
            ->withPivot('desbloqueada_en')
            ->withTimestamps();
    }

}
