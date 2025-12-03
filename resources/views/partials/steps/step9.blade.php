<!-- Pantalla 9 - Cronograma de actividades -->
<div class="layout step-content" id="step-9" style="display: none;">
    <div class="card__header">
        <div class="header__step"><strong>1</strong> de 1</div>
        <h2 class="title">Cronograma de actividades</h2>
    </div>

    <div class="form">
        <div class="form__group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <label class="group__label">Actividades del proyecto</label>
                <button type="button" class="btn-add" onclick="openActivityModal()">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 1V15M1 8H15" stroke="#22C55E" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Agregar actividad
                </button>
            </div>

            <!-- Lista de actividades -->
            <div class="activities-list" id="activities-list">
                <!-- Las actividades se agregarán dinámicamente aquí -->
                <div class="empty-state" id="empty-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 11L12 14L22 4" stroke="#D1D5DB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 12V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16" stroke="#D1D5DB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p>No hay actividades agregadas</p>
                    <span>Comienza agregando la primera actividad del proyecto</span>
                </div>
            </div>

            <!-- Paginación -->
            <div class="activities-pagination" id="activities-pagination" style="display: none;">
                <button type="button" class="pagination-btn" id="prev-page-btn" onclick="scheduleManager.prevPage()">
                    <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 1.5L1 7.5L7 13.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Anterior
                </button>
                <span class="pagination-info" id="pagination-info"></span>
                <button type="button" class="pagination-btn" id="next-page-btn" onclick="scheduleManager.nextPage()">
                    Siguiente
                    <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 13.5L7 7.5L1 1.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="form__actions">
            <button type="button" class="btn btn--secondary" onclick="prevStep()">
                Regresar
            </button>
            <button type="button" class="btn btn--primary has-icon" onclick="submitForm()">
                Enviar
                <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 13.5L7 7.5L1 1.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
</div>
