<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Secretaría de Investigación y Posgrado')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    @vite(['resources/css/main.css'])
    @stack('styles')
</head>
<body>
    <!-- Hamburger Menu Button -->
    <button class="menu-toggle" id="menuToggle">
        <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="logo">
                <img src="{{ asset('rsc/img/logo_sip.png') }}" alt="Logo SIP">
            </div>

            <div class="profile">
                <img src="{{ Auth::guard('investigador')->check() ? Auth::guard('investigador')->user()->profile_image : Auth::guard('personal')->user()->profile_image }}" alt="Usuario" class="profile-img">
                <h3>
                    @if(Auth::guard('investigador')->check())
                        @php
                            $investigador = Auth::guard('investigador')->user();
                            $prefijo = '';
                            switch($investigador->nivel_academico) {
                                case 'licenciatura':
                                    $prefijo = 'Lic.';
                                    break;
                                case 'maestria':
                                    $prefijo = 'M.';
                                    break;
                                case 'doctorado':
                                    $prefijo = 'Dr.';
                                    break;
                                default:
                                    $prefijo = '';
                            }
                        @endphp
                        {{ $prefijo }} {{ $investigador->nombre }}
                    @else
                        {{ Auth::guard('personal')->user()->nombre ?? 'Usuario' }}
                    @endif
                </h3>
                <p>{{ Auth::guard('investigador')->check() ? 'Investigador' : 'Personal administrativo' }}</p>
            </div>

            <nav class="menu">
                <a href="{{ Auth::guard('investigador')->check() ? route('user') : route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('user', 'admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-open"></i>
                    {{ Auth::guard('investigador')->check() ? 'Proyectos' : 'Panel' }}
                </a>
                <a href="#" class="menu-item disabled" disabled>
                    <i class="fa-solid fa-file-lines"></i>
                    Reportes
                </a>
                <a href="{{ Auth::guard('investigador')->check() ? route('investigador.config') : route('admin.config') }}" class="menu-item {{ request()->routeIs('investigador.config', 'admin.config') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i>
                    Configuración
                </a>
                <a href="{{ route('logout') }}" class="menu-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Cerrar sesión
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </a>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script>
        // Hamburger menu functionality
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const menuOverlay = document.getElementById('menuOverlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            menuOverlay.classList.toggle('active');

            // Change icon
            const icon = menuToggle.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }

        menuToggle.addEventListener('click', toggleMenu);
        menuOverlay.addEventListener('click', toggleMenu);

        // Close menu when clicking on a menu item (optional)
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleMenu();
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
