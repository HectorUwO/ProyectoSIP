<!-- Pantalla 8 - Impacto de la propuesta -->
<div class="layout step-content" id="step-8" style="display: none;">
    <div class="card__header">
        <h2 class="title">Impacto de la propuesta</h2>
    </div>

    <div class="form">
        <div class="form__group">
            <label class="group__label">Usuario específico de los resultados o productos del proyecto</label>
            <textarea class="input @error('usuario_especifico') error @enderror"
                      name="usuario_especifico"
                      placeholder="Describa quién será el usuario específico que se beneficiará directamente de los resultados o productos del proyecto"
                      rows="4"
                      required
                      maxlength="500">{{ old('usuario_especifico') }}</textarea>
            @error('usuario_especifico')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form__group">
            <label class="group__label">Seleccione los tipos de impacto que aplican a su proyecto</label>
            <div class="card-select" id="impact-types-select">
                <div class="card" data-impact="cientifico" onclick="openImpactModal('cientifico')">
                    <div class="card-check-icon" style="display: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6L9 17L4 12" stroke="#22C55E" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <svg class="icon" width="48" height="48" viewBox="0 0 448 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M288 0L128 0C110.3 0 96 14.3 96 32s14.3 32 32 32L128 215.5 7.5 426.3C2.6 435 0 444.7 0 454.7 0 486.4 25.6 512 57.3 512l333.4 0c31.6 0 57.3-25.6 57.3-57.3 0-10-2.6-19.8-7.5-28.4L320 215.5 320 64c17.7 0 32-14.3 32-32S337.7 0 320 0L288 0zM192 215.5l0-151.5 64 0 0 151.5c0 11.1 2.9 22.1 8.4 31.8l41.6 72.7-164 0 41.6-72.7c5.5-9.7 8.4-20.6 8.4-31.8z" stroke="#003D84" stroke-width="8" fill="#003D84"/>
                    </svg>
                    <h3 class="card__title">Científico</h3>
                    <p class="card__description">Contribución al conocimiento y avances teóricos</p>
                    <input type="hidden" name="impacto_cientifico" id="impacto_cientifico">
                </div>

                <div class="card" data-impact="tecnologico" onclick="openImpactModal('tecnologico')">
                    <div class="card-check-icon" style="display: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6L9 17L4 12" stroke="#22C55E" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <svg class="icon" width="48" height="48" viewBox="0 0 640 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M415.9 210.5c12.2-3.3 25 2.5 30.5 13.8L465 261.9c10.3 1.4 20.4 4.2 29.9 8.1l35-23.3c10.5-7 24.4-5.6 33.3 3.3l19.2 19.2c8.9 8.9 10.3 22.9 3.3 33.3l-23.3 34.9c1.9 4.7 3.6 9.6 5 14.7 1.4 5.1 2.3 10.1 3 15.2l37.7 18.6c11.3 5.6 17.1 18.4 13.8 30.5l-7 26.2c-3.3 12.1-14.6 20.3-27.2 19.5l-42-2.7c-6.3 8.1-13.6 15.6-21.9 22l2.7 41.9c.8 12.6-7.4 24-19.5 27.2l-26.2 7c-12.2 3.3-24.9-2.5-30.5-13.8l-18.6-37.6c-10.3-1.4-20.4-4.2-29.9-8.1l-35 23.3c-10.5 7-24.4 5.6-33.3-3.3l-19.2-19.2c-8.9-8.9-10.3-22.8-3.3-33.3l23.3-35c-1.9-4.7-3.6-9.6-5-14.7s-2.3-10.2-3-15.2l-37.7-18.6c-11.3-5.6-17-18.4-13.8-30.5l7-26.2c3.3-12.1 14.6-20.3 27.2-19.5l41.9 2.7c6.3-8.1 13.6-15.6 21.9-22l-2.7-41.8c-.8-12.6 7.4-24 19.5-27.2l26.2-7zM448.4 340a44 44 0 1 0 .1 88 44 44 0 1 0 -.1-88zM224.9-45.5l26.2 7c12.1 3.3 20.3 14.7 19.5 27.2l-2.7 41.8c8.3 6.4 15.6 13.8 21.9 22l42-2.7c12.5-.8 23.9 7.4 27.2 19.5l7 26.2c3.2 12.1-2.5 24.9-13.8 30.5l-37.7 18.6c-.7 5.1-1.7 10.2-3 15.2s-3.1 10-5 14.7l23.3 35c7 10.5 5.6 24.4-3.3 33.3L307.3 262c-8.9 8.9-22.8 10.3-33.3 3.3L239 242c-9.5 3.9-19.6 6.7-29.9 8.1l-18.6 37.6c-5.6 11.3-18.4 17-30.5 13.8l-26.2-7c-12.2-3.3-20.3-14.7-19.5-27.2l2.7-41.9c-8.3-6.4-15.6-13.8-21.9-22l-42 2.7c-12.5 .8-23.9-7.4-27.2-19.5l-7-26.2c-3.2-12.1 2.5-24.9 13.8-30.5l37.7-18.6c.7-5.1 1.7-10.1 3-15.2 1.4-5.1 3-10 5-14.7L55.1 46.5c-7-10.5-5.6-24.4 3.3-33.3L77.6-6c8.9-8.9 22.8-10.3 33.3-3.3l35 23.3c9.5-3.9 19.6-6.7 29.9-8.1l18.6-37.6c5.6-11.3 18.3-17 30.5-13.8zM192.4 84a44 44 0 1 0 0 88 44 44 0 1 0 0-88z" stroke="#003D84" stroke-width="4" fill="#003D84"/>
                    </svg>
                    <h3 class="card__title">Tecnológico</h3>
                    <p class="card__description">Desarrollo de nuevas tecnologías e innovaciones</p>
                    <input type="hidden" name="impacto_tecnologico" id="impacto_tecnologico">
                </div>

                <div class="card" data-impact="social" onclick="openImpactModal('social')">
                    <div class="card-check-icon" style="display: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6L9 17L4 12" stroke="#22C55E" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <svg class="icon" width="48" height="48" viewBox="0 0 640 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M320 16a104 104 0 1 1 0 208 104 104 0 1 1 0-208zM96 88a72 72 0 1 1 0 144 72 72 0 1 1 0-144zM0 416c0-70.7 57.3-128 128-128 12.8 0 25.2 1.9 36.9 5.4-32.9 36.8-52.9 85.4-52.9 138.6l0 16c0 11.4 2.4 22.2 6.7 32L32 480c-17.7 0-32-14.3-32-32l0-32zm521.3 64c4.3-9.8 6.7-20.6 6.7-32l0-16c0-53.2-20-101.8-52.9-138.6 11.7-3.5 24.1-5.4 36.9-5.4 70.7 0 128 57.3 128 128l0 32c0 17.7-14.3 32-32 32l-86.7 0zM472 160a72 72 0 1 1 144 0 72 72 0 1 1 -144 0zM160 432c0-88.4 71.6-160 160-160s160 71.6 160 160l0 16c0 17.7-14.3 32-32 32l-256 0c-17.7 0-32-14.3-32-32l0-16z" stroke="#003D84" stroke-width="4" fill="#003D84"/>
                    </svg>
                    <h3 class="card__title">Social</h3>
                    <p class="card__description">Beneficios para la comunidad y desarrollo social</p>
                    <input type="hidden" name="impacto_social" id="impacto_social">
                </div>

                <div class="card" data-impact="economico" onclick="openImpactModal('economico')">
                    <div class="card-check-icon" style="display: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6L9 17L4 12" stroke="#22C55E" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <svg class="icon" width="48" height="48" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M238.7 5.1c10.5-6.8 24.1-6.8 34.6 0l224 144c11.9 7.7 17.4 22.3 13.4 35.9s-16.5 23-30.7 23l-32 0 0 208 51.2 38.4c8.1 6 12.8 15.5 12.8 25.6 0 17.7-14.3 32-32 32L32 512c-17.7 0-32-14.3-32-32 0-10.1 4.7-19.6 12.8-25.6l51.2-38.4 0 0 0-208-32 0c-14.2 0-26.7-9.4-30.7-23s1.5-28.3 13.4-35.9l224-144zM336 208l0 208 64 0 0-208-64 0zM224 416l64 0 0-208-64 0 0 208zM112 208l0 208 64 0 0-208-64 0z" stroke="#003D84" stroke-width="8" fill="#003D84"/>
                    </svg>
                    <h3 class="card__title">Económico</h3>
                    <p class="card__description">Generación de valor y oportunidades económicas</p>
                    <input type="hidden" name="impacto_economico" id="impacto_economico">
                </div>

                <div class="card" data-impact="ambiental" onclick="openImpactModal('ambiental')">
                    <div class="card-check-icon" style="display: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6L9 17L4 12" stroke="#22C55E" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <svg class="icon" width="48" height="48" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M512 32C512 140.1 435.4 230.3 333.6 251.4 325.7 193.3 299.6 141 261.1 100.5 301.2 40 369.9 0 448 0l32 0c17.7 0 32 14.3 32 32zM0 96C0 78.3 14.3 64 32 64l32 0c123.7 0 224 100.3 224 224l0 192c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-160C100.3 320 0 219.7 0 96z" stroke="#003D84" stroke-width="8" fill="#003D84"/>
                    </svg>
                    <h3 class="card__title">Ambiental</h3>
                    <p class="card__description">Sostenibilidad y conservación del medio ambiente</p>
                    <input type="hidden" name="impacto_ambiental" id="impacto_ambiental">
                </div>
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
