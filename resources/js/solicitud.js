// Estado de la aplicación
const state = {
    currentStep: 1,
    maxReachedStep: 1,
    completedSections: new Set()
};

// Configuración
const CONFIG = {
    STEPS_PER_SECTION: { 1: [1,2,3,4,5], 2: [6], 3: [7], 4: [8], 5: [9] },
    SKIP_RULES: { 1: { field: 'sin-financiamiento', skipTo: 3 } }
};

// Utilidades
const utils = {
    getSectionFromStep: (step) => Object.keys(CONFIG.STEPS_PER_SECTION)
        .find(key => CONFIG.STEPS_PER_SECTION[key].includes(step)) || '1',

    notify: (message, type = 'success') => {
        document.querySelectorAll('.notification').forEach(n => n.remove());
        const notification = Object.assign(document.createElement('div'), {
            className: `notification notification--${type}`,
            textContent: message
        });
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 5000);
    },

    validateField: (field) => {
        const validators = {
            required: (f) => f.value.trim() !== '',
            minlength: (f) => !f.value || f.value.length >= parseInt(f.getAttribute('minlength')),
            maxlength: (f) => f.value.length <= parseInt(f.getAttribute('maxlength')),
            email: (f) => !f.value || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.value),
            pattern: (f) => !f.value || new RegExp(f.getAttribute('pattern')).test(f.value)
        };

        field.classList.remove('error');

        for (let [attr, validator] of Object.entries(validators)) {
            if (field.hasAttribute(attr) && !validator(field)) {
                field.classList.add('error');
                return false;
            }
        }
        return true;
    }
};

// Gestión de pasos
const stepManager = {
    show: (stepNumber) => {
        document.querySelectorAll('.step-content').forEach(s => s.style.display = 'none');
        document.getElementById(`step-${stepNumber}`).style.display = 'block';

        state.currentStep = stepNumber;
        state.maxReachedStep = Math.max(state.maxReachedStep, stepNumber);

        stepManager.updateIndicators();
        stepManager.updateStepCounters();
    },

    updateStepCounters: () => {
        const currentSection = parseInt(utils.getSectionFromStep(state.currentStep));
        const stepsInSection = CONFIG.STEPS_PER_SECTION[currentSection];

        // Determinar si el step 2 debe saltarse
        const skipStep2 = document.getElementById('sin-financiamiento')?.checked;

        // Para la sección 1, calcular el total real de pasos
        if (currentSection === 1) {
            const totalSteps = skipStep2 ? 4 : 5; // Si no hay financiamiento, son 4 pasos (1,3,4,5); con financiamiento son 5 (1,2,3,4,5)
            let currentStepInSection = stepsInSection.indexOf(state.currentStep) + 1;

            // Si estamos en step 3 o mayor y se saltó el step 2, ajustar el contador
            if (skipStep2 && state.currentStep >= 3) {
                currentStepInSection--;
            }

            // Actualizar todos los contadores de la sección 1
            stepsInSection.forEach(stepNum => {
                if (skipStep2 && stepNum === 2) return; // Saltar step 2 si no hay financiamiento

                const stepElement = document.getElementById(`step-${stepNum}`);
                const headerStep = stepElement?.querySelector('.header__step strong');

                if (headerStep) {
                    let stepPosition = stepsInSection.indexOf(stepNum) + 1;
                    if (skipStep2 && stepNum > 2) {
                        stepPosition--;
                    }
                    headerStep.textContent = stepPosition;
                    headerStep.nextSibling.textContent = ` de ${totalSteps}`;
                }
            });
        } else {
            // Para las demás secciones, actualizar normalmente
            const totalSteps = stepsInSection.length;

            stepsInSection.forEach((stepNum, index) => {
                const stepElement = document.getElementById(`step-${stepNum}`);
                const headerStep = stepElement?.querySelector('.header__step strong');

                if (headerStep) {
                    headerStep.textContent = index + 1;
                    headerStep.nextSibling.textContent = ` de ${totalSteps}`;
                }
            });
        }
    },

    updateIndicators: () => {
        const currentSection = parseInt(utils.getSectionFromStep(state.currentStep));

        document.querySelectorAll('.step__number').forEach((num, index) => {
            const sectionNumber = index + 1;
            num.classList.remove('step--active', 'step--completed', 'step--next-completed');

            if (state.completedSections.has(sectionNumber)) {
                num.classList.add('step--completed');
                if (state.completedSections.has(sectionNumber + 1)) {
                    num.classList.add('step--next-completed');
                }
            } else if (sectionNumber === currentSection) {
                num.classList.add('step--active');
            }
        });
    },

    validate: (stepNumber) => {
        const step = document.getElementById(`step-${stepNumber}`);
        const fields = step.querySelectorAll('[required]');
        const errors = [];

        step.querySelectorAll('.input, .select').forEach(f => f.classList.remove('error'));

        // Validación especial para step 8 (impactos)
        if (stepNumber === 8) {
            // Validar usuario específico
            const usuarioEspecifico = step.querySelector('[name="usuario_especifico"]');
            if (usuarioEspecifico && usuarioEspecifico.value.trim().length === 0) {
                errors.push('Debe completar el campo "Usuario específico de los resultados"');
                usuarioEspecifico.classList.add('error');
            }

            // Validar que al menos un impacto esté seleccionado
            const completedImpacts = step.querySelectorAll('#impact-types-select .card--completed');
            if (completedImpacts.length === 0) {
                errors.push('Debe completar al menos un tipo de impacto');
            }
        }

        fields.forEach(field => {
            if (!utils.validateField(field)) {
                const label = field.closest('.form__group')?.querySelector('.group__label')?.textContent || 'Campo';
                errors.push(`${label.replace('*', '').trim()} es requerido`);
            }
        });

        // Validar selecciones de radio
        const radioGroups = [...new Set([...step.querySelectorAll('input[type="radio"][required]')].map(r => r.name))];
        radioGroups.forEach(name => {
            if (!step.querySelector(`input[name="${name}"]:checked`)) {
                errors.push(`Debe seleccionar una opción`);
            }
        });

        // Validar cards (excepto impact-types-select que ya se validó arriba)
        step.querySelectorAll('.card-select').forEach(cs => {
            if (cs.id !== 'impact-types-select' && !cs.querySelector('.card--active') && cs.closest('.form__group').querySelector('[required]')) {
                errors.push('Debe seleccionar una opción');
            }
        });

        // Validación especial para step 9 (cronograma)
        if (stepNumber === 9) {
            if (scheduleManager.activities.length === 0) {
                errors.push('Debe agregar al menos una actividad al cronograma');
            }
        }

        if (errors.length) {
            utils.notify(errors.join('\n'), 'error');
            return false;
        }
        return true;
    },

    next: () => {
        if (!stepManager.validate(state.currentStep)) return;

        let next = state.currentStep + 1;
        const skipRule = CONFIG.SKIP_RULES[state.currentStep];

        if (skipRule && document.getElementById(skipRule.field)?.checked) {
            next = skipRule.skipTo;
        }

        const currentSection = parseInt(utils.getSectionFromStep(state.currentStep));
        const nextSection = parseInt(utils.getSectionFromStep(next));

        if (nextSection > currentSection) {
            state.completedSections.add(currentSection);
        }

        if (next <= 9) stepManager.show(next);
    },

    prev: () => {
        let prev = state.currentStep - 1;

        // Si estamos intentando regresar al step 2 pero "sin financiamiento" está marcado, saltar al step 1
        if (prev === 2 && document.getElementById('sin-financiamiento')?.checked) {
            prev = 1;
        }

        if (prev >= 1) stepManager.show(prev);
    }
};

