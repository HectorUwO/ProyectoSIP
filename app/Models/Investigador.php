<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Investigador extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'investigadores';

    protected $fillable = [
        'nombre', 'email', 'password', 'clave_empleado', 'programa_academico',
        'nivel_academico', 'sni', 'perfil_prodep', 'cuerpo_academico',
        'grado_consolidacion_ca', 'telefono', 'foto'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'sni' => 'boolean',
        'perfil_prodep' => 'boolean',
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
}
