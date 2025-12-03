<!-- Pantalla 1 - Tipo de proyecto -->
<div class="layout step-content" id="step-1">
    <div class="card__header">
        <div class="header__step"><strong>1</strong> de 4</div>
        <h2 class="title">Tipo de proyecto</h2>
    </div>

    <div class="form">
        <div class="form__group">
            <label class="group__label">Tipo de investigación</label>
            <select class="select @error('tipo_investigacion') error @enderror" name="tipo_investigacion" required>
                <option value="">Seleccione una opción</option>
                <option value="desarrollo-experimental" {{ old('tipo_investigacion') == 'desarrollo-experimental' ? 'selected' : '' }}>Desarrollo experimental</option>
                <option value="investigacion-basica" {{ old('tipo_investigacion') == 'investigacion-basica' ? 'selected' : '' }}>Investigación básica</option>
                <option value="investigacion-aplicada" {{ old('tipo_investigacion') == 'investigacion-aplicada' ? 'selected' : '' }}>Investigación aplicada</option>
            </select>
            @error('tipo_investigacion')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form__group">
            <label class="group__label">Tipo de financiamiento</label>
            <div class="radio__content">
                <input type="radio"
                       class="radio"
                       name="financiamiento"
                       id="sin-financiamiento"
                       value="sin-financiamiento"
                       {{ old('financiamiento') == 'sin-financiamiento' ? 'checked' : '' }}
                       required/>
                <label for="sin-financiamiento">Sin financiamiento</label>
            </div>
            <div class="radio__content">
                <input type="radio"
                       class="radio"
                       name="financiamiento"
                       id="interno"
                       value="interno"
                       {{ old('financiamiento') == 'interno' ? 'checked' : '' }}
                       required/>
                <label for="interno">Interno</label>
            </div>
            <div class="radio__content">
                <input type="radio"
                       class="radio"
                       name="financiamiento"
                       id="externo"
                       value="externo"
                       {{ old('financiamiento') == 'externo' ? 'checked' : '' }}
                       required/>
                <label for="externo">Externo</label>
            </div>
            @error('financiamiento')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div id="option-html"></div>

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
