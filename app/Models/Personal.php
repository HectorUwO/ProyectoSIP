<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Personal extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'personal';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'clave_empleado',
        'foto',
        'cargo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the profile image URL
     */
    public function getProfileImageAttribute()
    {
        if ($this->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }
        return asset('rsc/img/profile.png');
    }

    /**
     * Relación con proyectos como investigador principal
     */
    public function proyectos()
    {
        return $this->hasMany(\App\Models\Proyecto::class, 'investigador_id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }
}
