// Section Edit Form Script

window.BuilderSection = {

    getValue(form, input) {

        if (!input) return null;

        if (input.type === "radio") {
            const checked = form.querySelector(`input[name="${input.name}"]:checked`);
            return checked ? checked.value : null;
        }

        if (input.type === "checkbox") {
            return input.checked ? input.value : "0";
        }

        if (input.type === "hidden") {
            const checkbox = form.querySelector(`input[type="checkbox"][name="${input.name}"]`);
            if (checkbox) return checkbox.checked ? checkbox.value : "0";
            return input.value;
        }

        return input.value;
    },

    evaluate(value, operator, expected) {
        let valStr = String(value);
        let expStr = String(expected);

        if (expected === true || expected === 'true') expStr = '1';
        if (expected === false || expected === 'false') expStr = '0';
        if (value === true || value === 'true') valStr = '1';
        if (value === false || value === 'false') valStr = '0';

        switch (operator) {
            case '!=': return valStr !== expStr;
            case 'in': return Array.isArray(expected) && expected.map(String).includes(valStr);
            case 'not_in': return Array.isArray(expected) && !expected.map(String).includes(valStr);
            default: return valStr === expStr;
        }
    },

    updateConditional(form) {

        form.querySelectorAll('[data-conditions]').forEach(field => {

            let conditions = JSON.parse(field.dataset.conditions);
            let show = true;

            if (conditions.operator === 'or' && Array.isArray(conditions.rules)) {

                show = false;

                conditions.rules.forEach(rule => {
                    const fieldKey = rule.field || rule.key;
                    const control = form.querySelector(`[name="settings[${fieldKey}]"],[name="${fieldKey}"]`);
                    const value = this.getValue(form, control);
                    if (this.evaluate(value, rule.operator ?? '==', rule.value)) show = true;
                });

            } else {

                if (!Array.isArray(conditions)) conditions = [conditions];

                conditions.forEach(cond => {
                    const fieldKey = cond.field || cond.key;
                    const control = form.querySelector(`[name="settings[${fieldKey}]"],[name="${fieldKey}"]`);
                    const value = this.getValue(form, control);
                    if (!this.evaluate(value, cond.operator ?? '==', cond.value)) show = false;
                });

            }

            field.style.display = show ? 'block' : 'none';
            field.querySelectorAll('input,select,textarea').forEach(el => el.disabled = !show);

        });
    },

    submit(form) {

        const fd = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                'X-HTTP-Method-Override': 'PUT'
            },
            body: fd
        })
            .then(r => r.json())
            .then(d => {
                if (d.status === 'success') reloadPreview();
            });

    },

    init(form) {

        if (!form) return;

        this.updateConditional(form);

        let timeout = null;

        form.addEventListener('input', () => {
            this.updateConditional(form);
            clearTimeout(timeout);
            timeout = setTimeout(() => this.submit(form), 250);
        });

        form.addEventListener('change', () => {
            this.updateConditional(form);
            clearTimeout(timeout);
            timeout = setTimeout(() => this.submit(form), 250);
        });

    }

};
window.initBuilderSections = function (container = document) {

    container.querySelectorAll('.editSectionForm').forEach(form => {
        if (form.dataset.builderInitialized) return;

        form.dataset.builderInitialized = "1";
        BuilderSection.init(form);
    });

};

document.addEventListener("turbo:load", () => initBuilderSections());
document.addEventListener("DOMContentLoaded", () => initBuilderSections());

const observer = new MutationObserver(mutations => {
    mutations.forEach(m => {
        m.addedNodes.forEach(node => {
            if (node.nodeType !== 1) return;
            initBuilderSections(node);
        });
    });
});

observer.observe(document.body, { childList: true, subtree: true });


// media delete globally
window.deleteSectionMedia = function (key) {

    const wrapper = document.querySelector('.media-field-' + key);
    if (!wrapper) return;

    wrapper.querySelector('[data-content-preview]')?.classList.add('hidden');
    wrapper.querySelector('[data-content-preview]')?.removeAttribute('src');
    wrapper.querySelector('[data-content-placeholder]')?.classList.remove('hidden');

    const input = wrapper.querySelector('input');
    if (input) input.value = '';

    const form = wrapper.closest('.editSectionForm');
    if (form) BuilderSection.submit(form);

};






