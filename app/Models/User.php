<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'aula_id',
    'nivel_educativo',
    'grado',
    'seccion',
    'institucion',
    ])]
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
    public function gradoCompleto(): string
    {
        if (!$this->grado || !$this->nivel_educativo) return 'Sin grado asignado';
        $nivel = $this->nivel_educativo === 'primaria' ? 'Primaria' : 'Secundaria';
        return "{$this->grado}° {$nivel}" . ($this->seccion ? " — Sección {$this->seccion}" : '');
    }

    public function cicloEBR(): string
    {
        if ($this->nivel_educativo === 'primaria') {
            return match(true) {
                in_array($this->grado, [1, 2]) => 'Ciclo III',
                in_array($this->grado, [3, 4]) => 'Ciclo IV',
                in_array($this->grado, [5, 6]) => 'Ciclo V',
                default => 'Primaria'
            };
        }
        if ($this->nivel_educativo === 'secundaria') {
            return match(true) {
                in_array($this->grado, [1, 2]) => 'Ciclo VI',
                in_array($this->grado, [3, 4, 5]) => 'Ciclo VII',
                default => 'Secundaria'
            };
        }
        return 'Sin ciclo';
    }

}
