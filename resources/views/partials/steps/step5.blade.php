<!-- Pantalla 5 - Grupo de trabajo -->
<div class="layout step-content" id="step-5" style="display: none;">
    <div class="card__header">
        <div class="header__step"><strong>5</strong> de 5</div>
        <h2 class="title">Grupo de trabajo</h2>
    </div>

    <div class="form">
        <div class="form__group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <label class="group__label">Colaborador profesor</label>
                <button type="button" class="btn-add" onclick="addCollaborator('profesor')">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 1V15M1 8H15" stroke="#22C55E" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Agregar profesor
                </button>
            </div>

            <div class="collaborator-list">
                <!-- Colaboradores profesores se agregarán dinámicamente -->
            </div>
        </div>

        <div class="form__group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <label class="group__label">Colaborador estudiante</label>
                <button type="button" class="btn-add" onclick="addCollaborator('estudiante')">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 1V15M1 8H15" stroke="#22C55E" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Agregar estudiante
                </button>
            </div>

            <div class="collaborator-list">
                <!-- Colaboradores estudiantes se agregarán dinámicamente -->
            </div>
        </div>

        <div class="form__actions">
            <button type="button" class="btn btn--secondary" onclick="prevStep()">
                Regresar
            </button>
            <button type="button" class="btn btn--primary has-icon" onclick="nextStep()">
                Continuar
                <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 13.5L7 7.5L1 1.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
</div>