// Section Add Form Script

(function () {

    if (window.__ARZAVO_ADD_SECTION_INIT__) return;
    window.__ARZAVO_ADD_SECTION_INIT__ = true;

    const containerId = 'addSectionContainer';

    /* -----------------------------
       CATEGORY FILTER
    ----------------------------- */
    window.filterSectionsByTarget = function (target) {

        document.querySelectorAll('.section-category')
            .forEach(cat => {

                const category = cat.dataset.originalCategory;

                let show = false;

                if (target === 'header')
                    show = category === 'Header';

                else if (target === 'footer')
                    show = category === 'Footer';

                else
                    show = category !== 'Header'
                        && category !== 'Footer';

                cat.classList.toggle('hidden', !show);
            });
    };


    /* -----------------------------
       CATEGORY COLLAPSE STATE
    ----------------------------- */
    function initCategoryToggle() {

        const savedState =
            JSON.parse(localStorage.getItem('sectionCategoriesState')) || {};

        document.querySelectorAll('.section-category')
            .forEach(cat => {

                const key = cat.dataset.category;
                const content = cat.querySelector('.category-items');
                const icon = cat.querySelector('.fa-angle-down');

                if (savedState[key] === undefined)
                    savedState[key] = true;

                if (!savedState[key]) {
                    content.classList.add('hidden');
                    icon.style.transform = "rotate(-90deg)";
                }

                cat.querySelector('.category-toggle')
                    ?.addEventListener('click', () => {

                        content.classList.toggle('hidden');

                        const isOpen =
                            !content.classList.contains('hidden');

                        savedState[key] = isOpen;

                        icon.style.transform =
                            isOpen ? "rotate(0deg)" : "rotate(-90deg)";

                        localStorage.setItem(
                            'sectionCategoriesState',
                            JSON.stringify(savedState)
                        );
                    });

            });
    }


    /* -----------------------------
       FORM SUBMIT HELPER
    ----------------------------- */
    async function submitForm({
        formId,
        buttonId,
        insertTarget,
        beforeInsert
    }) {

        const form = document.getElementById(formId);
        const button = document.getElementById(buttonId);
        const container =
            document.getElementById(containerId);

        if (!form || !button) return;

        const originalHTML = button.innerHTML;

        button.disabled = true;
        button.innerHTML =
            `<i class="fa-solid fa-spinner fa-spin"></i> Adding...`;

        try {

            if (beforeInsert)
                beforeInsert(form);

            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        )?.content,
                },
            });

            if (!response.ok)
                throw new Error('Request failed');

            const html = await response.text();

            document
                .getElementById(insertTarget)
                ?.insertAdjacentHTML('beforeend', html);

            container?.classList.add('hidden');

            window.reloadPreview?.();
            window.initRichText?.();

        } catch (e) {
            console.error(e);
            button.textContent = 'Error! Try Again';
        } finally {
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    }


    /* -----------------------------
       GLOBAL METHODS
    ----------------------------- */
    window.SubmitSectionForm = function (type) {

        submitForm({
            formId: `sectionAddForm${type}`,
            buttonId: `sectionAddBtn${type}`,
            insertTarget:
                window._ARZAVO_SECTION_TARGET + '-section-list',

            beforeInsert: (form) => {
                form.querySelector(
                    'input[name="target"]'
                ).value =
                    window._ARZAVO_SECTION_TARGET;
            }
        });
    };


    window.SubmitTemplateForm = function (type) {

        submitForm({
            formId: `templateAddForm${type}`,
            buttonId: `templateAddbtn${type}`,
            insertTarget: 'page-section-list'
        });
    };


    /* -----------------------------
       TURBO SAFE INIT
    ----------------------------- */
    document.addEventListener(
        'turbo:load',
        initCategoryToggle
    );
    document.addEventListener(
        'DOMContentLoaded',
        initCategoryToggle
    );

})();




