<!-- Modal para agregar estudiante -->
<div id="modal-estudiante" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nuevo estudiante</h3>
            <button type="button" class="modal-close" onclick="closeModal('modal-estudiante')">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18M6 6L18 18" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Cancelar
            </button>
        </div>

        <form class="modal-form">
            <div class="form__group">
                <label class="group__label">Nombre completo</label>
                <input type="text"
                       class="input @error('estudiante_nombre') error @enderror"
                       name="estudiante_nombre"
                       placeholder="Escriba su nombre completo"
                       value="{{ old('estudiante_nombre') }}"
                       required>
                @error('estudiante_nombre')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>

            <div class="form__group">
                <label class="group__label">Actividad</label>
                <input type="text"
                       class="input @error('estudiante_actividad') error @enderror"
                       name="estudiante_actividad"
                       placeholder="Escriba la actividad"
                       value="{{ old('estudiante_actividad') }}"
                       required>
                @error('estudiante_actividad')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>

            <div class="form__group">
                <label class="group__label">Tipo de formación académica</label>
                <select class="select @error('estudiante_tipo_formacion') error @enderror"
                        name="estudiante_tipo_formacion"
                        required>
                    <option value="">Seleccione una opción</option>
                    <option value="servicio-social" {{ old('estudiante_tipo_formacion') == 'servicio-social' ? 'selected' : '' }}>Servicio social</option>
                    <option value="practicas-profesionales" {{ old('estudiante_tipo_formacion') == 'practicas-profesionales' ? 'selected' : '' }}>Prácticas profesionales</option>
                    <option value="tesis" {{ old('estudiante_tipo_formacion') == 'tesis' ? 'selected' : '' }}>Tesis</option>
                    <option value="becario" {{ old('estudiante_tipo_formacion') == 'becario' ? 'selected' : '' }}>Becario</option>
                </select>
                @error('estudiante_tipo_formacion')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>

            <div class="form__group">
                <label class="group__label">Máximo grado académico obtenido</label>
                <input type="hidden" name="estudiante_grado" value="{{ old('estudiante_grado', 'Licenciatura') }}" required>
                <div class="card-select">
                    <div class="card {{ old('estudiante_grado', 'Licenciatura') == 'Licenciatura' ? 'card--active' : '' }}">
                        <svg width="24" height="30" viewBox="0 0 24 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 30C2.175 30 1.469 29.7065 0.882 29.1195C0.295 28.5325 0.001 27.826 0 27V3C0 2.175 0.294 1.469 0.882 0.882C1.47 0.295 2.176 0.001 3 0H21C21.825 0 22.5315 0.294 23.1195 0.882C23.7075 1.47 24.001 2.176 24 3V27C24 27.825 23.7065 28.5315 23.1195 29.1195C22.5325 29.7075 21.826 30.001 21 30H3ZM10.5 13.5L14.25 11.25L18 13.5V3H10.5V13.5Z" fill="#656565"/>
                        </svg>
                        <div class="card__title">Licenciatura</div>
                    </div>
                    <div class="card {{ old('estudiante_grado', 'Licenciatura') == 'Maestría' ? 'card--active' : '' }}">
                        <svg width="24" height="30" viewBox="0 0 24 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 30C2.175 30 1.469 29.7065 0.882 29.1195C0.295 28.5325 0.001 27.826 0 27V3C0 2.175 0.294 1.469 0.882 0.882C1.47 0.295 2.176 0.001 3 0H21C21.825 0 22.5315 0.294 23.1195 0.882C23.7075 1.47 24.001 2.176 24 3V27C24 27.825 23.7065 28.5315 23.1195 29.1195C22.5325 29.7075 21.826 30.001 21 30H3ZM10.5 13.5L14.25 11.25L18 13.5V3H10.5V13.5Z" fill="#656565"/>
                        </svg>
                        <div class="card__title">Maestría</div>
                    </div>
                    <div class="card {{ old('estudiante_grado', 'Licenciatura') == 'Doctorado' ? 'card--active' : '' }}">
                        <svg width="24" height="30" viewBox="0 0 24 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 30C2.175 30 1.469 29.7065 0.882 29.1195C0.295 28.5325 0.001 27.826 0 27V3C0 2.175 0.294 1.469 0.882 0.882C1.47 0.295 2.176 0.001 3 0H21C21.825 0 22.5315 0.294 23.1195 0.882C23.7075 1.47 24.001 2.176 24 3V27C24 27.825 23.7065 28.5315 23.1195 29.1195C22.5325 29.7075 21.826 30.001 21 30H3ZM10.5 13.5L14.25 11.25L18 13.5V3H10.5V13.5Z" fill="#656565"/>
                        </svg>
                        <div class="card__title">Doctorado</div>
                    </div>
                </div>
                @error('estudiante_grado')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn--primary" onclick="addStudent()">
                    Agregar alumno
                </button>
            </div>
        </form>
    </div>
</div>
