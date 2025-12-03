@extends('layouts/main')

@section('title', 'Panel Administrativo - SIP')

@section('content')
<header class="header">
    <h1>Panel Administrativo</h1>
    <div class="header-actions">
        <div class="stats-summary">
            <div class="stat-item">
                <span class="stat-number">{{ $stats['pending'] }}</span>
                <span class="stat-label">Pendientes</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $stats['approved'] }}</span>
                <span class="stat-label">Aprobados</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">{{ $stats['rejected'] }}</span>
                <span class="stat-label">Rechazados</span>
            </div>
        </div>
        <button class="btn-notification">
            <i class="fa-solid fa-bell"></i>
            <span class="badge">{{ $stats['pending'] }}</span>
        </button>
        <span class="notifications-text">Notificaciones</span>
    </div>
</header>

<div class="content">
    <div class="actions-row">
        <div class="filter-container">
            <button class="filter-btn {{ $filter === 'all' ? 'active' : '' }}" data-filter="all">
                <i class="fa-solid fa-boxes-stacked"></i>
                Todos
            </button>
        </div>

        <div class="filter-container">
            <button class="filter-btn pending {{ $filter === 'pending' ? 'active' : '' }}" data-filter="pending">
                <i class="fa-regular fa-hourglass-half"></i>
                Pendientes
                @if($stats['pending'] > 0)
                <span class="filter-badge">{{ $stats['pending'] }}</span>
                @endif
            </button>
        </div>

        <div class="filter-container">
            <button class="filter-btn {{ $filter === 'approved' ? 'active' : '' }}" data-filter="approved">
                <i class="fa-regular fa-circle-check"></i>
                Aprobados
            </button>
        </div>

        <div class="filter-container">
            <button class="filter-btn {{ $filter === 'revision' ? 'active' : '' }}" data-filter="revision">
                <i class="fa-regular fa-alarm-clock"></i>
                Con observaciones
            </button>
        </div>

        <div class="filter-container">
            <button class="filter-btn {{ $filter === 'rejected' ? 'active' : '' }}" data-filter="rejected">
                <i class="fa-regular fa-circle-xmark"></i>
                Rechazados
            </button>
        </div>

        <div class="filter-container">
            <button class="btn-export" onclick="window.print()">
                <i class="fa-solid fa-download"></i> Exportar
            </button>
        </div>
    </div>

    <div class="top-bar">
        <div class="search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="search-input" placeholder="Buscar proyecto por título, investigador o palabras clave" value="{{ $search }}">
        </div>
        <div class="sort-controls">
            <select id="sort-select">
                <option value="date-desc" {{ $sort === 'date-desc' ? 'selected' : '' }}>Más recientes primero</option>
                <option value="date-asc" {{ $sort === 'date-asc' ? 'selected' : '' }}>Más antiguos primero</option>
                <option value="title-asc" {{ $sort === 'title-asc' ? 'selected' : '' }}>Título A-Z</option>
                <option value="title-desc" {{ $sort === 'title-desc' ? 'selected' : '' }}>Título Z-A</option>
                <option value="status" {{ $sort === 'status' ? 'selected' : '' }}>Por estado</option>
            </select>
        </div>
    </div>

    <!-- Vista de proyectos en cards -->
    <div class="projects-list" id="projects-list">
        @forelse($proyectos as $proyecto)
        @php
            $statusMap = [
                'en_revision' => 'pending',
                'aprobado' => 'approved',
                'rechazado' => 'rejected',
                'con_observaciones' => 'revision'
            ];
            $dataStatus = $statusMap[$proyecto->estado] ?? 'pending';

            $statusLabels = [
                'en_revision' => 'Pendiente',
                'aprobado' => 'Aprobado',
                'rechazado' => 'Rechazado',
                'con_observaciones' => 'Con observaciones'
            ];
            $statusLabel = $statusLabels[$proyecto->estado] ?? $proyecto->estado;

            $statusIcons = [
                'en_revision' => 'fa-hourglass-half',
                'aprobado' => 'fa-check-circle',
                'rechazado' => 'fa-times-circle',
                'con_observaciones' => 'fa-exclamation-triangle'
            ];
            $statusIcon = $statusIcons[$proyecto->estado] ?? 'fa-clock';

            // Calculate duration in months
            $duracion = $proyecto->fecha_inicio && $proyecto->fecha_termino
                ? \Carbon\Carbon::parse($proyecto->fecha_inicio)->diffInMonths(\Carbon\Carbon::parse($proyecto->fecha_termino))
                : 'N/A';
        @endphp
        <div class="admin-project-card" data-status="{{ $dataStatus }}" data-date="{{ $proyecto->created_at->format('Y-m-d') }}" data-title="{{ $proyecto->titulo }}">
            <div class="project-header">
                <div class="project-info">
                    <h3>{{ $proyecto->titulo }}</h3>
                    <div class="project-meta">
                        <span class="investigator">
                            <i class="fa-solid fa-user"></i>
                            {{ $proyecto->investigador ? $proyecto->investigador->nombre . ' ' . $proyecto->investigador->apellido_paterno : 'Sin investigador' }}
                        </span>
                        <span class="project-date">
                            <i class="fa-solid fa-calendar"></i>
                            Solicitado el {{ $proyecto->created_at->format('d/m/Y') }}
                        </span>
                        <span class="project-type">
                            <i class="fa-solid fa-flask"></i>
                            {{ $proyecto->tipo_investigacion_formatted ?? 'N/A' }}
                        </span>
                    </div>
                </div>
                <div class="project-actions">
                    <span class="badge-status {{ $dataStatus }}">
                        <i class="fa-regular {{ $statusIcon }}"></i>
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>

            <div class="project-summary">
                <p class="project-description">
                    {{ $proyecto->descripcion_breve ?? 'Sin descripción disponible.' }}
                </p>
                <div class="project-details">
                    <div class="detail-item">
                        <span class="detail-label">Duración:</span>
                        <span class="detail-value">{{ $duracion }} meses</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Presupuesto:</span>
                        <span class="detail-value">{{ $proyecto->monto_aprobado ? '$' . number_format($proyecto->monto_aprobado, 2) . ' MXN' : 'Por definir' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Colaboradores:</span>
                        <span class="detail-value">{{ $proyecto->colaboradores->count() }} investigadores</span>
                    </div>
                </div>

                @if($proyecto->estado === 'rechazado')
                    @php
                        $lastComment = $proyecto->comentarios()->where('tipo', 'rechazo')->latest()->first();
                    @endphp
                    @if($lastComment)
                    <div class="rejection-reason">
                        <strong>Motivo de rechazo:</strong> {{ strip_tags($lastComment->contenido) }}
                    </div>
                    @endif
                @endif
            </div>

            <div class="project-footer">
                <div class="action-buttons">
                    <a href="{{ route('admin.projects.revision', $proyecto) }}" class="btn-view">
                        <i class="fa-solid fa-eye"></i>
                        Revisar Proyecto
                    </a>
                </div>

                @if($proyecto->estado === 'aprobado' && $proyecto->updated_at)
                <div class="approved-info">
                    <span class="approved-by">Aprobado</span>
                    <span class="approval-date">{{ $proyecto->updated_at->format('d/m/Y - H:i') }}</span>
                </div>
                @elseif($proyecto->estado === 'rechazado' && $proyecto->updated_at)
                <div class="rejection-info">
                    <span class="rejected-by">Rechazado</span>
                    <span class="rejection-date">{{ $proyecto->updated_at->format('d/m/Y - H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 40px;">
            <i class="fa-solid fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
            <p style="color: #999; font-size: 18px;">No se encontraron proyectos</p>
        </div>
        @endforelse
    </div>

    <!-- Laravel Pagination -->
    @if($proyectos->hasPages())
    <div class="pagination" style="display: flex;">
        {{ $proyectos->links() }}
    </div>
    @endif
</div>

@endsection

@push('styles')
@vite(['resources/css/administrativo.css'])
@endpush

@push('scripts')
<script>
    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Filtros - cambiar URL con parámetros
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.dataset.filter;
                const url = new URL(window.location.href);
                url.searchParams.set('filter', filter);
                url.searchParams.delete('page'); // Reset to first page
                window.location.href = url.toString();
            });
        });

        // Búsqueda con debounce
        const searchInput = document.getElementById('search-input');
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const url = new URL(window.location.href);
                url.searchParams.set('search', this.value);
                url.searchParams.delete('page'); // Reset to first page
                window.location.href = url.toString();
            }, 500);
        });

        // Ordenamiento
        const sortSelect = document.getElementById('sort-select');
        sortSelect.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', this.value);
            window.location.href = url.toString();
        });
    });
</script>
@endpush