// Block Edit Form Script
(function () {

    if (window.__BLOCK_BUILDER_INITIALIZED__) return;
    window.__BLOCK_BUILDER_INITIALIZED__ = true;

    document.addEventListener("turbo:load", () => window.initBlockForms?.());
    document.addEventListener("DOMContentLoaded", () => window.initBlockForms?.());

    window.initBlockForms = function () {

        document.querySelectorAll('.editBlockForm').forEach(form => {

            if (form.dataset.initialized) return;
            form.dataset.initialized = "1";

            /* ---------------- VALUE HELPER ---------------- */

            function getValue(input) {
                if (!input) return null;

                if (input.type === "radio") {
                    const checked = form.querySelector(`input[name="${input.name}"]:checked`);
                    return checked ? checked.value : null;
                }

                if (input.type === "checkbox") {
                    return input.checked ? input.value : "0";
                }

                if (input.type === "hidden") {
                    const checkbox = form.querySelector(`input[type="checkbox"][name="${input.name}"]`);
                    if (checkbox) {
                        return checkbox.checked ? checkbox.value : "0";
                    }
                    return input.value;
                }

                return input.value;
            }

            function evaluateCondition(controlValue, operator, expected) {
                let valStr = String(controlValue);
                let expStr = String(expected);

                if (expected === true || expected === 'true') expStr = '1';
                if (expected === false || expected === 'false') expStr = '0';
                if (controlValue === true || controlValue === 'true') valStr = '1';
                if (controlValue === false || controlValue === 'false') valStr = '0';

                switch (operator) {

                    case '==':
                    case '=':
                        return valStr === expStr;

                    case '!=':
                        return valStr !== expStr;

                    case 'in':
                        return Array.isArray(expected) && expected.map(String).includes(valStr);

                    case 'not_in':
                        return Array.isArray(expected) && !expected.map(String).includes(valStr);

                    default:
                        return valStr === expStr;
                }
            }

            function updateConditionalFields() {

                form.querySelectorAll('[data-conditions]').forEach(field => {

                    let conditions = JSON.parse(field.dataset.conditions);
                    let shouldShow = true;

                    if (conditions.operator === 'or' && Array.isArray(conditions.rules)) {

                        shouldShow = false;

                        conditions.rules.forEach(rule => {

                            const fieldName = rule.field || rule.key;
                            const control = form.querySelector(
                                `[name="settings[${fieldName}]"], [name="${fieldName}"]`
                            );

                            const value = getValue(control);

                            if (evaluateCondition(value, rule.operator ?? '==', rule.value)) {
                                shouldShow = true;
                            }

                        });

                    } else {

                        if (!Array.isArray(conditions)) {
                            conditions = [conditions];
                        }

                        conditions.forEach(cond => {

                            const fieldName = cond.field || cond.key;
                            const control = form.querySelector(
                                `[name="settings[${fieldName}]"], [name="${fieldName}"]`
                            );

                            const value = getValue(control);

                            if (!evaluateCondition(value, cond.operator ?? '==', cond.value)) {
                                shouldShow = false;
                            }

                        });

                    }

                    field.style.display = shouldShow ? 'block' : 'none';
                    field.style.opacity = shouldShow ? '1' : '0';

                    field.querySelectorAll('input,select,textarea')
                        .forEach(el => el.disabled = !shouldShow);
                });
            }

            updateConditionalFields();

            form.addEventListener("input", updateConditionalFields);
            form.addEventListener("change", updateConditionalFields);

        });

    }


    /* ---------------- AUTO SUBMIT GLOBAL ---------------- */

    if (!window.__BLOCK_FORM_SUBMIT_BOUND__) {

        window.__BLOCK_FORM_SUBMIT_BOUND__ = true;
        window.submitTimeout = null;

        document.addEventListener('input', handleBlockFormChange);
        document.addEventListener('change', handleBlockFormChange);
    }

    function handleBlockFormChange(e) {

        const form = e.target.closest('.editBlockForm');
        if (!form) return;

        clearTimeout(window.submitTimeout);

        window.submitTimeout = setTimeout(() => {
            submitBlockForm(form);
        }, 200);
    }


    /* ---------------- GLOBAL SUBMIT ---------------- */

    window.submitBlockForm = function (form) {

        const formData = new FormData(form);

        const imageInputs = form.querySelectorAll('input[type="hidden"][id$="Input"]');

        imageInputs.forEach(input => {
            if (input.value === '') {
                formData.set(input.name, '');
            }
        });

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                'X-HTTP-Method-Override': 'PUT'
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {

                if (data.status === 'success') {

                    const iframe = document.getElementById('livePreviewContent');

                    if (iframe) iframe.contentWindow.location.reload();

                } else {

                    console.error('Update failed:', data.message || 'Unknown error');

                }

            })
            .catch(err => console.error('Live update failed:', err));
    };


    /* ---------------- MEDIA DELETE GLOBAL ---------------- */

    window.deleteBlockMedia = function (key) {

        const wrapper = document.querySelector('.media-field-' + key);
        if (!wrapper) return;

        const preview = wrapper.querySelector('[data-content-preview]');
        const placeholder = wrapper.querySelector('[data-content-placeholder]');
        const input = wrapper.querySelector('input');

        if (preview) {
            preview.classList.add('hidden');
            preview.removeAttribute('src');
        }

        if (placeholder) {
            placeholder.classList.remove('hidden');
        }

        if (input) {
            input.value = '';
        }

        const form = wrapper.closest('.editBlockForm');
        if (form) submitBlockForm(form);
    };

})();

