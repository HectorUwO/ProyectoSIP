<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PIIGPI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/login.css'])
    @stack('styles')
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="background-overlay"></div>
            <div class="content-wrapper">
                <div class="logo-container">
                    <img src="{{ asset('rsc/img/logo.png') }}" alt="Logo PIIGPI" class="main-logo">
                </div>
                <h1 class="title">Plataforma Institucional para la Gestión de Proyectos de Investigación</h1>
                <p class="subtitle">Secretaría de Investigación y Posgrado</p>
                <p class="subtitle">Dirección de Fortalecimiento a la Investigación</p>
            </div>
            <div class="footer-logos">
                <img src="{{ asset('rsc/img/logo_uan.png') }}" alt="Universidad Autónoma de Nayarit" class="university-logo">
            </div>
        </div>

        <div class="right-panel">
            <div class="saludo">
                <h2 class="welcome">¡Hola!</h2>
            </div>
            <div class="login-box">
                <form class="login-form" method="POST" action="">
                    @csrf
                    <p class="instruction">Ingresa tus datos para iniciar sesión.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="email">Email o Núm de Empleado <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="email" name="email" required value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" required>
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>

                    <div class="form-footer">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember">
                            <span>No cerrar sesión</span>
                        </label>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-sign-in-alt"></i>
                            Ingresar
                        </button>
                    </div>
                </form>
            </div>
            <a href="" class="forgot-password">¿Olvidaste tu contraseña?</a>
        </div>
    </div>

    <script>
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const password = document.querySelector('#password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
