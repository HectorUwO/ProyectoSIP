<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;

    protected $table = 'colaboradores';

    protected $fillable = [
        'proyecto_id',
        'nombre_completo',
        'actividad',
        'tipo_colaborador'
    ];

    /**
     * Relación con proyecto
     */
    public function proyecto()
    {
        return $this->belongsTo(\App\Models\Proyecto::class);
    }

    /**
     * Scopes para filtrar por tipo
     */
    public function scopeProfesores($query)
    {
        return $query->where('tipo_colaborador', 'profesor');
    }

    public function scopeEstudiantes($query)
    {
        return $query->where('tipo_colaborador', 'estudiante');
    }
}