// Gestión de cards
const cardManager = {
    init: () => {
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', function(e) {
                const group = this.closest('.card-select');

                // No hacer nada para impactos, se manejan con onclick en el HTML
                if (group && group.id === 'impact-types-select') {
                    return;
                }

                // Selección única para otros casos
                group?.querySelectorAll('.card').forEach(c => {
                    c.classList.remove('card--active');
                    c.querySelector('svg path')?.setAttribute('fill', '#656565');
                });

                this.classList.add('card--active');
                this.querySelector('svg path')?.setAttribute('fill', '#007bff');

                const hiddenInput = group?.closest('.form__group').querySelector('input[type="hidden"]');
                if (hiddenInput) {
                    hiddenInput.value = this.querySelector('.card__title')?.textContent || '';
                }
            });
        });
    }
};

// Gestión de impactos
const impactManager = {
    currentImpactType: null,

    impactTypes: {
        cientifico: {
            label: 'Impacto científico',
            placeholder: 'Describa el impacto científico esperado del proyecto (contribución al conocimiento, avances teóricos, metodológicos, etc.)'
        },
        tecnologico: {
            label: 'Impacto tecnológico',
            placeholder: 'Describa el impacto tecnológico esperado del proyecto (desarrollo de nuevas tecnologías, mejoras en procesos, innovaciones técnicas, etc.)'
        },
        social: {
            label: 'Impacto social',
            placeholder: 'Describa el impacto social esperado del proyecto (beneficios para la comunidad, mejoras en calidad de vida, desarrollo social, etc.)'
        },
        economico: {
            label: 'Impacto económico',
            placeholder: 'Describa el impacto económico esperado del proyecto (generación de valor, reducción de costos, oportunidades de negocio, etc.)'
        },
        ambiental: {
            label: 'Impacto ambiental',
            placeholder: 'Describa el impacto ambiental esperado del proyecto (sostenibilidad, conservación, reducción de impacto ecológico, etc.)'
        }
    },

    openModal: (impactType) => {
        impactManager.currentImpactType = impactType;
        const modal = document.getElementById('modal-impacto');
        const title = document.getElementById('modal-impacto-title');
        const label = document.getElementById('impact-label');
        const textarea = document.getElementById('impact-description');
        const impactInfo = impactManager.impactTypes[impactType];

        // Configurar el modal
        title.textContent = impactInfo.label;
        label.textContent = impactInfo.label;
        textarea.placeholder = impactInfo.placeholder;

        // Cargar valor existente si hay
        const existingValue = document.getElementById(`impacto_${impactType}`).value;
        textarea.value = existingValue;

        modal.style.display = 'flex';

        // Setup character counter
        const counter = textarea.parentElement.querySelector('.character-count') || document.createElement('div');
        counter.className = 'character-count';
        if (!textarea.parentElement.querySelector('.character-count')) {
            textarea.parentElement.insertBefore(counter, textarea.nextSibling.nextSibling);
        }

        const updateCounter = () => {
            const length = textarea.value.length;
            const maxLength = textarea.getAttribute('maxlength');
            counter.textContent = `${length}/${maxLength} caracteres`;
            counter.classList.toggle('warning', length > maxLength * 0.9);
            counter.classList.toggle('error', length >= maxLength);
        };

        textarea.removeEventListener('input', updateCounter);
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    },

    closeModal: () => {
        const modal = document.getElementById('modal-impacto');
        modal.style.display = 'none';
        impactManager.currentImpactType = null;
    },

    saveImpact: () => {
        const textarea = document.getElementById('impact-description');
        const value = textarea.value.trim();

        // Validar
        if (value.length === 0) {
            utils.notify('La descripción es requerida', 'error');
            textarea.classList.add('error');
            return;
        }

        if (value.length > 500) {
            utils.notify('La descripción no puede exceder 500 caracteres', 'error');
            textarea.classList.add('error');
            return;
        }

        // Guardar valor
        const hiddenInput = document.getElementById(`impacto_${impactManager.currentImpactType}`);
        hiddenInput.value = value;

        // Marcar tarjeta como completada (verde)
        const card = document.querySelector(`[data-impact="${impactManager.currentImpactType}"]`);
        card.classList.add('card--completed');
        card.querySelector('.card-check-icon').style.display = 'block';

        utils.notify('Impacto guardado exitosamente', 'success');
        impactManager.closeModal();
    }
};

