<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyecto_id',
        'personal_id',
        'contenido',
        'tipo'
    ];

    /**
     * Relación con proyecto
     */
    public function proyecto()
    {
        return $this->belongsTo(\App\Models\Proyecto::class);
    }

    /**
     * Relación con personal que hizo el comentario
     */
    public function personal()
    {
        return $this->belongsTo(\App\Models\Personal::class);
    }
}
