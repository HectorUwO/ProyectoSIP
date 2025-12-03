<!-- Pantalla 7 - Resultados esperados -->
<div class="layout step-content" id="step-7" style="display: none;">
    <div class="card__header">
        <div class="header__step"><strong>1</strong> de 1</div>
        <h2 class="title">Resultados esperados</h2>
    </div>

    <div class="form">
        <div class="form__group">
            <label class="group__label">Resultados esperados de la propuesta</label>
            <textarea class="input @error('resultados_esperados') error @enderror"
                      name="resultados_esperados"
                      placeholder="Describa los resultados esperados del proyecto, incluyendo productos, impactos y beneficios que se espera obtener al finalizar la investigación"
                      rows="12"
                      required
                      maxlength="3000">{{ old('resultados_esperados') }}</textarea>
            @error('resultados_esperados')
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