// Gestión de colaboradores
const collaboratorManager = {
    add: (type) => {
        const modal = document.getElementById(`modal-${type}`);
        modal.querySelector('.modal-form').reset();
        modal.querySelectorAll('.card').forEach((c, i) => {
            c.classList.toggle('card--active', i === 0);
        });
        modal.style.display = 'flex';
    },

    save: (type) => {
        const modal = document.getElementById(`modal-${type}`);
        if (!collaboratorManager.validate(modal)) return;

        const data = collaboratorManager.collectData(modal, type);
        const list = document.querySelector(`#step-5 .form__group:${type === 'profesor' ? 'first-child' : 'nth-child(2)'} .collaborator-list`);

        list.appendChild(collaboratorManager.createElement(data));
        if (type === 'estudiante') list.style.display = 'flex';

        utils.notify(`${type === 'profesor' ? 'Profesor' : 'Estudiante'} agregado exitosamente`);
        modal.style.display = 'none';
    },

    collectData: (modal, type) => {
        const inputs = modal.querySelectorAll('.input');
        return {
            nombre: inputs[0].value,
            actividad: inputs[1].value,
            grado: modal.querySelector('.card--active .card__title').textContent,
            ...(type === 'estudiante' && { tipoFormacion: modal.querySelector('.select').value })
        };
    },

    createElement: (data) => {
        const item = document.createElement('div');
        item.className = 'collaborator-item';
        item.innerHTML = `
            <div class="collaborator-avatar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" fill="#6B7280"/>
                    <path d="M12 14C7.58172 14 4 17.5817 4 22H20C20 17.5817 16.4183 14 12 14Z" fill="#6B7280"/>
                </svg>
            </div>
            <div class="collaborator-info">
                <div class="collaborator-name">${data.nombre}</div>
                <div class="collaborator-role">${data.grado} - ${data.actividad}${data.tipoFormacion ? ` (${data.tipoFormacion})` : ''}</div>
            </div>
            <button type="button" class="btn-remove" onclick="collaboratorManager.remove(this)">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M12 4L4 12M4 4L12 12" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        `;
        return item;
    },

    remove: (btn) => {
        const item = btn.closest('.collaborator-item');
        const list = item.closest('.collaborator-list');

        if (list.querySelectorAll('.collaborator-item').length <= 1 &&
            list.closest('.form__group:first-child')) {
            utils.notify('Debe mantener al menos un profesor', 'error');
            return;
        }

        if (confirm('¿Eliminar colaborador?')) {
            item.remove();
            if (list.children.length === 0) list.style.display = 'none';
            utils.notify('Colaborador eliminado', 'success');
        }
    },

    validate: (modal) => {
        const fields = modal.querySelectorAll('[required]');
        const errors = [];

        fields.forEach(field => {
            field.classList.remove('error');
            if (!utils.validateField(field)) {
                field.classList.add('error');
                errors.push(`El campo "${field.previousElementSibling?.textContent || field.getAttribute('placeholder') || 'requerido'}" es obligatorio`);
            }
        });

        const activeCard = modal.querySelector('.card--active');
        if (!activeCard) {
            errors.push('Debe seleccionar un grado académico');
        }

        if (errors.length) {
            utils.notify(errors[0], 'error');
            return false;
        }
        return true;
    }
};

