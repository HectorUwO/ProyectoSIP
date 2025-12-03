<!-- Pantalla 3 - Detalles del proyecto -->
<div class="layout step-content" id="step-3" style="display: none;">
    <div class="card__header">
        <div class="header__step"><strong>3</strong> de 4</div>
        <h2 class="title">Detalles del proyecto</h2>
    </div>

    <div class="form">
        <div class="form__group">
            <label class="group__label">Nombre del proyecto</label>
            <input type="text"
                   class="input @error('nombre_proyecto') error @enderror"
                   name="nombre_proyecto"
                   placeholder="Escriba el nombre del proyecto"
                   value="{{ old('nombre_proyecto') }}"
                   required
                   maxlength="150">
            @error('nombre_proyecto')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form__group">
            <label class="group__label">Vigencia</label>
            <div style="display: flex; gap: 12px; align-items: center;">
                <div style="flex: 1;">
                    <label class="group__label" style="font-size: 0.875rem; margin-bottom: 4px;">Fecha de inicio</label>
                    <input type="date"
                           class="input @error('vigencia_inicio') error @enderror"
                           name="vigencia_inicio"
                           value="{{ old('vigencia_inicio') }}"
                           required>
                </div>
                <div style="flex: 1;">
                    <label class="group__label" style="font-size: 0.875rem; margin-bottom: 4px;">Fecha de fin</label>
                    <input type="date"
                           class="input @error('vigencia_fin') error @enderror"
                           name="vigencia_fin"
                           value="{{ old('vigencia_fin') }}"
                           required>
                </div>
            </div>
            @error('vigencia_inicio')
                <small class="error-message">{{ $message }}</small>
            @enderror
            @error('vigencia_fin')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form__group">
            <label class="group__label">Área académica de la UAN</label>
            <select class="select @error('area_academica') error @enderror" name="area_academica" required>
                <option value="">Seleccione una opción</option>
                <option value="ciencias-economicas" {{ old('area_academica') == 'ciencias-economicas' ? 'selected' : '' }}>Ciencias Económicas y Administrativas</option>
                <option value="ciencias-basicas" {{ old('area_academica') == 'ciencias-basicas' ? 'selected' : '' }}>Ciencias Básicas e Ingenierías</option>
                <option value="ciencias-biologicas" {{ old('area_academica') == 'ciencias-biologicas' ? 'selected' : '' }}>Ciencias Biológicas Agropecuarias</option>
                <option value="ciencias-salud" {{ old('area_academica') == 'ciencias-salud' ? 'selected' : '' }}>Ciencias de la Salud</option>
                <option value="ciencias-sociales" {{ old('area_academica') == 'ciencias-sociales' ? 'selected' : '' }}>Ciencias Sociales y Humanidades</option>
                <option value="artes" {{ old('area_academica') == 'artes' ? 'selected' : '' }}>Artes</option>
            </select>
            @error('area_academica')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form__group">
            <label class="group__label">Área de estudio (INEGI)</label>
            <select class="select @error('area_inegi') error @enderror" name="area_inegi" required>
                <option value="">Seleccione una opción</option>
                <option value="educacion" {{ old('area_inegi') == 'educacion' ? 'selected' : '' }}>Educación</option>
                <option value="tecnologia" {{ old('area_inegi') == 'tecnologia' ? 'selected' : '' }}>Tecnología</option>
                <option value="salud" {{ old('area_inegi') == 'salud' ? 'selected' : '' }}>Salud</option>
                <option value="ingenieria" {{ old('area_inegi') == 'ingenieria' ? 'selected' : '' }}>Ingeniería</option>
                <option value="ciencias-sociales" {{ old('area_inegi') == 'ciencias-sociales' ? 'selected' : '' }}>Ciencias Sociales</option>
                <option value="economia" {{ old('area_inegi') == 'economia' ? 'selected' : '' }}>Economía</option>
            </select>
            @error('area_inegi')
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
