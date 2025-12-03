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
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
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
                <form class="login-form" method="POST" action="{{ route('auth.login') }}">
                    @csrf
                    <p class="instruction">Ingresa tus datos para iniciar sesión.</p>

                    @if (session('status'))
                        <div class="alert alert-success" id="success-alert">
                            <i class="fas fa-check-circle alert-icon"></i>
                            <div class="alert-content">
                                <p>{{ session('status') }}</p>
                            </div>
                            <button type="button" class="alert-close" onclick="this.parentElement.style.display='none'">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="email">Email o Núm de Empleado <span class="required">*</span></label>
                        <div class="input-wrapper {{ $errors->has('email') ? 'error' : '' }}">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="email" name="email" required value="{{ old('email') }}" autocomplete="email">
                        </div>
                        @if ($errors->has('email'))
                            <div class="field-error">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña <span class="required">*</span></label>
                        <div class="input-wrapper {{ $errors->has('password') ? 'error' : '' }}">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" required autocomplete="current-password">
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                        @if ($errors->has('password'))
                            <div class="field-error">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-footer">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
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
        // Debug: Verificar que el formulario se envía
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            console.log('Formulario enviado');
            console.log('Email:', document.querySelector('#email').value);
        });

        document.querySelector('.toggle-password').addEventListener('click', function() {
            const password = document.querySelector('#password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => alert.style.display = 'none', 300);
                }
            });
        }, 5000);

        // Remove error styling when user starts typing
        document.querySelectorAll('.input-wrapper.error input').forEach(input => {
            input.addEventListener('input', function() {
                this.closest('.input-wrapper').classList.remove('error');
                const fieldError = this.closest('.form-group').querySelector('.field-error');
                if (fieldError) fieldError.style.display = 'none';
            });
        });
    </script>
</body>
</html>