// Gestión de cronograma
const scheduleManager = {
    activities: [],
    editingIndex: null,
    currentModalStep: 1,
    maxYears: 2,
    currentPage: 1,
    activitiesPerPage: 3,

    init: () => {
        // Inicializar los botones de trimestres
        document.querySelectorAll('.quarter-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.toggle('active');
            });
        });

        // Manejar el envío del formulario de actividad
        document.getElementById('activity-form')?.addEventListener('submit', (e) => {
            e.preventDefault();
            scheduleManager.saveActivity();
        });

        // Cargar actividades por defecto si existen
        scheduleManager.renderActivities();
    },

    openModal: (index = null) => {
        const modal = document.getElementById('activity-modal');
        const form = document.getElementById('activity-form');
        const title = document.getElementById('modal-title');

        // Reset del formulario
        form.reset();
        scheduleManager.currentModalStep = 1;
        scheduleManager.updateModalStep();
        document.querySelectorAll('.quarter-btn').forEach(btn => btn.classList.remove('active'));

        if (index !== null) {
            // Modo edición
            scheduleManager.editingIndex = index;
            const activity = scheduleManager.activities[index];

            title.textContent = 'Editar actividad';
            document.getElementById('activity-name').value = activity.name;
            document.getElementById('activity-description').value = activity.description;

            // Marcar trimestres seleccionados
            activity.quarters.forEach(q => {
                document.querySelector(`.quarter-btn[data-quarter="${q}"]`)?.classList.add('active');
            });
        } else {
            // Modo creación
            scheduleManager.editingIndex = null;
            title.textContent = 'Agregar actividad';
        }

        modal.style.display = 'flex';
    },

    closeModal: () => {
        document.getElementById('activity-modal').style.display = 'none';
        scheduleManager.editingIndex = null;
        scheduleManager.currentModalStep = 1;
        scheduleManager.maxYears = 2;
        // Ocultar años 3 y 4
        document.querySelectorAll('.quarters-year-group[data-year]').forEach(group => {
            const year = parseInt(group.dataset.year);
            if (year > 2) {
                group.style.display = 'none';
            }
        });
        // Mostrar botón de agregar trimestres
        const addBtn = document.getElementById('add-quarters-btn');
        if (addBtn) addBtn.style.display = 'flex';
    },

    updateModalStep: () => {
        const totalSteps = 3;

        // Actualizar contenido visible
        document.querySelectorAll('.modal-step-content').forEach((content, index) => {
            content.classList.toggle('modal-step-content--active', index + 1 === scheduleManager.currentModalStep);
        });

        // Actualizar indicadores de paso
        document.querySelectorAll('.modal-step').forEach((step, index) => {
            const stepNum = index + 1;
            step.classList.remove('modal-step--active', 'modal-step--completed');

            if (stepNum < scheduleManager.currentModalStep) {
                step.classList.add('modal-step--completed');
            } else if (stepNum === scheduleManager.currentModalStep) {
                step.classList.add('modal-step--active');
            }
        });

        // Actualizar divisores
        document.querySelectorAll('.modal-step-divider').forEach((divider, index) => {
            divider.classList.toggle('completed', index + 1 < scheduleManager.currentModalStep);
        });

        // Actualizar botones
        const prevBtn = document.getElementById('modal-prev-btn');
        const nextBtn = document.getElementById('modal-next-btn');
        const submitBtn = document.getElementById('modal-submit-btn');

        prevBtn.style.display = scheduleManager.currentModalStep === 1 ? 'none' : 'flex';
        nextBtn.style.display = scheduleManager.currentModalStep === totalSteps ? 'none' : 'flex';
        submitBtn.style.display = scheduleManager.currentModalStep === totalSteps ? 'block' : 'none';
    },

    nextModalStep: () => {
        const totalSteps = 3;

        // Validar paso actual
        if (scheduleManager.currentModalStep === 1) {
            const nameField = document.getElementById('activity-name');
            const name = nameField.value.trim();
            if (!name) {
                nameField.classList.add('error');
                utils.notify('Por favor ingresa el nombre de la actividad', 'error');
                nameField.focus();
                return;
            }
            if (name.length < 5) {
                nameField.classList.add('error');
                utils.notify('El nombre debe tener al menos 5 caracteres', 'error');
                nameField.focus();
                return;
            }
            nameField.classList.remove('error');
        } else if (scheduleManager.currentModalStep === 2) {
            const descField = document.getElementById('activity-description');
            const description = descField.value.trim();
            if (!description) {
                descField.classList.add('error');
                utils.notify('Por favor ingresa la descripción de la actividad', 'error');
                descField.focus();
                return;
            }
            if (description.length < 10) {
                descField.classList.add('error');
                utils.notify('La descripción debe tener al menos 10 caracteres', 'error');
                descField.focus();
                return;
            }
            descField.classList.remove('error');
        }

        if (scheduleManager.currentModalStep < totalSteps) {
            scheduleManager.currentModalStep++;
            scheduleManager.updateModalStep();
        }
    },

    prevModalStep: () => {
        if (scheduleManager.currentModalStep > 1) {
            scheduleManager.currentModalStep--;
            scheduleManager.updateModalStep();
        }
    },

    saveActivity: () => {
        const name = document.getElementById('activity-name').value.trim();
        const description = document.getElementById('activity-description').value.trim();
        const selectedQuarters = [...document.querySelectorAll('.quarter-btn.active')]
            .map(btn => parseInt(btn.dataset.quarter))
            .sort((a, b) => a - b);

        // Validación
        if (!name || !description) {
            utils.notify('Por favor completa todos los campos', 'error');
            return;
        }

        if (selectedQuarters.length === 0) {
            utils.notify('Selecciona al menos un trimestre', 'error');
            return;
        }

        const activity = {
            name,
            description,
            quarters: selectedQuarters
        };

        if (scheduleManager.editingIndex !== null) {
            // Actualizar actividad existente
            scheduleManager.activities[scheduleManager.editingIndex] = activity;
            utils.notify('Actividad actualizada exitosamente', 'success');
        } else {
            // Agregar nueva actividad
            scheduleManager.activities.push(activity);
            utils.notify('Actividad agregada exitosamente', 'success');
        }

        scheduleManager.renderActivities();
        scheduleManager.closeModal();

        // Actualizar botón enviar
        if (typeof workPlanManager !== 'undefined') {
            workPlanManager.updateSubmitButton();
        }
    },

    removeActivity: (index) => {
        if (confirm('¿Estás seguro de eliminar esta actividad?')) {
            scheduleManager.activities.splice(index, 1);

            // Ajustar la página actual si es necesario
            const totalPages = Math.ceil(scheduleManager.activities.length / scheduleManager.activitiesPerPage);
            if (scheduleManager.currentPage > totalPages && totalPages > 0) {
                scheduleManager.currentPage = totalPages;
            } else if (scheduleManager.activities.length === 0) {
                scheduleManager.currentPage = 1;
            }

            scheduleManager.renderActivities();
            utils.notify('Actividad eliminada', 'success');

            // Actualizar botón enviar
            if (typeof workPlanManager !== 'undefined') {
                workPlanManager.updateSubmitButton();
            }
        }
    },

    addMoreQuarters: () => {
        if (scheduleManager.maxYears >= 4) {
            utils.notify('Ya has alcanzado el máximo de 4 años', 'warning');
            return;
        }

        scheduleManager.maxYears++;

        // Mostrar el grupo de año correspondiente
        const yearGroup = document.querySelector(`.quarters-year-group[data-year="${scheduleManager.maxYears}"]`);
        if (yearGroup) {
            yearGroup.style.display = 'flex';
        }

        // Ocultar botón y mostrar aviso si llegamos al máximo
        if (scheduleManager.maxYears >= 4) {
            document.getElementById('add-quarters-btn').style.display = 'none';
            document.getElementById('extra-time-notice').style.display = 'flex';
        }

        utils.notify(`Año ${scheduleManager.maxYears} agregado`, 'success');
    },

    getQuartersRange: (quarters) => {
        if (quarters.length === 0) return '';
        if (quarters.length === 1) return `Trimestre ${quarters[0]}`;

        const min = Math.min(...quarters);
        const max = Math.max(...quarters);

        if (max - min + 1 === quarters.length) {
            // Son consecutivos
            return `Trimestre ${min} - ${max}`;
        } else {
            // No son consecutivos, mostrar individual
            return quarters.map(q => q).join(', ');
        }
    },

    renderActivities: () => {
        const list = document.getElementById('activities-list');
        const emptyState = document.getElementById('empty-state');
        const paginationContainer = document.getElementById('activities-pagination');
        const paginationInfo = document.getElementById('pagination-info');
        const prevBtn = document.getElementById('prev-page-btn');
        const nextBtn = document.getElementById('next-page-btn');

        if (scheduleManager.activities.length === 0) {
            emptyState.style.display = 'flex';
            paginationContainer.style.display = 'none';
            list.querySelectorAll('.activity-card').forEach(card => card.remove());
            return;
        }

        emptyState.style.display = 'none';

        // Determinar si se necesita paginación (a partir de la 3era actividad)
        const needsPagination = scheduleManager.activities.length > scheduleManager.activitiesPerPage;

        // Calcular índices para la página actual
        const startIndex = (scheduleManager.currentPage - 1) * scheduleManager.activitiesPerPage;
        const endIndex = startIndex + scheduleManager.activitiesPerPage;
        const activitiesToShow = needsPagination
            ? scheduleManager.activities.slice(startIndex, endIndex)
            : scheduleManager.activities;

        // Limpiar lista actual
        list.querySelectorAll('.activity-card').forEach(card => card.remove());

        // Renderizar actividades de la página actual
        activitiesToShow.forEach((activity, displayIndex) => {
            const actualIndex = needsPagination ? startIndex + displayIndex : displayIndex;
            const card = document.createElement('div');
            card.className = 'activity-card';
            card.innerHTML = `
                <div class="activity-card-header">
                    <h3 class="activity-card-title">${activity.name}</h3>
                    <div class="activity-card-actions">
                        <button type="button" class="btn-edit-activity" onclick="scheduleManager.openModal(${actualIndex})" title="Editar actividad">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.333 2.00004C11.5081 1.82494 11.716 1.68605 11.9447 1.59129C12.1735 1.49653 12.4187 1.44775 12.6663 1.44775C12.914 1.44775 13.1592 1.49653 13.3879 1.59129C13.6167 1.68605 13.8246 1.82494 13.9997 2.00004C14.1748 2.17513 14.3137 2.383 14.4084 2.61178C14.5032 2.84055 14.552 3.08575 14.552 3.33337C14.552 3.58099 14.5032 3.82619 14.4084 4.05497C14.3137 4.28374 14.1748 4.49161 13.9997 4.66671L4.99967 13.6667L1.33301 14.6667L2.33301 11L11.333 2.00004Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button type="button" class="btn-remove-activity" onclick="scheduleManager.removeActivity(${actualIndex})" title="Eliminar actividad">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="activity-card-description">${activity.description}</p>
                <div class="activity-card-quarters">
                    <span class="quarters-label">Periodo:</span>
                    <div class="quarters-badges">
                        ${activity.quarters.map(q => `<span class="quarter-badge">${q}</span>`).join('')}
                    </div>
                </div>
            `;
            list.appendChild(card);
        });

        // Mostrar/ocultar paginación
        if (needsPagination) {
            const totalPages = Math.ceil(scheduleManager.activities.length / scheduleManager.activitiesPerPage);
            paginationContainer.style.display = 'flex';
            paginationInfo.textContent = `Página ${scheduleManager.currentPage} de ${totalPages}`;

            // Habilitar/deshabilitar botones
            prevBtn.disabled = scheduleManager.currentPage === 1;
            nextBtn.disabled = scheduleManager.currentPage === totalPages;
        } else {
            paginationContainer.style.display = 'none';
        }

        // Actualizar botón enviar
        if (typeof workPlanManager !== 'undefined' && workPlanManager.updateSubmitButton) {
            workPlanManager.updateSubmitButton();
        }
    },

    nextPage: () => {
        const totalPages = Math.ceil(scheduleManager.activities.length / scheduleManager.activitiesPerPage);
        if (scheduleManager.currentPage < totalPages) {
            scheduleManager.currentPage++;
            scheduleManager.renderActivities();
        }
    },

    prevPage: () => {
        if (scheduleManager.currentPage > 1) {
            scheduleManager.currentPage--;
            scheduleManager.renderActivities();
        }
    }
};

