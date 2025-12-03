@extends('layouts/main')

@section('title', 'Revisar Proyecto - SIP')

@section('content')
<div class="revision-container">
    <div class="revision-header">
        <div class="header-left">
            <a href="{{ route('admin.dashboard') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Volver al panel
            </a>
            <h1>{{ $proyecto->titulo }}</h1>
            <div class="project-meta-info">
                <span class="meta-item">
                    <i class="fa-solid fa-hashtag"></i>
                    {{ $proyecto->no_registro }}
                </span>
                <span class="meta-item">
                    <i class="fa-solid fa-calendar"></i>
                    Solicitado el {{ $proyecto->created_at->format('d/m/Y') }}
                </span>
                <span class="badge-status {{ $proyecto->estado }}">
                    @if($proyecto->estado === 'en_revision')
                        <i class="fa-regular fa-hourglass-half"></i> En Revisión
                    @elseif($proyecto->estado === 'aprobado')
                        <i class="fa-solid fa-check-circle"></i> Aprobado
                    @elseif($proyecto->estado === 'rechazado')
                        <i class="fa-solid fa-times-circle"></i> Rechazado
                    @endif
                </span>
            </div>
        </div>

        @if($proyecto->estado === 'en_revision')
        <div class="header-actions">
            <button class="btn-approve" onclick="approveProject()">
                <i class="fa-solid fa-check"></i>
                Aprobar Proyecto
            </button>
            <button class="btn-reject" onclick="openRejectModal()">
                <i class="fa-solid fa-times"></i>
                Rechazar Proyecto
            </button>
        </div>
        @endif
    </div>

    <div class="revision-content">
        <!-- Investigador Principal -->
        <section class="revision-section">
            <h2><i class="fa-solid fa-user"></i> Investigador Principal</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Nombre Completo</label>
                    <p>{{ $proyecto->investigador->nombre }} {{ $proyecto->investigador->apellido_paterno }} {{ $proyecto->investigador->apellido_materno }}</p>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <p>{{ $proyecto->investigador->email }}</p>
                </div>
                <div class="info-item">
                    <label>Teléfono</label>
                    <p>{{ $proyecto->investigador->telefono ?? 'No especificado' }}</p>
                </div>
                <div class="info-item">
                    <label>Grado Académico</label>
                    <p>{{ $proyecto->investigador->grado_academico ?? 'No especificado' }}</p>
                </div>
            </div>
        </section>

        <!-- Información del Proyecto -->
        <section class="revision-section">
            <h2><i class="fa-solid fa-flask"></i> Información del Proyecto</h2>
            <div class="info-grid">
                <div class="info-item full-width">
                    <label>Descripción Breve</label>
                    <p>{{ $proyecto->descripcion_breve ?? 'No especificada' }}</p>
                </div>
                <div class="info-item">
                    <label>Tipo de Investigación</label>
                    <p>{{ $proyecto->tipo_investigacion_formatted }}</p>
                </div>
                <div class="info-item">
                    <label>Área UAN</label>
                    <p>{{ $proyecto->area_uan_formatted }}</p>
                </div>
                <div class="info-item">
                    <label>Área INEGI</label>
                    <p>{{ $proyecto->area_inegi_formatted }}</p>
                </div>
                <div class="info-item">
                    <label>Palabras Clave</label>
                    <p>{{ $proyecto->palabras_clave ?? 'No especificadas' }}</p>
                </div>
            </div>
        </section>

        <!-- Fechas y Duración -->
        <section class="revision-section">
            <h2><i class="fa-solid fa-calendar-days"></i> Cronograma</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Fecha de Inicio</label>
                    <p>{{ $proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('d/m/Y') : 'No especificada' }}</p>
                </div>
                <div class="info-item">
                    <label>Fecha de Término</label>
                    <p>{{ $proyecto->fecha_termino ? $proyecto->fecha_termino->format('d/m/Y') : 'No especificada' }}</p>
                </div>
                <div class="info-item">
                    <label>Duración</label>
                    <p>{{ $proyecto->duracion }} meses</p>
                </div>
            </div>
        </section>

        <!-- Financiamiento -->
        <section class="revision-section">
            <h2><i class="fa-solid fa-dollar-sign"></i> Financiamiento</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Tipo de Financiamiento</label>
                    <p>{{ $proyecto->tipo_financiamiento_formatted }}</p>
                </div>
                @if($proyecto->tipo_financiamiento !== 'sin_financiamiento')
                <div class="info-item">
                    <label>Fuente de Financiamiento</label>
                    <p>{{ $proyecto->fuente_financiamiento ?? 'No especificada' }}</p>
                </div>
                <div class="info-item">
                    <label>Tipo de Fondo</label>
                    <p>{{ $proyecto->tipo_fondo_formatted ?? 'No especificado' }}</p>
                </div>
                <div class="info-item">
                    <label>Monto Solicitado</label>
                    <p>{{ $proyecto->monto_aprobado ? '$' . number_format($proyecto->monto_aprobado, 2) . ' MXN' : 'No especificado' }}</p>
                </div>
                @if($proyecto->accion_transferencia)
                <div class="info-item full-width">
                    <label>Acción de Transferencia</label>
                    <p>{{ $proyecto->accion_transferencia }}</p>
                </div>
                @endif
                @endif
            </div>
        </section>

        <!-- Colaboradores -->
        @if($proyecto->colaboradores->count() > 0)
        <section class="revision-section">
            <h2><i class="fa-solid fa-users"></i> Colaboradores ({{ $proyecto->colaboradores->count() }})</h2>
            <div class="colaboradores-list">
                @foreach($proyecto->colaboradores as $colaborador)
                <div class="colaborador-card">
                    <div class="colaborador-info">
                        <h4>{{ $colaborador->nombre }} {{ $colaborador->apellido_paterno }} {{ $colaborador->apellido_materno }}</h4>
                        <p><strong>Email:</strong> {{ $colaborador->email }}</p>
                        <p><strong>Institución:</strong> {{ $colaborador->institucion ?? 'No especificada' }}</p>
                        @if($colaborador->grado_academico)
                        <p><strong>Grado:</strong> {{ $colaborador->grado_academico }}</p>
                        @endif
                    </div>
                    <div class="colaborador-rol">
                        <span class="rol-badge">{{ $colaborador->rol ?? 'Colaborador' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Productos Entregables -->
        <section class="revision-section">
            <h2><i class="fa-solid fa-box-archive"></i> Productos Esperados</h2>
            <div class="productos-grid">
                <div class="producto-item">
                    <i class="fa-solid fa-newspaper"></i>
                    <div>
                        <label>Artículos en Revista Indexada</label>
                        <span class="producto-count">{{ $proyecto->articulos_indexada ?? 0 }}</span>
                    </div>
                </div>
                <div class="producto-item">
                    <i class="fa-solid fa-file-alt"></i>
                    <div>
                        <label>Artículos en Revista Arbitrada</label>
                        <span class="producto-count">{{ $proyecto->articulos_arbitrada ?? 0 }}</span>
                    </div>
                </div>
                <div class="producto-item">
                    <i class="fa-solid fa-book"></i>
                    <div>
                        <label>Libros</label>
                        <span class="producto-count">{{ $proyecto->libros ?? 0 }}</span>
                    </div>
                </div>
                <div class="producto-item">
                    <i class="fa-solid fa-book-open"></i>
                    <div>
                        <label>Capítulos de Libro</label>
                        <span class="producto-count">{{ $proyecto->capitulo_libro ?? 0 }}</span>
                    </div>
                </div>
                <div class="producto-item">
                    <i class="fa-solid fa-users-line"></i>
                    <div>
                        <label>Memorias en Extenso</label>
                        <span class="producto-count">{{ $proyecto->memorias_congreso ?? 0 }}</span>
                    </div>
                </div>
                <div class="producto-item">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <div>
                        <label>Tesis</label>
                        <span class="producto-count">{{ $proyecto->tesis ?? 0 }}</span>
                    </div>
                </div>
                <div class="producto-item">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <div>
                        <label>Material Didáctico</label>
                        <span class="producto-count">{{ $proyecto->material_didactico ?? 0 }}</span>
                    </div>
                </div>
                @if($proyecto->otros_entregables)
                <div class="producto-item full-width">
                    <i class="fa-solid fa-ellipsis"></i>
                    <div>
                        <label>Otros Entregables</label>
                        <p>{{ $proyecto->otros_entregables }}</p>
                    </div>
                </div>
                @endif
            </div>
        </section>

        <!-- Resultados e Impactos -->
        @if($proyecto->resultados_esperados || $proyecto->usuario_especifico || $proyecto->impactos)
        <section class="revision-section">
            <h2><i class="fa-solid fa-bullseye"></i> Resultados e Impactos</h2>
            <div class="info-grid">
                @if($proyecto->resultados_esperados)
                <div class="info-item full-width">
                    <label>Resultados Esperados</label>
                    <p>{{ $proyecto->resultados_esperados }}</p>
                </div>
                @endif
                @if($proyecto->usuario_especifico)
                <div class="info-item full-width">
                    <label>Usuario Específico</label>
                    <p>{{ $proyecto->usuario_especifico }}</p>
                </div>
                @endif
                @if($proyecto->impactos && is_array($proyecto->impactos))
                <div class="info-item full-width">
                    <label>Impactos</label>
                    <ul class="impactos-list">
                        @foreach($proyecto->impactos as $impacto)
                            <li>{{ $impacto }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </section>
        @endif

        <!-- Archivo de Protocolo -->
        @if($proyecto->archivo_protocolo)
        <section class="revision-section">
            <h2><i class="fa-solid fa-file-pdf"></i> Protocolo de Investigación</h2>
            <div class="file-actions">
                <a href="{{ route('proyectos.view-protocolo', $proyecto) }}" target="_blank" class="btn-file-action">
                    <i class="fa-solid fa-eye"></i>
                    Ver Protocolo
                </a>
                <a href="{{ route('proyectos.download-protocolo', $proyecto) }}" class="btn-file-action">
                    <i class="fa-solid fa-download"></i>
                    Descargar Protocolo
                </a>
            </div>
        </section>
        @endif

        <!-- Comentarios de rechazo si existe -->
        @if($proyecto->estado === 'rechazado')
        <section class="revision-section rejection-section">
            <h2><i class="fa-solid fa-comment-slash"></i> Motivo de Rechazo</h2>
            @php
                $comentarioRechazo = $proyecto->comentarios()->where('tipo', 'rechazo')->latest()->first();
            @endphp
            @if($comentarioRechazo)
            <div class="rejection-content">
                <p>{!! nl2br(e($comentarioRechazo->contenido)) !!}</p>
                <div class="rejection-footer">
                    <span>Rechazado por: {{ $comentarioRechazo->personal->nombre ?? 'Administrador' }}</span>
                    <span>Fecha: {{ $comentarioRechazo->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
            @endif
        </section>
        @endif
    </div>
</div>

<!-- Modal de Rechazo -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Rechazar Proyecto</h2>
            <button class="modal-close" onclick="closeRejectModal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="rejectForm">
                <div class="form-group">
                    <label for="rejectComments">Motivo del Rechazo <span class="required">*</span></label>
                    <textarea id="rejectComments" rows="6" required placeholder="Explique detalladamente por qué se rechaza este proyecto. Este comentario será visible para el investigador."></textarea>
                    <small>Proporcione información clara y constructiva que ayude al investigador a mejorar su propuesta.</small>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeRejectModal()">Cancelar</button>
            <button class="btn-confirm-reject" onclick="confirmRejection()">Confirmar Rechazo</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
@vite(['resources/css/administrativo.css'])
<style>
    .revision-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .revision-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
        padding: 25px;
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
    }

    .header-left {
        flex: 1;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--color-text-secondary);
        text-decoration: none;
        margin-bottom: 15px;
        font-size: 14px;
        transition: color 0.3s;
    }

    .btn-back:hover {
        color: var(--color-primary);
    }

    .revision-header h1 {
        font-size: 28px;
        color: var(--color-text-primary);
        margin: 0 0 10px 0;
    }

    .project-meta-info {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        align-items: center;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--color-text-secondary);
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .revision-content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .revision-section {
        background: white;
        border-radius: var(--radius-lg);
        padding: 25px;
        box-shadow: var(--shadow-md);
    }

    .revision-section h2 {
        font-size: 20px;
        color: var(--color-text-primary);
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e5e7eb;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    .info-item label {
        font-weight: 600;
        color: var(--color-text-secondary);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item p {
        color: var(--color-text-primary);
        font-size: 15px;
        margin: 0;
        line-height: 1.6;
    }

    .colaboradores-list {
        display: grid;
        gap: 15px;
    }

    .colaborador-card {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 20px;
        background: var(--color-bg-light);
        border-radius: var(--radius-md);
        border-left: 4px solid var(--color-primary);
    }

    .colaborador-info h4 {
        margin: 0 0 10px 0;
        color: var(--color-text-primary);
        font-size: 16px;
    }

    .colaborador-info p {
        margin: 5px 0;
        font-size: 14px;
        color: var(--color-text-secondary);
    }

    .rol-badge {
        padding: 6px 12px;
        background: var(--color-primary);
        color: white;
        border-radius: var(--radius-full);
        font-size: 12px;
        font-weight: 600;
    }

    .productos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .producto-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: var(--color-bg-light);
        border-radius: var(--radius-md);
    }

    .producto-item.full-width {
        grid-column: 1 / -1;
    }

    .producto-item i {
        font-size: 24px;
        color: var(--color-primary);
    }

    .producto-item label {
        display: block;
        font-size: 13px;
        color: var(--color-text-secondary);
        margin-bottom: 3px;
    }

    .producto-count {
        font-size: 20px;
        font-weight: 700;
        color: var(--color-text-primary);
    }

    .impactos-list {
        margin: 10px 0 0 20px;
    }

    .impactos-list li {
        margin-bottom: 8px;
        color: var(--color-text-primary);
        line-height: 1.6;
    }

    .file-actions {
        display: flex;
        gap: 15px;
    }

    .btn-file-action {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: var(--color-secondary);
        color: white;
        border-radius: var(--radius-md);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-file-action:hover {
        background: var(--color-primary-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .rejection-section {
        border-left: 4px solid var(--color-rejected);
    }

    .rejection-content {
        padding: 20px;
        background: var(--color-rejected-light);
        border-radius: var(--radius-md);
    }

    .rejection-content p {
        color: var(--color-text-primary);
        line-height: 1.8;
        margin-bottom: 15px;
    }

    .rejection-footer {
        display: flex;
        justify-content: space-between;
        padding-top: 15px;
        border-top: 1px solid #ddd;
        font-size: 13px;
        color: var(--color-text-secondary);
    }

    @media (max-width: 768px) {
        .revision-header {
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .productos-grid {
            grid-template-columns: 1fr;
        }

        .file-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    // @ts-ignore - Blade template syntax
    const proyectoId = {{ $proyecto->id }};

    function openRejectModal() {
        document.getElementById('rejectModal').style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
        document.getElementById('rejectForm').reset();
    }

    function approveProject() {
        if (confirm('¿Está seguro que desea aprobar este proyecto?')) {
            fetch(`/admin/proyectos/${proyectoId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = '{{ route("admin.dashboard") }}';
                } else {
                    alert('Error: ' + (data.message || 'No se pudo aprobar el proyecto'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al aprobar el proyecto');
            });
        }
    }

    function confirmRejection() {
        const comments = document.getElementById('rejectComments').value.trim();

        if (!comments) {
            alert('Por favor proporcione el motivo del rechazo');
            return;
        }

        fetch(`/admin/proyectos/${proyectoId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ comments })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = '{{ route("admin.dashboard") }}';
            } else {
                alert('Error: ' + (data.message || 'No se pudo rechazar el proyecto'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al rechazar el proyecto');
        });
    }

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('rejectModal');
        if (e.target === modal) {
            closeRejectModal();
        }
    });
</script>
@endpush
