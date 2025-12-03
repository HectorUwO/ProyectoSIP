<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion_breve',
        'fecha_inicio',
        'fecha_termino',
        'area_uan',
        'area_inegi',
        'palabras_clave',
        'tipo_investigacion',
        'tipo_financiamiento',
        'fuente_financiamiento',
        'monto_aprobado',
        'tipo_fondo',
        'accion_transferencia',
        'articulos_indexada',
        'articulos_arbitrada',
        'libros',
        'capitulo_libro',
        'memorias_congreso',
        'tesis',
        'material_didactico',
        'otros_entregables',
        'productos_entregables',
        'resultados_esperados',
        'usuario_especifico',
        'impactos',
        'cronograma_actividades',
        'no_registro',
        'archivo_protocolo',
        'estado',
        'investigador_id',
        'personal_id'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_termino' => 'date',
        'monto_aprobado' => 'decimal:2',
        'articulos_indexada' => 'integer',
        'articulos_arbitrada' => 'integer',
        'libros' => 'integer',
        'capitulo_libro' => 'integer',
        'memorias_congreso' => 'integer',
        'tesis' => 'integer',
        'material_didactico' => 'integer',
        'productos_entregables' => 'array',
        'impactos' => 'array',
        'cronograma_actividades' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'estado' => 'en_revision'
    ];

    /**
     * Relación con colaboradores
     */
    public function colaboradores()
    {
        return $this->hasMany(\App\Models\Colaborador::class);
    }

    /**
     * Relación con investigador principal
     */
    public function investigador()
    {
        return $this->belongsTo(\App\Models\Investigador::class, 'investigador_id');
    }

    /**
     * Relación con comentarios
     */
    public function comentarios()
    {
        return $this->hasMany(\App\Models\Comentario::class);
    }

    /**
     * Scopes
     */
    public function scopeEnRevision($query)
    {
        return $query->where('estado', 'en_revision');
    }

    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    public function scopeRechazados($query)
    {
        return $query->where('estado', 'rechazado');
    }

    /**
     * Accessors
     */
    public function getEstadoBadgeAttribute()
    {
        $badges = [
            'en_revision' => 'badge-warning',
            'aprobado' => 'badge-success',
            'rechazado' => 'badge-danger',
            'con_observaciones' => 'badge-warning',
            'finalizado' => 'badge-info'
        ];

        return $badges[$this->estado] ?? 'badge-secondary';
    }

    public function getDuracionAttribute()
    {
        return Carbon::parse($this->fecha_inicio)->diffInMonths(Carbon::parse($this->fecha_termino));
    }

    /**
     * Format area UAN for display
     */
    public function getAreaUanFormattedAttribute()
    {
        $areas = [
            'ciencias_basicas_ingenierias' => 'Ciencias Básicas e Ingenierías',
            'ciencias_biologicas_agropecuarias' => 'Ciencias Biológicas y Agropecuarias',
            'ciencias_economicas_administrativas' => 'Ciencias Económicas y Administrativas',
            'ciencias_salud' => 'Ciencias de la Salud',
            'ciencias_sociales_humanidades' => 'Ciencias Sociales y Humanidades',
            'artes' => 'Artes'
        ];

        return $areas[$this->area_uan] ?? $this->area_uan;
    }

    /**
     * Format area INEGI for display
     */
    public function getAreaInegiFormattedAttribute()
    {
        $areas = [
            'educacion' => 'Educación',
            'artes_humanidades' => 'Artes y Humanidades',
            'ciencias_sociales_derecho' => 'Ciencias Sociales y Derecho',
            'ciencias_naturales_exactas_computacion' => 'Ciencias Naturales, Exactas y de la Computación',
            'ingenieria_manufactura_construccion' => 'Ingeniería, Manufactura y Construcción',
            'agronomia_veterinaria' => 'Agronomía y Veterinaria',
            'salud' => 'Salud',
            'servicios' => 'Servicios',
            'administracion_negocios' => 'Administración y Negocios',
            'tecnologia_informacion' => 'Tecnología de la Información'
        ];

        return $areas[$this->area_inegi] ?? $this->area_inegi;
    }

    /**
     * Format tipo investigacion for display
     */
    public function getTipoInvestigacionFormattedAttribute()
    {
        $tipos = [
            'basica' => 'Básica',
            'aplicada' => 'Aplicada',
            'experimental' => 'Experimental',
            'educativa' => 'Educativa',
            'intervencion_social' => 'Intervención Social',
            'otra' => 'Otra'
        ];

        return $tipos[$this->tipo_investigacion] ?? $this->tipo_investigacion;
    }

    /**
     * Format tipo financiamiento for display
     */
    public function getTipoFinanciamientoFormattedAttribute()
    {
        $tipos = [
            'sin_financiamiento' => 'Sin Financiamiento',
            'interno' => 'Interno',
            'externo' => 'Externo'
        ];

        return $tipos[$this->tipo_financiamiento] ?? $this->tipo_financiamiento;
    }

    /**
     * Format tipo fondo for display
     */
    public function getTipoFondoFormattedAttribute()
    {
        $tipos = [
            'propios' => 'Propios',
            'privado' => 'Privado',
            'gobierno' => 'Gobierno',
            'fondos_publicos' => 'Fondos Públicos',
            'instituciones_privadas_no_lucrativas' => 'Instituciones Privadas no Lucrativas',
            'exterior' => 'Exterior'
        ];

        return $tipos[$this->tipo_fondo] ?? $this->tipo_fondo;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'no_registro';
    }
}
