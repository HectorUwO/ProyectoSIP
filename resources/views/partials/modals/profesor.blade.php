<!-- Modal para agregar profesor -->
<div id="modal-profesor" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Agregar profesor</h3>
            <button type="button" class="modal-close" onclick="closeModal('modal-profesor')">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18M6 6L18 18" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Cancelar
            </button>
        </div>

        <!-- Vista de búsqueda (por defecto) -->
        <div id="profesor-search-view" class="modal-form">
            <div class="form__group">
                <label class="group__label">Buscar profesor</label>
                <input type="text"
                       id="profesor-search-input"
                       class="input"
                       placeholder="Escriba el nombre del profesor"
                       oninput="searchProfesores(this.value)">
                <small style="color: #6B7280; font-size: 14px; margin-top: 8px; display: block;">
                    Busque un profesor registrado en el sistema
                </small>
            </div>

            <!-- Lista de resultados de búsqueda -->
            <div id="profesor-search-results" class="search-results" style="display: none;">
                <!-- Los resultados se cargarán dinámicamente aquí -->
            </div>

            <div class="modal-actions" style="margin-top: 24px;">
                <button type="button" class="btn btn--secondary" onclick="showProfesorRegistrationForm()">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                        <path d="M8 1V15M1 8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Registrar nuevo profesor
                </button>
            </div>
        </div>

        <!-- Vista de registro (oculta por defecto) -->
        <div id="profesor-registration-view" class="modal-form" style="display: none;">
            <div style="margin-bottom: 24px;">
                <button type="button" class="btn btn--text" onclick="showProfesorSearchView()" style="padding: 0; color: #3B82F6; display: flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Volver a búsqueda
                </button>
            </div>

            <form id="profesor-registration-form">
                <div class="form__group">
                    <label class="group__label">Nombre completo</label>
                    <input type="text"
                           class="input @error('profesor_nombre') error @enderror"
                           name="profesor_nombre"
                           placeholder="Escriba su nombre completo"
                           value="{{ old('profesor_nombre') }}"
                           required>
                    @error('profesor_nombre')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form__group">
                    <label class="group__label">Actividad</label>
                    <input type="text"
                           class="input @error('profesor_actividad') error @enderror"
                           name="profesor_actividad"
                           placeholder="Escriba la actividad"
                           value="{{ old('profesor_actividad') }}"
                           required>
                    @error('profesor_actividad')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form__group">
                    <label class="group__label">Máximo grado académico obtenido</label>
                    <input type="hidden" name="profesor_grado" value="{{ old('profesor_grado', 'Doctorado') }}" required>
                    <div class="card-select">
                        <div class="card {{ old('profesor_grado', 'Doctorado') == 'Licenciatura' ? 'card--active' : '' }}">
                            <svg width="24" height="30" viewBox="0 0 24 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 30C2.175 30 1.469 29.7065 0.882 29.1195C0.295 28.5325 0.001 27.826 0 27V3C0 2.175 0.294 1.469 0.882 0.882C1.47 0.295 2.176 0.001 3 0H21C21.825 0 22.5315 0.294 23.1195 0.882C23.7075 1.47 24.001 2.176 24 3V27C24 27.825 23.7065 28.5315 23.1195 29.1195C22.5325 29.7075 21.826 30.001 21 30H3ZM10.5 13.5L14.25 11.25L18 13.5V3H10.5V13.5Z" fill="#656565"/>
                            </svg>
                            <div class="card__title">Licenciatura</div>
                        </div>
                        <div class="card {{ old('profesor_grado', 'Doctorado') == 'Maestría' ? 'card--active' : '' }}">
                            <svg width="24" height="30" viewBox="0 0 24 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 30C2.175 30 1.469 29.7065 0.882 29.1195C0.295 28.5325 0.001 27.826 0 27V3C0 2.175 0.294 1.469 0.882 0.882C1.47 0.295 2.176 0.001 3 0H21C21.825 0 22.5315 0.294 23.1195 0.882C23.7075 1.47 24.001 2.176 24 3V27C24 27.825 23.7065 28.5315 23.1195 29.1195C22.5325 29.7075 21.826 30.001 21 30H3ZM10.5 13.5L14.25 11.25L18 13.5V3H10.5V13.5Z" fill="#656565"/>
                            </svg>
                            <div class="card__title">Maestría</div>
                        </div>
                        <div class="card {{ old('profesor_grado', 'Doctorado') == 'Doctorado' ? 'card--active' : '' }}">
                            <svg width="24" height="30" viewBox="0 0 24 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 30C2.175 30 1.469 29.7065 0.882 29.1195C0.295 28.5325 0.001 27.826 0 27V3C0 2.175 0.294 1.469 0.882 0.882C1.47 0.295 2.176 0.001 3 0H21C21.825 0 22.5315 0.294 23.1195 0.882C23.7075 1.47 24.001 2.176 24 3V27C24 27.825 23.7065 28.5315 23.1195 29.1195C22.5325 29.7075 21.826 30.001 21 30H3ZM10.5 13.5L14.25 11.25L18 13.5V3H10.5V13.5Z" fill="#656565"/>
                            </svg>
                            <div class="card__title">Doctorado</div>
                        </div>
                    </div>
                    @error('profesor_grado')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn--primary" onclick="addProfessor()">
                        Registrar y agregar profesor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.search-results {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    margin-top: 12px;
}

.search-result-item {
    padding: 16px;
    border-bottom: 1px solid #E5E7EB;
    cursor: pointer;
    transition: background-color 0.2s;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.search-result-item:last-child {
    border-bottom: none;
}

.search-result-item:hover {
    background-color: #F9FAFB;
}

.search-result-info {
    flex: 1;
}

.search-result-name {
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 4px;
}

.search-result-details {
    font-size: 14px;
    color: #6B7280;
}

.btn--text {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.btn--text:hover {
    text-decoration: underline;
}

.search-results-empty {
    padding: 24px;
    text-align: center;
    color: #6B7280;
}
</style>


