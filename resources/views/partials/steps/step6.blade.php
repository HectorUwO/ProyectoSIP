<!-- Pantalla 6 - Protocolo de investigación -->
<div class="layout step-content" id="step-6" style="display: none;">
    <div class="card__header">
        <div class="header__step"><strong>1</strong> de 1</div>
        <h2 class="title">Protocolo de investigación</h2>
    </div>

    <div class="form">
        <div class="form__group">
            <label class="group__label">Protocolo de investigación</label>
            <p class="form__description" style="margin-bottom: 24px; color: #6B7280; font-size: 0.875rem; line-height: 1.5;">
                Sube el documento que contiene el protocolo completo de investigación. Este documento debe incluir objetivos, metodología, cronograma y recursos necesarios.
            </p>

            <div class="file-upload-area" id="file-upload-area-step6">
                <input type="file"
                       class="file-input @error('protocolo_investigacion') error @enderror"
                       name="protocolo_investigacion"
                       id="protocolo_investigacion"
                       accept=".pdf,.doc,.docx"
                       required
                       style="display: none;">

                <div class="upload-placeholder" id="upload-placeholder-step6">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #9CA3AF; margin-bottom: 16px;">
                        <path d="M7 18C4.79086 18 3 16.2091 3 14C3 11.7909 4.79086 10 7 10C7.27885 10 7.55151 10.0223 7.81693 10.0651C8.29476 6.75108 11.2068 4 14.8333 4C18.055 4 20.7738 6.28107 21.465 9.34268C23.5298 9.73344 25 11.5607 25 13.8C25 16.3405 22.9853 18.3973 20.5 18.3973H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 12V21M12 12L9 15M12 12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 8px;">
                        Arrastra tu archivo aquí
                    </h3>
                    <p style="font-size: 0.875rem; color: #6B7280; margin-bottom: 16px;">
                        o haz clic para seleccionar
                    </p>
                    <button type="button" class="btn btn--secondary btn--small" onclick="document.getElementById('protocolo_investigacion').click()">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                            <path d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10M11.3333 5.33333L8 2M8 2L4.66667 5.33333M8 2V10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Seleccionar archivo
                    </button>
                    <p style="font-size: 0.75rem; color: #9CA3AF; margin-top: 12px;">
                        Formatos: PDF, DOC, DOCX • Tamaño máximo: 10MB
                    </p>
                </div>

                <div class="file-selected" id="file-selected-step6" style="display: none;">
                    <div style="display: flex; align-items: center; gap: 16px; padding: 20px; background: #F9FAFB; border-radius: 12px; border: 2px solid #10B981;">
                        <div style="flex-shrink: 0; width: 48px; height: 48px; background: #D1FAE5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 18C4.79086 18 3 16.2091 3 14C3 11.7909 4.79086 10 7 10C7.27885 10 7.55151 10.0223 7.81693 10.0651C8.29476 6.75108 11.2068 4 14.8333 4C18.055 4 20.7738 6.28107 21.465 9.34268C23.5298 9.73344 25 11.5607 25 13.8C25 16.3405 22.9853 18.3973 20.5 18.3973H16" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 11L12 8L15 11M12 8V16" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.3333 7.38662V3.99996C13.3333 3.64634 13.1928 3.3072 12.9428 3.05715C12.6927 2.8071 12.3536 2.66663 12 2.66663H3.99996C3.64634 2.66663 3.3072 2.8071 3.05715 3.05715C2.8071 3.3072 2.66663 3.64634 2.66663 3.99996V12C2.66663 12.3536 2.8071 12.6927 3.05715 12.9428C3.3072 13.1928 3.64634 13.3333 3.99996 13.3333H7.38662M9.33329 9.33329L13.3333 13.3333M13.3333 13.3333L9.33329 13.3333M13.3333 13.3333V9.33329" stroke="#10B981" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <h4 id="file-name-step6" style="font-size: 0.875rem; font-weight: 600; color: #111827; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    archivo.pdf
                                </h4>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <p id="file-size-step6" style="font-size: 0.75rem; color: #6B7280;">
                                    0 KB
                                </p>
                                <span style="width: 4px; height: 4px; background: #D1D5DB; border-radius: 50%;"></span>
                                <p style="font-size: 0.75rem; color: #10B981; font-weight: 500;">
                                    ✓ Archivo listo para subir
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn-icon-danger" onclick="removeFileStep6()" title="Eliminar archivo">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            @error('protocolo_investigacion')
                <small class="error-message" style="display: block; margin-top: 8px;">{{ $message }}</small>
            @enderror
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