if (!window.__BLOCK_FORM_OBSERVER__) {

    window.__BLOCK_FORM_OBSERVER__ = true;

    const observer = new MutationObserver(() => {

        if (window.initBlockForms) {
            window.initBlockForms();
        }

    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

}

// Block Add Form Script
// resources/js/builder/add-block.js

(function () {

    if (window.__ARZAVO_ADD_BLOCK_INIT__) return;
    window.__ARZAVO_ADD_BLOCK_INIT__ = true;

    /* -----------------------------
       CATEGORY TOGGLE STATE
    ----------------------------- */

    function initBlockCategories() {

        const savedState =
            JSON.parse(localStorage.getItem('BlockCategoriesState')) || {};

        document.querySelectorAll('.Block-category')
            .forEach(cat => {

                const key = cat.dataset.category;
                const content = cat.querySelector('.category-items');
                const icon = cat.querySelector('.fa-angle-down');

                if (savedState[key] === false) {
                    content.classList.add('hidden');
                    icon.style.transform = "rotate(-90deg)";
                }

                cat.querySelector('.category-toggle')
                    ?.addEventListener('click', () => {

                        content.classList.toggle('hidden');

                        const isOpen =
                            !content.classList.contains('hidden');

                        savedState[key] = isOpen;

                        icon.style.transform =
                            isOpen ? "rotate(0deg)" : "rotate(-90deg)";

                        localStorage.setItem(
                            'BlockCategoriesState',
                            JSON.stringify(savedState)
                        );
                    });
            });
    }


    /* -----------------------------
       BLOCK ADD
    ----------------------------- */

    window.submitAddBlockForm =
        async function (sectionId, blockType) {

            const container =
                document.getElementById(
                    `addBlockContainer${sectionId}`
                );

            const blockList =
                document.getElementById(
                    `block-list-${sectionId}`
                );

            const form =
                document.getElementById(
                    `blockAddForm${sectionId}-${blockType}`
                );

            const button =
                document.getElementById(
                    `blockAddBtn${sectionId}-${blockType}`
                );

            if (!form || !button) return;

            const originalHTML = button.innerHTML;

            button.disabled = true;
            button.innerHTML =
                `<i class="fa-solid fa-spinner fa-spin"></i> Adding...`;

            try {

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            )?.content,
                    },
                });

                if (!response.ok)
                    throw new Error('Request failed');

                const html = await response.text();

                blockList
                    ?.insertAdjacentHTML('beforeend', html);

                container?.classList.add('hidden');

                window.reloadPreview?.();
                window.initRichText?.();
                window.initBlockForms?.();

            } catch (err) {

                console.error(err);
                button.textContent = 'Error! Try Again';

            } finally {

                button.disabled = false;
                button.innerHTML = originalHTML;
            }
        };


    /* -----------------------------
       TURBO SAFE INIT
    ----------------------------- */

    document.addEventListener(
        'turbo:load',
        initBlockCategories
    );
    document.addEventListener(
        'DOMContentLoaded',
        initBlockCategories
    );

})();