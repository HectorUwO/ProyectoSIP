<!-- Modal para agregar/editar impacto -->
<div id="modal-impacto" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-header-content">
                <h3 id="modal-impacto-title">Agregar impacto</h3>
            </div>
            <button type="button" class="modal-close" onclick="closeImpactModal()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18M6 6L18 18" stroke="#6B7280" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <form class="modal-form" id="impact-form" onsubmit="event.preventDefault(); saveImpact();">
            <div class="form__group">
                <label class="group__label" id="impact-label">Descripción del impacto</label>
                <textarea class="input"
                          id="impact-description"
                          name="impacto_description"
                          placeholder="Describa el impacto esperado del proyecto"
                          rows="6"
                          required
                          maxlength="500"></textarea>
                <small class="input-hint">Máximo 500 caracteres</small>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn--secondary" onclick="closeImpactModal()">
                    Cancelar
                </button>
                <button type="submit" class="btn btn--primary">
                    Guardar impacto
                </button>
            </div>
        </form>
    </div>
</div>
