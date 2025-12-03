{{-- filepath: c:\laragon\www\ProyectoSIP\resources\views\proyecto.blade.php --}}
@extends('layouts.main')

@section('title', $proyecto->titulo . ' - SIP')

@push('styles')
    @vite(['resources/css/proyecto.css'])
@endpush

@section('content')
<div class="project-detail-container">
    <!-- Header -->
    <div class="project-detail-header">
        <div class="header-top">
            <a href="{{ route('user') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                Volver a proyectos
            </a>
            <div class="project-status">
                <span class="badge-status {{ $proyecto->estado }}">
                    @switch($proyecto->estado)
                        @case('aprobado')
                            <i class="fa-solid fa-check-circle"></i> Aprobado
                            @break
                        @case('en_revision')
                            <i class="fa-solid fa-clock"></i> En revisión
                            @break
                        @case('rechazado')
                            <i class="fa-solid fa-times-circle"></i> Rechazado
                            @break
                        @case('con_observaciones')
                            <i class="fa-solid fa-exclamation-circle"></i> Con observaciones
                            @break
                        @case('finalizado')
                            <i class="fa-solid fa-flag-checkered"></i> Finalizado
                            @break
                    @endswitch
                </span>
            </div>
        </div>

        <h1 class="project-title">{{ $proyecto->titulo }}</h1>

        <div class="project-meta">
            <div class="meta-item">
                <i class="fa-solid fa-hashtag"></i>
                <span>No. Registro: <strong>{{ $proyecto->no_registro }}</strong></span>
            </div>
            <div class="meta-item">
                <i class="fa-solid fa-user"></i>
                <span>Investigador principal: <strong>{{ $proyecto->investigador->nombre }}</strong></span>
            </div>
            <div class="meta-item">
                <i class="fa-solid fa-calendar"></i>
                <span>Duración: <strong>{{ $proyecto->duracion }} meses</strong></span>
            </div>
        </div>
    </div>

    <!-- Main content grid -->
    <div class="project-content-grid">
        <!-- Left column -->
        <div class="project-main-content">
            <!-- Información general -->
            <div class="detail-card">
                <h2 class="card-title">
                    <i class="fa-solid fa-info-circle"></i>
                    Información General
                </h2>
                <div class="card-content">
                    <div class="info-group">
                        <label>Descripción breve</label>
                        <p>{{ $proyecto->descripcion_breve }}</p>
                    </div>

                    <div class="info-row">
                        <div class="info-group">
                            <label>Fecha de inicio</label>
                            <p>{{ $proyecto->fecha_inicio->format('d/m/Y') }}</p>
                        </div>
                        <div class="info-group">
                            <label>Fecha de término</label>
                            <p>{{ $proyecto->fecha_termino->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-group">
                            <label>Área UAN</label>
                            <p>{{ $proyecto->area_uan_formatted }}</p>
                        </div>
                        <div class="info-group">
                            <label>Área INEGI</label>
                            <p>{{ $proyecto->area_inegi_formatted }}</p>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-group">
                            <label>Tipo de investigación</label>
                            <p>{{ $proyecto->tipo_investigacion_formatted }}</p>
                        </div>
                        <div class="info-group">
                            <label>Tipo de financiamiento</label>
                            <p>{{ $proyecto->tipo_financiamiento_formatted }}</p>
                        </div>
                    </div>

                    @if($proyecto->tipo_financiamiento !== 'sin_financiamiento')
                        <div class="info-row">
                            @if($proyecto->tipo_financiamiento === 'externo' && $proyecto->fuente_financiamiento)
                                <div class="info-group">
                                    <label>Fuente de financiamiento</label>
                                    <p>{{ $proyecto->fuente_financiamiento }}</p>
                                </div>
                            @endif
                            @if($proyecto->tipo_fondo)
                                <div class="info-group">
                                    <label>Tipo de fondo</label>
                                    <p>{{ $proyecto->tipo_fondo_formatted }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Productos entregables -->
            <div class="detail-card">
                <h2 class="card-title">
                    <i class="fa-solid fa-box-open"></i>
                    Productos Entregables
                </h2>
                <div class="card-content">
                    @if($proyecto->productos_entregables && count($proyecto->productos_entregables) > 0)
                        <ul class="productos-list">
                            @foreach($proyecto->productos_entregables as $producto)
                                <li>
                                    <i class="fa-solid fa-check"></i>
                                    <span class="producto-text">{{ $producto }}</span>
                                    <button class="btn-entregar" title="Entregar producto">
                                        <i class="fa-solid fa-upload"></i>
                                        Entregar
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="empty-message">No se han especificado productos entregables.</p>
                    @endif
                </div>
            </div>

            <!-- Colaboradores -->
            <div class="detail-card">
                <h2 class="card-title">
                    <i class="fa-solid fa-users"></i>
                    Equipo de Trabajo
                </h2>
                <div class="card-content">
                    @if($proyecto->colaboradores->count() > 0)
                        <!-- Profesores -->
                        @php
                            $profesores = $proyecto->colaboradores->where('tipo_colaborador', 'profesor');
                        @endphp
                        @if($profesores->count() > 0)
                            <div class="colaboradores-section">
                                <h3 class="section-subtitle">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                    Profesores Colaboradores
                                </h3>
                                <div class="colaboradores-grid">
                                    @foreach($profesores as $profesor)
                                        <div class="colaborador-card">
                                            <div class="colaborador-icon">
                                                <i class="fa-solid fa-user-tie"></i>
                                            </div>
                                            <div class="colaborador-info">
                                                <h4>{{ $profesor->nombre_completo }}</h4>
                                                <p>{{ $profesor->actividad }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Estudiantes -->
                        @php
                            $estudiantes = $proyecto->colaboradores->where('tipo_colaborador', 'estudiante');
                        @endphp
                        @if($estudiantes->count() > 0)
                            <div class="colaboradores-section">
                                <h3 class="section-subtitle">
                                    <i class="fa-solid fa-user-graduate"></i>
                                    Estudiantes Colaboradores
                                </h3>
                                <div class="colaboradores-grid">
                                    @foreach($estudiantes as $estudiante)
                                        <div class="colaborador-card">
                                            <div class="colaborador-icon estudiante">
                                                <i class="fa-solid fa-graduation-cap"></i>
                                            </div>
                                            <div class="colaborador-info">
                                                <h4>{{ $estudiante->nombre_completo }}</h4>
                                                <p>{{ $estudiante->actividad }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <p class="empty-message">
                            <i class="fa-solid fa-user-slash"></i>
                            No hay colaboradores registrados en este proyecto.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right column -->
        <div class="project-sidebar">
            <!-- Documento -->
            <div class="detail-card">
                <h2 class="card-title">
                    <i class="fa-solid fa-file-pdf"></i>
                    Documento
                </h2>
                <div class="card-content">
                    @if($proyecto->archivo_protocolo)
                        <a href="{{ Storage::url($proyecto->archivo_protocolo) }}" class="btn-document" target="_blank">
                            <i class="fa-solid fa-download"></i>
                            Descargar protocolo
                        </a>
                    @else
                        <p class="empty-message">No hay documento disponible</p>
                    @endif
                </div>
            </div>

            <!-- Comentarios -->
            @if($proyecto->comentarios->count() > 0)
                <div class="detail-card">
                    <h2 class="card-title">
                        <i class="fa-solid fa-comments"></i>
                        Comentarios ({{ $proyecto->comentarios->count() }})
                    </h2>
                    <div class="card-content">
                        <div class="comentarios-list">
                            @foreach($proyecto->comentarios as $comentario)
                                <div class="comentario-item">
                                    <div class="comentario-header">
                                        <strong>{{ $comentario->personal->nombre }}</strong>
                                        <span class="comentario-date">{{ $comentario->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="comentario-text">{{ $comentario->contenido }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="detail-card">
                <h2 class="card-title">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Historial
                </h2>
                <div class="card-content">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <span class="timeline-date">{{ $proyecto->created_at->format('d/m/Y H:i') }}</span>
                                <p>Proyecto creado</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <span class="timeline-date">{{ $proyecto->updated_at->format('d/m/Y H:i') }}</span>
                                <p>Última actualización</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
