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
            <button class="filter-btn" data-filter="approved">
                <i class="fa-regular fa-circle-check"></i>
                Aprobados
            </button>
        </div>

        <div class="filter-container">
            <button class="filter-btn" data-filter="revision">
                <i class="fa-regular fa-alarm-clock"></i>
                En revisión
            </button>
        </div>

        <div class="filter-container">
            <button class="filter-btn" data-filter="rejected">
                <i class="fa-regular fa-circle-xmark"></i>
                Rechazados
            </button>
        </div>

        <div class="filter-container">
            <button class="btn-primary">
                <i class="fa-solid fa-plus"></i> Solicitar nuevo
            </button>
        </div>
    </div>

    <div class="top-bar">
        <div class="search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="search-input" placeholder="Buscar un proyecto en particular">
        </div>
    </div>

    <div class="projects-list">
        <div class="project-card" data-status="approved">
            <div class="project-header">
                <h3>Sistema de gestión de inventarios</h3>
                <span class="project-date">Se registró el 15/10/2024</span>
            </div>
            <p class="project-description">
                Desarrollo de un sistema web para la gestión eficiente de inventarios en pequeñas y medianas empresas, con módulos de control de stock y reportes.
            </p>
            <div class="project-footer">
                <span class="badge-status approved">
                    Aprobado
                </span>
                <a href="#" class="btn-details">
                    Detalles
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <div class="project-card" data-status="revision">
            <div class="project-header">
                <h3>Análisis de algoritmos de machine learning</h3>
                <span class="project-date">Se registró el 03/11/2024</span>
            </div>
            <p class="project-description">
                Investigación comparativa sobre la eficiencia de diferentes algoritmos de aprendizaje automático aplicados a la clasificación de datos médicos.
            </p>
            <div class="project-footer">
                <span class="badge-status revision">
                    En revisión
                </span>
                <a href="#" class="btn-details">
                    Detalles
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <div class="project-card" data-status="rejected">
            <div class="project-header">
                <h3>Aplicación móvil para turismo</h3>
                <span class="project-date">Se registró el 28/09/2024</span>
            </div>
            <p class="project-description">
                Aplicación móvil multiplataforma para promover el turismo local con geolocalización y recomendaciones personalizadas.
            </p>
            <div class="project-footer">
                <span class="badge-status rejected">
                    Rechazado
                </span>
                <a href="#" class="btn-details">
                    Detalles
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <div class="project-card" data-status="approved">
            <div class="project-header">
                <h3>Plataforma educativa virtual</h3>
                <span class="project-date">Se registró el 12/10/2024</span>
            </div>
            <p class="project-description">
                Desarrollo de una plataforma LMS para la gestión de cursos en línea, con seguimiento de estudiantes y evaluaciones automatizadas.
            </p>
            <div class="project-footer">
                <span class="badge-status approved">
                    Aprobado
                </span>
                <a href="#" class="btn-details">
                    Detalles
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>
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