// Gestión de plan de trabajo
const workPlanManager = {
    selectedFile: null,
    uploadedFile: null,

    openModal: () => {
        document.getElementById('work-plan-modal').style.display = 'flex';
        workPlanManager.resetForm();
    },

    closeModal: () => {
        document.getElementById('work-plan-modal').style.display = 'none';
        workPlanManager.resetForm();
    },

    resetForm: () => {
        workPlanManager.selectedFile = null;
        document.getElementById('work-plan-file').value = '';
        document.getElementById('upload-placeholder').style.display = 'block';
        document.getElementById('file-selected').style.display = 'none';
        document.getElementById('save-work-plan-btn').disabled = true;
    },

    handleFileSelect: (event) => {
        const file = event.target.files[0];

        if (!file) return;

        // Validar tipo de archivo
        const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!validTypes.includes(file.type)) {
            utils.notify('Por favor selecciona un archivo PDF, DOC o DOCX', 'error');
            return;
        }

        // Validar tamaño (10MB)
        if (file.size > 10 * 1024 * 1024) {
            utils.notify('El archivo no debe superar los 10MB', 'error');
            return;
        }

        workPlanManager.selectedFile = file;

        // Actualizar UI
        document.getElementById('upload-placeholder').style.display = 'none';
        document.getElementById('file-selected').style.display = 'flex';
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-size').textContent = workPlanManager.formatFileSize(file.size);
        document.getElementById('save-work-plan-btn').disabled = false;
    },

    removeFile: () => {
        workPlanManager.resetForm();
    },

    formatFileSize: (bytes) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    },

    save: () => {
        if (!workPlanManager.selectedFile) {
            utils.notify('Por favor selecciona un archivo', 'error');
            return;
        }

        // Guardar el archivo subido
        workPlanManager.uploadedFile = workPlanManager.selectedFile;

        // Cerrar ambos modales
        workPlanManager.closeModal();
        scheduleManager.closeModal();

        // Mostrar el documento en el step 9
        workPlanManager.displayUploadedFile();

        // Habilitar el botón enviar
        workPlanManager.updateSubmitButton();

        utils.notify('Plan de trabajo guardado exitosamente', 'success');
    },

    displayUploadedFile: () => {
        const activitiesList = document.getElementById('activities-list');
        const emptyState = document.getElementById('empty-state');

        // Ocultar estado vacío
        if (emptyState) {
            emptyState.style.display = 'none';
        }

        // Verificar si ya existe la sección del documento
        let workPlanSection = document.getElementById('work-plan-uploaded-section');

        if (!workPlanSection) {
            // Crear la sección del documento
            workPlanSection = document.createElement('div');
            workPlanSection.id = 'work-plan-uploaded-section';
            workPlanSection.className = 'work-plan-uploaded';
            workPlanSection.innerHTML = `
                <div class="work-plan-header">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V9L13 2Z" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13 2V9H20" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3>Plan de trabajo extendido</h3>
                </div>
                <div class="work-plan-content">
                    <div class="work-plan-info">
                        <div class="work-plan-badge">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22 11.08V12C21.9988 14.1564 21.3005 16.2547 20.0093 17.9818C18.7182 19.709 16.9033 20.9725 14.8354 21.5839C12.7674 22.1953 10.5573 22.1219 8.53447 21.3746C6.51168 20.6273 4.78465 19.2461 3.61096 17.4371C2.43727 15.628 1.87979 13.4881 2.02168 11.3363C2.16356 9.18455 2.99721 7.13631 4.39828 5.49706C5.79935 3.85781 7.69279 2.71537 9.79619 2.24013C11.8996 1.7649 14.1003 1.98232 16.07 2.85999" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M22 4L12 14.01L9 11.01" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Documento subido
                        </div>
                        <p class="work-plan-filename" id="uploaded-file-name"></p>
                        <p class="work-plan-filesize" id="uploaded-file-size"></p>
                    </div>
                    <button type="button" class="btn-remove-work-plan" onclick="workPlanManager.removeUploadedFile()" title="Eliminar documento">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4L4 12M4 4L12 12" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            `;
            activitiesList.insertBefore(workPlanSection, activitiesList.firstChild);
        }

        // Actualizar la información del archivo
        document.getElementById('uploaded-file-name').textContent = workPlanManager.uploadedFile.name;
        document.getElementById('uploaded-file-size').textContent = workPlanManager.formatFileSize(workPlanManager.uploadedFile.size);
    },

    removeUploadedFile: () => {
        if (confirm('¿Estás seguro de que deseas eliminar el plan de trabajo?')) {
            workPlanManager.uploadedFile = null;

            // Eliminar la sección del documento
            const workPlanSection = document.getElementById('work-plan-uploaded-section');
            if (workPlanSection) {
                workPlanSection.remove();
            }

            // Mostrar estado vacío si no hay actividades ni documento
            const activitiesList = document.getElementById('activities-list');
            const hasActivities = activitiesList.querySelector('.activity-item');
            if (!hasActivities) {
                const emptyState = document.getElementById('empty-state');
                if (emptyState) {
                    emptyState.style.display = 'flex';
                }
            }

            // Actualizar botón enviar
            workPlanManager.updateSubmitButton();

            utils.notify('Plan de trabajo eliminado', 'success');
        }
    },

    updateSubmitButton: () => {
        const hasActivities = scheduleManager.activities.length > 0;
        const hasWorkPlan = workPlanManager.uploadedFile !== null;

        // Habilitar botón si hay actividades O si hay documento de plan de trabajo
        const submitBtn = document.querySelector('#step-9 .btn--primary.has-icon');
        if (submitBtn) {
            if (hasActivities || hasWorkPlan) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            } else {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
            }
        }
    }
};

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar gestores
    cardManager.init();
    scheduleManager.init();
    protocolFileManager.init();
    stepManager.show(1);

    // Inicializar el botón enviar como deshabilitado
    workPlanManager.updateSubmitButton();

    // Sincronizar contadores con inputs hidden al cargar la página
    ['articulos-indexada', 'articulos-arbitrada', 'libros', 'capitulo-libro',
     'memorias-congreso', 'tesis', 'material-didactico'].forEach(id => {
        const counter = document.getElementById(id);
        const inputId = id.replace(/-/g, '_') + '_input';
        const input = document.getElementById(inputId);
        if (counter && input) {
            const value = parseInt(counter.textContent) || 0;
            input.value = value;
        }
    });

    // Event listeners
    document.querySelectorAll('.step__number').forEach((num, i) => {
        num.addEventListener('click', () => {
            if (i + 1 <= parseInt(utils.getSectionFromStep(state.maxReachedStep))) {
                stepManager.show(CONFIG.STEPS_PER_SECTION[i + 1][0]);
            }
        });
    });

    document.querySelectorAll('.input, .select').forEach(field => {
        field.addEventListener('blur', () => utils.validateField(field));
        field.addEventListener('input', () => field.classList.remove('error'));
    });

    // Setup character counters for all textareas with maxlength
    document.querySelectorAll('textarea[maxlength]').forEach(textarea => {
        const counter = document.createElement('div');
        counter.className = 'character-count';
        const updateCounter = () => {
            const current = textarea.value.length;
            const max = parseInt(textarea.getAttribute('maxlength'));
            const remaining = max - current;
            counter.textContent = `${current}/${max}`;
            counter.className = 'character-count';
            if (remaining < 50) counter.classList.add('warning');
            if (remaining < 10) counter.classList.add('error');
        };
        textarea.addEventListener('input', updateCounter);
        textarea.parentElement.appendChild(counter);
        updateCounter();
    });

    // Setup character counters for inputs with maxlength
    document.querySelectorAll('input[type="text"][maxlength], input:not([type])[maxlength]').forEach(input => {
        const maxLength = parseInt(input.getAttribute('maxlength'));
        if (maxLength >= 50) { // Only show counter for longer inputs
            const counter = document.createElement('div');
            counter.className = 'character-count';
            const updateCounter = () => {
                const current = input.value.length;
                const remaining = maxLength - current;
                counter.textContent = `${current}/${maxLength}`;
                counter.className = 'character-count';
                if (remaining < 20) counter.classList.add('warning');
                if (remaining < 5) counter.classList.add('error');
            };
            input.addEventListener('input', updateCounter);
            input.parentElement.appendChild(counter);
            updateCounter();
        }
    });

    // Agregar evento a inputs de dinero existentes
    const setupMoneyInput = (input) => {
        let isFormatted = false;

        input.addEventListener('input', (e) => {
            if (isFormatted) return;

            // Remover todo excepto números y punto decimal
            let value = e.target.value.replace(/[^\d.]/g, '');

            // Asegurar solo un punto decimal
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts[1];
            }

            // Limitar decimales a 2
            if (parts[1] && parts[1].length > 2) {
                value = parts[0] + '.' + parts[1].substring(0, 2);
            }

            e.target.value = value;
        });

        input.addEventListener('blur', (e) => {
            let value = e.target.value.replace(/[^\d.]/g, '');

            if (value) {
                const number = parseFloat(value);
                if (!isNaN(number)) {
                    isFormatted = true;
                    e.target.value = '$ ' + number.toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' MXN';
                    setTimeout(() => isFormatted = false, 100);
                }
            }
        });

        input.addEventListener('focus', (e) => {
            // Al hacer focus, mostrar solo el número
            let value = e.target.value.replace(/[^\d.]/g, '');
            if (value) {
                e.target.value = value;
            }
        });

        input.addEventListener('keydown', (e) => {
            // Permitir: backspace, delete, tab, escape, enter, punto decimal
            if ([46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
                // Permitir: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true) ||
                // Permitir: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            // Solo permitir números
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    };

    // Financiamiento dinámico
    const renderFinanciamientoFields = (index) => {
        const optionHTML = document.querySelector('#option-html');
        const templates = [
            '',
            '<div class="form__group"><label class="group__label">Unidad académica aportante</label><input type="text" class="input" name="unidad_aportante" required maxlength="200" placeholder="Escriba la unidad académica"></div><div class="form__group"><label class="group__label">Monto aprobado</label><input type="text" class="input money-input" name="monto_aprobado" required placeholder="$ 0.00 MXN"></div>',
            '<div class="form__group"><label class="group__label">Institución aportante</label><input type="text" class="input" name="institucion_aportante" required maxlength="200" placeholder="Escriba la institución"></div><div class="form__group"><label class="group__label">Monto aprobado</label><input type="text" class="input money-input" name="monto_aprobado" required placeholder="$ 0.00 MXN"></div>'
        ];
        optionHTML.innerHTML = templates[index] || '';

        // Agregar formato a los nuevos inputs de dinero
        optionHTML.querySelectorAll('.money-input').forEach(input => {
            setupMoneyInput(input);
        });

        // Actualizar contadores cuando cambie la opción de financiamiento
        stepManager.updateStepCounters();
    };

    document.querySelectorAll('[name="financiamiento"]').forEach((radio, i) => {
        radio.addEventListener('click', () => {
            renderFinanciamientoFields(i);
        });
    });

    // Verificar si hay un radio seleccionado al cargar (para recuperación de formulario)
    const selectedFinanciamiento = document.querySelector('[name="financiamiento"]:checked');
    if (selectedFinanciamiento) {
        const index = Array.from(document.querySelectorAll('[name="financiamiento"]')).indexOf(selectedFinanciamiento);
        renderFinanciamientoFields(index);
    }

    // Modal close
    window.onclick = (e) => {
        if (e.target.classList.contains('modal')) e.target.style.display = 'none';
    };
});

// Funciones globales necesarias
window.nextStep = () => stepManager.next();
window.prevStep = () => stepManager.prev();
window.addCollaborator = (type) => {
    if (type === 'profesor') {
        // Abrir el modal con la vista de búsqueda
        const modal = document.getElementById('modal-profesor');
        modal.style.display = 'flex';
        profesorManager.showSearchView();
    } else {
        collaboratorManager.add(type);
    }
};
window.removeCollaborator = (btn) => collaboratorManager.remove(btn);
window.addStudent = () => collaboratorManager.save('estudiante');
window.addProfessor = () => profesorManager.register();
window.collaboratorManager = collaboratorManager;
window.scheduleManager = scheduleManager;
window.openActivityModal = () => scheduleManager.openModal();
window.closeActivityModal = () => scheduleManager.closeModal();
window.nextModalStep = () => scheduleManager.nextModalStep();
window.prevModalStep = () => scheduleManager.prevModalStep();
window.addMoreQuarters = () => scheduleManager.addMoreQuarters();
window.openWorkPlanUpload = () => workPlanManager.openModal();
window.closeWorkPlanUpload = () => workPlanManager.closeModal();
window.handleFileSelect = (event) => workPlanManager.handleFileSelect(event);
window.removeFile = () => workPlanManager.removeFile();
window.saveWorkPlan = () => workPlanManager.save();
window.workPlanManager = workPlanManager;
window.openImpactModal = (type) => impactManager.openModal(type);
window.closeImpactModal = () => impactManager.closeModal();
window.saveImpact = () => impactManager.saveImpact();
window.closeModal = (id) => document.getElementById(id).style.display = 'none';
window.incrementCounter = (id) => {
    const el = document.getElementById(id);
    const newValue = parseInt(el.textContent) + 1;
    el.textContent = newValue;

    // Sincronizar con input hidden (convertir guiones a guiones bajos)
    const inputId = id.replace(/-/g, '_') + '_input';
    const hiddenInput = document.getElementById(inputId);
    if (hiddenInput) {
        hiddenInput.value = newValue;
    }
};
window.decrementCounter = (id) => {
    const el = document.getElementById(id);
    const val = parseInt(el.textContent);
    if (val > 0) {
        const newValue = val - 1;
        el.textContent = newValue;

        // Sincronizar con input hidden (convertir guiones a guiones bajos)
        const inputId = id.replace(/-/g, '_') + '_input';
        const hiddenInput = document.getElementById(inputId);
        if (hiddenInput) {
            hiddenInput.value = newValue;
        }
    }
};

window.submitForm = () => {
    if (!stepManager.validate(state.currentStep)) return;

    // Recolectar colaboradores
    const colaboradores = {
        profesores: [],
        estudiantes: []
    };

    document.querySelectorAll('.collaborator-card').forEach(card => {
        const tipo = card.getAttribute('data-type');
        const nombre = card.querySelector('.card__name')?.textContent || '';
        const role = card.querySelector('.card__role')?.textContent || '';

        if (tipo === 'profesor') {
            colaboradores.profesores.push({ nombre, role });
        } else if (tipo === 'estudiante') {
            colaboradores.estudiantes.push({ nombre, role });
        }
    });

    // Crear input hidden para colaboradores
    let colaboradoresInput = document.getElementById('colaboradores_input');
    if (!colaboradoresInput) {
        colaboradoresInput = document.createElement('input');
        colaboradoresInput.type = 'hidden';
        colaboradoresInput.id = 'colaboradores_input';
        colaboradoresInput.name = 'colaboradores';
        document.getElementById('project-form').appendChild(colaboradoresInput);
    }
    colaboradoresInput.value = JSON.stringify(colaboradores);

    // Recolectar cronograma
    if (scheduleManager.activities && scheduleManager.activities.length > 0) {
        let cronogramaInput = document.getElementById('cronograma_input');
        if (!cronogramaInput) {
            cronogramaInput = document.createElement('input');
            cronogramaInput.type = 'hidden';
            cronogramaInput.id = 'cronograma_input';
            cronogramaInput.name = 'cronograma';
            document.getElementById('project-form').appendChild(cronogramaInput);
        }
        cronogramaInput.value = JSON.stringify(scheduleManager.activities);
    }

    state.completedSections.add(5);
    stepManager.updateIndicators();

    utils.notify('Enviando información del proyecto...', 'success');
    document.getElementById('project-form')?.submit();
};

window.cancelAndClearForm = () => {
    if (confirm('¿Estás seguro de que deseas cancelar y borrar todos los datos del formulario? Esta acción no se puede deshacer.')) {
        // Resetear el formulario principal
        document.getElementById('project-form').reset();

        // Limpiar colaboradores
        document.querySelectorAll('.collaborator-list').forEach(list => {
            list.innerHTML = '';
        });

        // Limpiar actividades del cronograma
        scheduleManager.activities = [];
        scheduleManager.currentPage = 1;
        scheduleManager.renderActivities();

        // Limpiar impactos
        document.querySelectorAll('[id^="impacto_"]').forEach(input => {
            input.value = '';
        });

        // Remover clases de cards completadas
        document.querySelectorAll('.card--completed').forEach(card => {
            card.classList.remove('card--completed');
            const checkIcon = card.querySelector('.card-check-icon');
            if (checkIcon) checkIcon.style.display = 'none';
        });

        // Remover clases de cards activas
        document.querySelectorAll('.card--active').forEach(card => {
            card.classList.remove('card--active');
        });

        // Resetear contadores de entregables
        document.querySelectorAll('[id$="-counter"]').forEach(counter => {
            counter.textContent = '0';
        });

        // Resetear estado de la aplicación
        state.currentStep = 1;
        state.maxReachedStep = 1;
        state.completedSections.clear();

        // Volver al primer paso
        stepManager.show(1);

        // Mostrar notificación
        utils.notify('Formulario borrado exitosamente', 'success');

        // Scroll hacia arriba
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};

// Gestión de búsqueda y registro de profesores
const profesorManager = {
    searchTimeout: null,

    showSearchView: () => {
        document.getElementById('profesor-search-view').style.display = 'block';
        document.getElementById('profesor-registration-view').style.display = 'none';
        document.getElementById('profesor-search-input').value = '';
        document.getElementById('profesor-search-results').style.display = 'none';
    },

    showRegistrationView: () => {
        document.getElementById('profesor-search-view').style.display = 'none';
        document.getElementById('profesor-registration-view').style.display = 'block';
    },

    search: (query) => {
        clearTimeout(profesorManager.searchTimeout);

        const resultsContainer = document.getElementById('profesor-search-results');

        if (query.length < 2) {
            resultsContainer.style.display = 'none';
            return;
        }

        profesorManager.searchTimeout = setTimeout(() => {
            fetch(`/api/profesores/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    profesorManager.displayResults(data);
                })
                .catch(error => {
                    console.error('Error buscando profesores:', error);
                    resultsContainer.innerHTML = '<div class="search-results-empty">Error al buscar profesores</div>';
                    resultsContainer.style.display = 'block';
                });
        }, 300);
    },

    displayResults: (profesores) => {
        const resultsContainer = document.getElementById('profesor-search-results');

        if (profesores.length === 0) {
            resultsContainer.innerHTML = '<div class="search-results-empty">No se encontraron profesores. Puede registrar uno nuevo.</div>';
            resultsContainer.style.display = 'block';
            return;
        }

        resultsContainer.innerHTML = profesores.map(profesor => `
            <div class="search-result-item" onclick="profesorManager.select(${profesor.id}, '${profesor.nombre.replace(/'/g, "\\'")}', '${profesor.grado}', '${profesor.actividad}')">
                <div class="search-result-info">
                    <div class="search-result-name">${profesor.nombre}</div>
                    <div class="search-result-details">${profesor.grado} - ${profesor.actividad}</div>
                </div>
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.5 15L12.5 10L7.5 5" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        `).join('');

        resultsContainer.style.display = 'block';
    },

    select: (id, nombre, grado, actividad) => {
        // Usar el collaboratorManager existente para agregar a la lista
        const data = {
            id: id,
            nombre: nombre,
            grado: grado,
            actividad: actividad
        };

        const list = document.querySelector('#step-5 .form__group:first-child .collaborator-list');
        list.appendChild(collaboratorManager.createElement(data));

        utils.notify('Profesor agregado exitosamente', 'success');
        closeModal('modal-profesor');
        profesorManager.showSearchView();
    },

    register: () => {
        const modal = document.getElementById('modal-profesor');

        // Validar usando la función existente del collaboratorManager
        if (!collaboratorManager.validate(modal)) {
            return;
        }

        const form = document.getElementById('profesor-registration-form');
        const formData = new FormData(form);

        fetch('/api/profesores/register', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Usar el collaboratorManager existente
                const list = document.querySelector('#step-5 .form__group:first-child .collaborator-list');
                list.appendChild(collaboratorManager.createElement({
                    id: data.profesor.id,
                    nombre: data.profesor.nombre,
                    grado: data.profesor.grado,
                    actividad: data.profesor.actividad
                }));

                utils.notify('Profesor registrado y agregado exitosamente', 'success');
                closeModal('modal-profesor');
                form.reset();
                profesorManager.showSearchView();
            } else {
                utils.notify('Error al registrar el profesor', 'error');
            }
        })
        .catch(error => {
            console.error('Error registrando profesor:', error);
            utils.notify('Error al registrar el profesor', 'error');
        });
    }
};

// Gestión de archivo de protocolo (Step 6)
const protocolFileManager = {
    init: () => {
        const fileInput = document.getElementById('protocolo_investigacion');
        const uploadArea = document.getElementById('file-upload-area-step6');
        const placeholder = document.getElementById('upload-placeholder-step6');
        const fileSelected = document.getElementById('file-selected-step6');

        if (!fileInput || !uploadArea) return;

        // Click en el área para abrir selector
        uploadArea.addEventListener('click', (e) => {
            if (e.target.closest('.btn-icon-danger')) return;
            if (!fileSelected.style.display || fileSelected.style.display === 'none') {
                fileInput.click();
            }
        });

        // Cambio de archivo
        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                protocolFileManager.handleFile(file);
            }
        });

        // Drag & Drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#3B82F6';
            uploadArea.style.background = '#EFF6FF';
        });

        uploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#D1D5DB';
            uploadArea.style.background = '#FFFFFF';
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#D1D5DB';
            uploadArea.style.background = '#FFFFFF';

            const file = e.dataTransfer.files[0];
            if (file) {
                // Validar tipo de archivo
                const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!validTypes.includes(file.type)) {
                    utils.notify('Por favor selecciona un archivo PDF, DOC o DOCX', 'error');
                    return;
                }

                // Asignar archivo al input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                protocolFileManager.handleFile(file);
            }
        });
    },

    handleFile: (file) => {
        const placeholder = document.getElementById('upload-placeholder-step6');
        const fileSelected = document.getElementById('file-selected-step6');
        const fileName = document.getElementById('file-name-step6');
        const fileSize = document.getElementById('file-size-step6');

        // Validar tamaño (10MB)
        if (file.size > 10 * 1024 * 1024) {
            utils.notify('El archivo no debe exceder 10MB', 'error');
            return;
        }

        // Mostrar información del archivo
        fileName.textContent = file.name;
        fileSize.textContent = protocolFileManager.formatFileSize(file.size);

        placeholder.style.display = 'none';
        fileSelected.style.display = 'block';

        utils.notify('Archivo seleccionado correctamente', 'success');
    },

    formatFileSize: (bytes) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
};

window.removeFileStep6 = () => {
    const fileInput = document.getElementById('protocolo_investigacion');
    const placeholder = document.getElementById('upload-placeholder-step6');
    const fileSelected = document.getElementById('file-selected-step6');

    fileInput.value = '';
    placeholder.style.display = 'block';
    fileSelected.style.display = 'none';

    utils.notify('Archivo eliminado', 'info');
};

// Exportar funciones globales para el modal de profesor
window.showProfesorSearchView = () => profesorManager.showSearchView();
window.showProfesorRegistrationForm = () => profesorManager.showRegistrationView();
window.searchProfesores = (query) => profesorManager.search(query);
window.profesorManager = profesorManager;
