@extends('layouts/main')

@section('title', 'Mis Proyectos - SIP')

@section('content')
<header class="header">
    <h1>Mis proyectos</h1>
    <div class="header-actions">
        <button class="btn-notification">
            <i class="fa-solid fa-bell"></i>
            <span class="badge">3</span>
        </button>
        <span class="notifications-text">Notificaciones</span>
    </div>
</header>

<div class="content">

    <div class="actions-row">
        <div class="filter-container">
            <button class="filter-btn active" data-filter="all">
                <i class="fa-solid fa-boxes-stacked"></i>
                Todos
            </button>
        </div>

        <div class="filter-container">
            <button class="filter-btn" data-filter="aprobado">
                <i class="fa-regular fa-circle-check"></i>
                Aprobados
            </button>
        </div>

        <div class="filter-container">
            <button class="filter-btn" data-filter="en_revision">
                <i class="fa-regular fa-alarm-clock"></i>
                En revisión
            </button>
        </div>

        <div class="filter-container">
            <button class="filter-btn" data-filter="rechazado">
                <i class="fa-regular fa-circle-xmark"></i>
                Rechazados
            </button>
        </div>

        <div class="filter-container">
            <a href="{{ route('proyectos.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Solicitar nuevo
            </a>
        </div>
    </div>

    <div class="top-bar">
        <div class="search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="search-input" placeholder="Buscar un proyecto en particular">
        </div>
    </div>

    <div class="projects-list">
        @forelse($proyectos as $proyecto)
            <div class="project-card" data-status="{{ $proyecto->estado }}">
                <div class="project-header">
                    <h3>{{ $proyecto->titulo }}</h3>
                    <span class="project-date">Se registró el <i class="fa-regular fa-calendar-days"></i> {{ $proyecto->created_at->format('d/m/Y') }}</span>
                </div>
                <p class="project-description">
                    {{ $proyecto->descripcion_breve }}
                </p>
                <div class="project-footer">
                    <span class="badge-status {{ $proyecto->estado === 'aprobado' ? 'approved' : ($proyecto->estado === 'en_revision' ? 'revision' : 'rejected') }}">
                        @switch($proyecto->estado)
                            @case('aprobado')
                                Aprobado
                                @break
                            @case('en_revision')
                                En revisión
                                @break
                            @case('rechazado')
                                Rechazado
                                @break
                            @case('finalizado')
                                Finalizado
                                @break
                            @default
                                {{ ucfirst(str_replace('_', ' ', $proyecto->estado)) }}
                        @endswitch
                    </span>
                    <a href="{{ route('proyectos.show', $proyecto->no_registro) }}" class="btn-details">
                        Detalles
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="no-projects">
                <p>No tienes proyectos registrados aún.</p>
                <a href="{{ route('proyectos.create') }}" class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Crear mi primer proyecto
                </a>
            </div>
        @endforelse
    </div>

    <div class="pagination" id="pagination" style="display: none;">
        <button class="btn-prev" id="prev-btn" disabled>Anterior</button>
        <div id="page-numbers"></div>
        <button class="btn-next" id="next-btn" disabled>Siguiente</button>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const PROJECTS_PER_PAGE = 3;
    let currentPage = 1;
    let filteredCards = [];
    let allCards = [];

    function updatePagination() {
        const totalPages = Math.ceil(filteredCards.length / PROJECTS_PER_PAGE);
        const paginationDiv = document.getElementById('pagination');
        const pageNumbers = document.getElementById('page-numbers');
        const nextBtn = document.getElementById('next-btn');
        const prevBtn = document.getElementById('prev-btn');

        // Ocultar todos los proyectos primero
        allCards.forEach(card => {
            card.style.display = 'none';
        });

        // Mostrar/ocultar paginación
        if (totalPages > 1) {
            paginationDiv.style.display = 'flex';
        } else {
            paginationDiv.style.display = 'none';
        }

        // Crear botones de página
        pageNumbers.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = i === currentPage ? 'page-btn active' : 'page-btn';
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => {
                currentPage = i;
                updatePagination();
            });
            pageNumbers.appendChild(pageBtn);
        }

        // Actualizar botón siguiente
        nextBtn.disabled = currentPage === totalPages || totalPages === 0;
        // Actualizar botón anterior
        prevBtn.disabled = currentPage === 1 || totalPages === 0;

        // Ocultar/mostrar botón anterior
        if (currentPage === 1) {
            prevBtn.style.display = 'none';
        } else {
            prevBtn.style.display = 'block';
        }

        // Mostrar solo los proyectos de la página actual
        const startIndex = (currentPage - 1) * PROJECTS_PER_PAGE;
        const endIndex = startIndex + PROJECTS_PER_PAGE;

        filteredCards.forEach((card, index) => {
            if (index >= startIndex && index < endIndex) {
                card.style.display = 'block';
            }
        });
    }

    function filterProjects(filter, searchTerm = '') {
        filteredCards = [];

        allCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('.project-description').textContent.toLowerCase();

            const matchesFilter = filter === 'all' || card.dataset.status === filter;
            const matchesSearch = searchTerm === '' || title.includes(searchTerm) || description.includes(searchTerm);

            if (matchesFilter && matchesSearch) {
                filteredCards.push(card);
            }
        });

        currentPage = 1;
        updatePagination();
    }

    // Filtros
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            filterProjects(filter, searchTerm);
        });
    });

    // Búsqueda
    const searchInput = document.getElementById('search-input');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
        filterProjects(activeFilter, searchTerm);
    });

    // Paginación - Botón anterior
    document.getElementById('prev-btn').addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    });

    // Paginación - Botón siguiente
    document.getElementById('next-btn').addEventListener('click', function() {
        const totalPages = Math.ceil(filteredCards.length / PROJECTS_PER_PAGE);
        if (currentPage < totalPages) {
            currentPage++;
            updatePagination();
        }
    });

    // Inicializar
    allCards = Array.from(document.querySelectorAll('.project-card'));
    filterProjects('all');
</script>
@endpush
