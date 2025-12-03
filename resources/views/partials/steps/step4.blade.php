<!-- Pantalla 4 - Entregables -->
<div class="layout step-content" id="step-4" style="display: none;">
    <div class="card__header">
        <div class="header__step"><strong>4</strong> de 4</div>
        <h2 class="title">Entregables</h2>
    </div>

    <div class="form">
        <div class="form__group">
            <div class="deliverable-item">
                <div class="deliverable-controls">
                    <button type="button" class="counter-btn" onclick="decrementCounter('articulos-indexada')">−</button>
                    <button type="button" class="counter-btn" onclick="incrementCounter('articulos-indexada')">+</button>
                </div>
                <div class="deliverable-info">
                    <span class="deliverable-count" id="articulos-indexada">{{ old('articulos_indexada', '0') }}</span>
                    <span class="deliverable-label">Artículos (revista indexada)</span>
                </div>
            </div>

            <div class="deliverable-item">
                <div class="deliverable-controls">
                    <button type="button" class="counter-btn" onclick="decrementCounter('articulos-arbitrada')">−</button>
                    <button type="button" class="counter-btn" onclick="incrementCounter('articulos-arbitrada')">+</button>
                </div>
                <div class="deliverable-info">
                    <span class="deliverable-count" id="articulos-arbitrada">{{ old('articulos_arbitrada', '0') }}</span>
                    <span class="deliverable-label">Artículos (revista arbitrada)</span>
                </div>
            </div>

            <div class="deliverable-item">
                <div class="deliverable-controls">
                    <button type="button" class="counter-btn" onclick="decrementCounter('libros')">−</button>
                    <button type="button" class="counter-btn" onclick="incrementCounter('libros')">+</button>
                </div>
                <div class="deliverable-info">
                    <span class="deliverable-count" id="libros">{{ old('libros', '0') }}</span>
                    <span class="deliverable-label">Libros</span>
                </div>
            </div>

            <div class="deliverable-item">
                <div class="deliverable-controls">
                    <button type="button" class="counter-btn" onclick="decrementCounter('capitulo-libro')">−</button>
                    <button type="button" class="counter-btn" onclick="incrementCounter('capitulo-libro')">+</button>
                </div>
                <div class="deliverable-info">
                    <span class="deliverable-count" id="capitulo-libro">{{ old('capitulo_libro', '0') }}</span>
                    <span class="deliverable-label">Capítulo de libro</span>
                </div>
            </div>

            <div class="deliverable-item">
                <div class="deliverable-controls">
                    <button type="button" class="counter-btn" onclick="decrementCounter('memorias-congreso')">−</button>
                    <button type="button" class="counter-btn" onclick="incrementCounter('memorias-congreso')">+</button>
                </div>
                <div class="deliverable-info">
                    <span class="deliverable-count" id="memorias-congreso">{{ old('memorias_congreso', '0') }}</span>
                    <span class="deliverable-label">Memorias en extenso de congreso</span>
                </div>
            </div>

            <div class="deliverable-item">
                <div class="deliverable-controls">
                    <button type="button" class="counter-btn" onclick="decrementCounter('tesis')">−</button>
                    <button type="button" class="counter-btn" onclick="incrementCounter('tesis')">+</button>
                </div>
                <div class="deliverable-info">
                    <span class="deliverable-count" id="tesis">{{ old('tesis', '0') }}</span>
                    <span class="deliverable-label">Tesis</span>
                </div>
            </div>

            <div class="deliverable-item">
                <div class="deliverable-controls">
                    <button type="button" class="counter-btn" onclick="decrementCounter('material-didactico')">−</button>
                    <button type="button" class="counter-btn" onclick="incrementCounter('material-didactico')">+</button>
                </div>
                <div class="deliverable-info">
                    <span class="deliverable-count" id="material-didactico">{{ old('material_didactico', '0') }}</span>
                    <span class="deliverable-label">Material didáctico derivado del proyecto</span>
                </div>
            </div>
        </div>

        <div class="form__group">
            <label class="group__label">Otro</label>
            <input type="text"
                   class="input @error('otros_entregables') error @enderror"
                   name="otros_entregables"
                   placeholder="Patentes, fotografía científica etc."
                   value="{{ old('otros_entregables') }}"
                   maxlength="200">
            @error('otros_entregables')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        {{-- Campos hidden para enviar los contadores --}}
        <input type="hidden" name="articulos_indexada" id="articulos_indexada_input" value="{{ old('articulos_indexada', '0') }}">
        <input type="hidden" name="articulos_arbitrada" id="articulos_arbitrada_input" value="{{ old('articulos_arbitrada', '0') }}">
        <input type="hidden" name="libros" id="libros_input" value="{{ old('libros', '0') }}">
        <input type="hidden" name="capitulo_libro" id="capitulo_libro_input" value="{{ old('capitulo_libro', '0') }}">
        <input type="hidden" name="memorias_congreso" id="memorias_congreso_input" value="{{ old('memorias_congreso', '0') }}">
        <input type="hidden" name="tesis" id="tesis_input" value="{{ old('tesis', '0') }}">
        <input type="hidden" name="material_didactico" id="material_didactico_input" value="{{ old('material_didactico', '0') }}">

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
