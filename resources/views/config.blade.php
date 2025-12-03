@extends('layouts/main')

@section('title', 'Configuración - SIP')

@section('content')
<header class="header">
    <h1>Configuración</h1>
    <div class="header-actions">
        <button class="btn-notification">
            <i class="fa-solid fa-bell"></i>
            <span class="badge">3</span>
        </button>
        <span class="notifications-text">Notificaciones</span>
    </div>
</header>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="config-tabs">
        <button class="tab-btn active" data-tab="profile">
            <i class="fa-solid fa-user"></i>
            Perfil
        </button>
        <button class="tab-btn" data-tab="password">
            <i class="fa-solid fa-lock"></i>
            Cambiar Contraseña
        </button>
    </div>

    <!-- Profile Tab -->
    <div id="profile-tab" class="tab-content active">
        <div class="config-card">
            <h2>Información del Perfil</h2>

            <form action="{{ Auth::guard('investigador')->check() ? route('investigador.config.update-profile') : route('admin.config.update-profile') }}" method="POST" enctype="multipart/form-data" class="config-form">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="profile-photo-section">
                        <div class="current-photo">
                            @php
                                $user = Auth::guard('investigador')->user() ?? Auth::guard('personal')->user();
                                $photoPath = $user->foto ? asset('storage/' . $user->foto) : asset('rsc/img/profile.png');
                            @endphp
                            <img src="{{ $photoPath }}" alt="Foto actual" id="current-photo-img">
                        </div>
                        <div class="photo-controls">
                            <input type="file" id="foto" name="foto" accept="image/*" style="display: none;">
                            <button type="button" class="btn-secondary" onclick="document.getElementById('foto').click();">
                                <i class="fa-solid fa-camera"></i>
                                Cambiar Foto
                            </button>
                            <span class="file-info">JPG, PNG. Max 2MB</span>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $user->nombre) }}" required>
                        @error('nombre')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="clave_empleado">Clave de Empleado</label>
                        <input type="text" id="clave_empleado" name="clave_empleado" value="{{ old('clave_empleado', $user->clave_empleado) }}" required>
                        @error('clave_empleado')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @if(Auth::guard('investigador')->check())
                    <div class="form-row">
                        <div class="form-group">
                            <label for="programa_academico">Programa Académico</label>
                            <input type="text" id="programa_academico" name="programa_academico" value="{{ old('programa_academico', $user->programa_academico) }}">
                        </div>
                        <div class="form-group">
                            <label for="nivel_academico">Nivel Académico</label>
                            @php
                                $nivelActual = old('nivel_academico', $user->nivel_academico ?? '');
                            @endphp
                            <select id="nivel_academico" name="nivel_academico">
                                @if(empty($nivelActual))
                                    <option value="" selected>Seleccionar...</option>
                                @endif
                                <option value="Licenciatura" {{ strtolower($nivelActual) == strtolower('Licenciatura') ? 'selected="selected"' : '' }}>Licenciatura</option>
                                <option value="Maestría" {{ strtolower($nivelActual) == strtolower('Maestria') ? 'selected="selected"' : '' }}>Maestría</option>
                                <option value="Doctorado" {{ strtolower($nivelActual) == strtolower('Doctorado') ? 'selected="selected"' : '' }}>Doctorado</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $user->telefono) }}">
                        </div>
                        <div class="form-group">
                            <label for="cuerpo_academico">Cuerpo Académico</label>
                            <input type="text" id="cuerpo_academico" name="cuerpo_academico" value="{{ old('cuerpo_academico', $user->cuerpo_academico) }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="sni" value="1" {{ old('sni', $user->sni) ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                Miembro del SNI
                            </label>
                        </div>
                        <div class="form-group checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="perfil_prodep" value="1" {{ old('perfil_prodep', $user->perfil_prodep) ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                Perfil PRODEP
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="grado_consolidacion_ca">Grado de Consolidación CA</label>
                            @php
                                $gradoActual = old('grado_consolidacion_ca', $user->grado_consolidacion_ca ?? '');
                            @endphp
                            <select id="grado_consolidacion_ca" name="grado_consolidacion_ca">
                                @if(empty($gradoActual))
                                    <option value="" selected>Seleccionar...</option>
                                @endif
                                <option value="en_formacion" {{ $gradoActual == 'en_formacion' ? 'selected="selected"' : '' }}>En formación</option>
                                <option value="en_consolidacion" {{ $gradoActual == 'en_consolidacion' ? 'selected="selected"' : '' }}>En consolidación</option>
                                <option value="consolidado" {{ $gradoActual == 'consolidado' ? 'selected="selected"' : '' }}>Consolidado</option>
                            </select>
                        </div>
                    </div>
                @endif

                @if(Auth::guard('personal')->check())
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cargo">Cargo</label>
                            <input type="text" id="cargo" name="cargo" value="{{ old('cargo', $user->cargo) }}">
                        </div>
                    </div>
                @endif

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-save"></i>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Tab -->
    <div id="password-tab" class="tab-content">
        <div class="config-card">
            <h2>Cambiar Contraseña</h2>

            <form action="{{ Auth::guard('investigador')->check() ? route('investigador.config.update-password') : route('admin.config.update-password') }}" method="POST" class="config-form">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="current_password">Contraseña Actual</label>
                        <input type="password" id="current_password" name="current_password" required>
                        @error('current_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Nueva Contraseña</label>
                        <input type="password" id="password" name="password" required>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                        <span class="password-match-indicator" id="password-match-indicator" style="display: none;">
                            <i class="fa-solid fa-times"></i>
                            Las contraseñas no coinciden
                        </span>
                    </div>
                </div>

                <div class="password-requirements">
                    <h4>La contraseña debe contener:</h4>
                    <ul>
                        <li id="req-length">
                            <i class="fa-solid fa-times"></i>
                            Al menos 8 caracteres
                        </li>
                        <li id="req-lowercase">
                            <i class="fa-solid fa-times"></i>
                            Al menos una letra minúscula
                        </li>
                        <li id="req-uppercase">
                            <i class="fa-solid fa-times"></i>
                            Al menos una letra mayúscula
                        </li>
                        <li id="req-number">
                            <i class="fa-solid fa-times"></i>
                            Al menos un número
                        </li>
                        <li id="req-special">
                            <i class="fa-solid fa-times"></i>
                            Al menos un carácter especial
                        </li>
                    </ul>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-lock"></i>
                        Cambiar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab functionality
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;

            // Remove active class from all tabs and contents
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(tabId + '-tab').classList.add('active');
        });
    });

    // Photo preview
    document.getElementById('foto').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('current-photo-img').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Password strength validation
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const passwordMatchIndicator = document.getElementById('password-match-indicator');

    function validatePassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        // Requirements validation
        const requirements = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };

        // Update visual indicators
        Object.keys(requirements).forEach(req => {
            const element = document.getElementById(`req-${req}`);
            const icon = element.querySelector('i');

            if (requirements[req]) {
                element.classList.add('valid');
                element.classList.remove('invalid');
                icon.className = 'fa-solid fa-check';
            } else {
                element.classList.remove('valid');
                element.classList.add('invalid');
                icon.className = 'fa-solid fa-times';
            }
        });

        const isValid = Object.values(requirements).every(req => req);

        // Visual feedback for password input
        if (password && !isValid) {
            passwordInput.classList.add('invalid');
        } else {
            passwordInput.classList.remove('invalid');
        }

        // Confirm password validation
        if (confirmPassword) {
            if (password !== confirmPassword) {
                confirmPasswordInput.classList.add('invalid');
                passwordMatchIndicator.style.display = 'block';
                passwordMatchIndicator.classList.add('invalid');
                passwordMatchIndicator.classList.remove('valid');
                passwordMatchIndicator.innerHTML = '<i class="fa-solid fa-times"></i> Las contraseñas no coinciden';
            } else if (password === confirmPassword && password !== '') {
                confirmPasswordInput.classList.remove('invalid');
                passwordMatchIndicator.style.display = 'block';
                passwordMatchIndicator.classList.add('valid');
                passwordMatchIndicator.classList.remove('invalid');
                passwordMatchIndicator.innerHTML = '<i class="fa-solid fa-check"></i> Las contraseñas coinciden';
            } else {
                passwordMatchIndicator.style.display = 'none';
            }
        } else {
            passwordMatchIndicator.style.display = 'none';
        }
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', validatePassword);
        confirmPasswordInput.addEventListener('input', validatePassword);
    }
</script>
@endpush
