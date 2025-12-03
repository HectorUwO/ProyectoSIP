<!-- Modal para agregar/editar actividad -->
<div id="activity-modal" class="modal">
    <div class="modal-content modal-content--large">
        <div class="modal-header">
            <div class="modal-header-content">
                <h3 id="modal-title">Agregar actividad</h3>
                <div class="modal-steps">
                    <span class="modal-step modal-step--active" data-step="1">1</span>
                    <span class="modal-step-divider"></span>
                    <span class="modal-step" data-step="2">2</span>
                    <span class="modal-step-divider"></span>
                    <span class="modal-step" data-step="3">3</span>
                </div>
            </div>
            <button type="button" class="modal-close" onclick="closeActivityModal()">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4L4 12M4 4L12 12" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Cancelar
            </button>
        </div>
        <form class="modal-form" id="activity-form">
            <input type="hidden" id="activity-index" value="">

            <!-- Step 1: Nombre -->
            <div class="modal-step-content modal-step-content--active" data-step-content="1">
                <div class="form__group">
                    <label class="group__label" for="activity-name">Nombre de la actividad</label>
                    <input type="text" id="activity-name" class="input" placeholder="Ej. Revisión bibliográfica" required>
                    <small class="input-hint">Ingresa un nombre descriptivo para la actividad</small>
                </div>
            </div>

            <!-- Step 2: Descripción -->
            <div class="modal-step-content" data-step-content="2">
                <div class="form__group">
                    <label class="group__label" for="activity-description">Descripción</label>
                    <textarea id="activity-description" class="input" rows="6" placeholder="Describe detalladamente la actividad, sus objetivos y alcance..." required></textarea>
                    <small class="input-hint">Proporciona una descripción detallada de la actividad</small>
                </div>
            </div>

            <!-- Step 3: Trimestres -->
            <div class="modal-step-content" data-step-content="3">
                <div class="form__group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <label class="group__label">Selecciona los trimestres</label>
                        <button type="button" class="btn-add-quarter" onclick="addMoreQuarters()" id="add-quarters-btn">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 1V15M1 8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Agregar más trimestres
                        </button>
                    </div>

                    <div class="quarters-container" id="quarters-container">
                        <!-- Año 1 (Trimestres 1-4) -->
                        <div class="quarters-year-group">
                            <div class="quarters-year-label">Año 1</div>
                            <div class="quarters-selector">
                                <button type="button" class="quarter-btn" data-quarter="1">
                                    <span class="quarter-number">1</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="2">
                                    <span class="quarter-number">2</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="3">
                                    <span class="quarter-number">3</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="4">
                                    <span class="quarter-number">4</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                            </div>
                        </div>

                        <!-- Año 2 (Trimestres 5-8) -->
                        <div class="quarters-year-group">
                            <div class="quarters-year-label">Año 2</div>
                            <div class="quarters-selector">
                                <button type="button" class="quarter-btn" data-quarter="5">
                                    <span class="quarter-number">5</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="6">
                                    <span class="quarter-number">6</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="7">
                                    <span class="quarter-number">7</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="8">
                                    <span class="quarter-number">8</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                            </div>
                        </div>

                        <!-- Año 3 (Trimestres 9-12) - Inicialmente oculto -->
                        <div class="quarters-year-group" data-year="3" style="display: none;">
                            <div class="quarters-year-label">Año 3</div>
                            <div class="quarters-selector">
                                <button type="button" class="quarter-btn" data-quarter="9">
                                    <span class="quarter-number">9</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="10">
                                    <span class="quarter-number">10</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="11">
                                    <span class="quarter-number">11</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="12">
                                    <span class="quarter-number">12</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                            </div>
                        </div>

                        <!-- Año 4 (Trimestres 13-16) - Inicialmente oculto -->
                        <div class="quarters-year-group" data-year="4" style="display: none;">
                            <div class="quarters-year-label">Año 4</div>
                            <div class="quarters-selector">
                                <button type="button" class="quarter-btn" data-quarter="13">
                                    <span class="quarter-number">13</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="14">
                                    <span class="quarter-number">14</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="15">
                                    <span class="quarter-number">15</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                                <button type="button" class="quarter-btn" data-quarter="16">
                                    <span class="quarter-number">16</span>
                                    <span class="quarter-label">Trimestre</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje para proyectos de más de 4 años -->
                    <div class="extra-time-notice" id="extra-time-notice" style="display: none;">
                        <div class="notice-content">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 16V12" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 8H12.01" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="notice-text">
                                <strong>¿Tu proyecto necesita más tiempo?</strong>
                                <p>Si tu proyecto requiere más de 4 años, por favor sube un documento con tu plan de trabajo detallado.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-upload-plan" onclick="openWorkPlanUpload()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17 8L12 3L7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 3V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Subir plan de trabajo
                        </button>
                    </div>

                    <p class="quarters-help">Selecciona uno o más trimestres haciendo clic en ellos</p>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" id="modal-prev-btn" onclick="prevModalStep()" style="display: none;">
                    <i class="fa-solid fa-angle-left"></i> Anterior
                </button>
                <button type="button" class="btn btn--secondary" onclick="closeActivityModal()">Cancelar</button>
                <button type="button" class="btn btn--primary has-icon" id="modal-next-btn" onclick="nextModalStep()">
                    Siguiente <i class="fa-solid fa-angle-right"></i>
                </button>
                <button type="submit" class="btn btn--primary" id="modal-submit-btn" style="display: none;">Guardar actividad</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para subir plan de trabajo -->
<div id="work-plan-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Subir plan de trabajo</h3>
            <button type="button" class="modal-close" onclick="closeWorkPlanUpload()">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4L4 12M4 4L12 12" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Cancelar
            </button>
        </div>
        <div class="modal-form">
            <p style="margin: 0 0 20px 0; color: #666; line-height: 1.6;">
                Para proyectos que requieren más de 4 años, es necesario adjuntar un plan de trabajo detallado que justifique la duración extendida del proyecto.
            </p>

            <div class="form__group">
                <label class="group__label">Documento del plan de trabajo</label>
                <div class="file-upload-area" id="file-upload-area">
                    <input type="file" id="work-plan-file" accept=".pdf,.doc,.docx" style="display: none;" onchange="handleFileSelect(event)">
                    <div class="upload-placeholder" id="upload-placeholder" onclick="document.getElementById('work-plan-file').click()">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17 8L12 3L7 8" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 3V15" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p>Haz clic para seleccionar o arrastra el archivo aquí</p>
                        <small>Formatos aceptados: PDF, DOC, DOCX (Máx. 10MB)</small>
                    </div>
                    <div class="file-selected" id="file-selected" style="display: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V9L13 2Z" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13 2V9H20" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="file-info">
                            <p class="file-name" id="file-name"></p>
                            <small class="file-size" id="file-size"></small>
                        </div>
                        <button type="button" class="btn-remove-file" onclick="removeFile()">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4L4 12M4 4L12 12" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" onclick="closeWorkPlanUpload()">Cancelar</button>
                <button type="button" class="btn btn--primary" onclick="saveWorkPlan()" id="save-work-plan-btn" disabled>Guardar documento</button>
            </div>
        </div>
    </div>
</div>

