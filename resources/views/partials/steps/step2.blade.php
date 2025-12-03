<!-- Pantalla 2 - Detalles del financiamiento -->
<div class="layout step-content" id="step-2" style="display: none;">
    <div class="card__header">
        <div class="header__step"><strong>2</strong> de 4</div>
        <h2 class="title">Detalles del financiamiento</h2>
    </div>

    <div class="form">
        <div class="form__group">
            <label class="group__label">Tipo de fondo</label>
            <div class="radio__content">
                <input type="radio"
                       class="radio"
                       name="tipo_fondo"
                       id="propios"
                       value="propios"
                       {{ old('tipo_fondo') == 'propios' ? 'checked' : '' }}
                       required/>
                <label for="propios">Propios</label>
            </div>
            <div class="radio__content">
                <input type="radio"
                       class="radio"
                       name="tipo_fondo"
                       id="privado"
                       value="privado"
                       {{ old('tipo_fondo') == 'privado' ? 'checked' : '' }}
                       required/>
                <label for="privado">Privado</label>
            </div>
            <div class="radio__content">
                <input type="radio"
                       class="radio"
                       name="tipo_fondo"
                       id="gobierno"
                       value="gobierno"
                       {{ old('tipo_fondo') == 'gobierno' ? 'checked' : '' }}
                       required/>
                <label for="gobierno">Gobierno</label>
            </div>
            <div class="radio__content">
                <input type="radio"
                       class="radio"
                       name="tipo_fondo"
                       id="fondos-publicos"
                       value="fondos-publicos"
                       {{ old('tipo_fondo') == 'fondos-publicos' ? 'checked' : '' }}
                       required/>
                <label for="fondos-publicos">Fondos públicos generales universitarios</label>
            </div>
            <div class="radio__content">
                <input type="radio"
                       class="radio"
                       name="tipo_fondo"
                       id="instituciones-privadas"
                       value="instituciones-privadas"
                       {{ old('tipo_fondo') == 'instituciones-privadas' ? 'checked' : '' }}
                       required/>
                <label for="instituciones-privadas">Instituciones privadas no lucrativas</label>
            </div>
            <div class="radio__content">
                <input type="radio"
                       class="radio"
                       name="tipo_fondo"
                       id="exterior"
                       value="exterior"
                       {{ old('tipo_fondo') == 'exterior' ? 'checked' : '' }}
                       required/>
                <label for="exterior">Exterior</label>
            </div>
            @error('tipo_fondo')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form__group">
            <label class="group__label">Acción de Transferencia de Tecnología y aplicación del conocimiento</label>
            <input type="text"
                   class="input @error('accion_transferencia') error @enderror"
                   name="accion_transferencia"
                   placeholder="Escriba la acción"
                   value="{{ old('accion_transferencia') }}"
                   required
                   maxlength="200">
            @error('accion_transferencia')
                <small class="error-message">{{ $message }}</small>
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
