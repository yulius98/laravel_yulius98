/**
 * Enhanced form validation system with real-time feedback
 */
class FormValidation {
    constructor(formSelector, options = {}) {
        this.form = document.querySelector(formSelector);
        this.options = {
            realTimeValidation: true,
            showFieldErrors: true,
            submitButton: null,
            ...options
        };

        this.validationRules = {};
        this.customMessages = {};

        if (this.form) {
            this.init();
        }
    }

    init() {
        this.setupRealTimeValidation();
        this.setupSubmitValidation();
        this.enhanceFormFields();
    }

    setupRealTimeValidation() {
        if (!this.options.realTimeValidation) return;

        this.form.querySelectorAll('input, select, textarea').forEach(field => {
            // Validate on blur for better UX
            field.addEventListener('blur', () => {
                this.validateField(field);
            });

            // Clear errors on focus
            field.addEventListener('focus', () => {
                this.clearFieldError(field);
            });

            // Special handling for email fields
            if (field.type === 'email') {
                field.addEventListener('input', () => {
                    if (field.value && !this.isValidEmail(field.value)) {
                        this.showFieldError(field, 'Format email tidak valid');
                    } else {
                        this.clearFieldError(field);
                    }
                });
            }

            // Special handling for phone fields
            if (field.id.includes('telp') || field.id.includes('phone')) {
                field.addEventListener('input', () => {
                    // Format phone number as user types
                    field.value = this.formatPhoneNumber(field.value);
                });
            }
        });
    }

    setupSubmitValidation() {
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();

            if (this.validateForm()) {
                // If custom submit handler exists, call it
                if (this.options.onSubmit) {
                    this.options.onSubmit(this.getFormData());
                } else {
                    // Default form submission
                    this.form.submit();
                }
            }
        });
    }

    enhanceFormFields() {
        // Add required field indicators
        this.form.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
            const label = this.form.querySelector(`label[for="${field.id}"]`);
            if (label && !label.querySelector('.text-danger')) {
                label.innerHTML += ' <span class="text-danger">*</span>';
            }
        });

        // Add input group enhancements
        this.addInputGroupIcons();
    }

    addInputGroupIcons() {
        const iconMappings = {
            'email': 'bi-envelope',
            'password': 'bi-lock',
            'telp': 'bi-telephone',
            'phone': 'bi-telephone',
            'alamat': 'bi-geo-alt',
            'nama': 'bi-person',
            'username': 'bi-person-circle'
        };

        Object.keys(iconMappings).forEach(fieldType => {
            const fields = this.form.querySelectorAll(`input[id*="${fieldType}"], input[type="${fieldType}"]`);
            fields.forEach(field => {
                if (!field.closest('.input-group')) {
                    this.wrapWithInputGroup(field, iconMappings[fieldType]);
                }
            });
        });
    }

    wrapWithInputGroup(input, iconClass) {
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group';

        const span = document.createElement('span');
        span.className = 'input-group-text';
        span.innerHTML = `<i class="bi ${iconClass}"></i>`;

        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(span);
        wrapper.appendChild(input);
    }

    validateField(field) {
        let isValid = true;
        let errorMessage = '';

        // Required validation
        if (field.hasAttribute('required') && !field.value.trim()) {
            isValid = false;
            errorMessage = `${this.getFieldLabel(field)} wajib diisi`;
        }

        // Email validation
        if (field.type === 'email' && field.value && !this.isValidEmail(field.value)) {
            isValid = false;
            errorMessage = 'Format email tidak valid';
        }

        // Phone validation
        if ((field.id.includes('telp') || field.id.includes('phone')) && field.value) {
            if (!this.isValidPhone(field.value)) {
                isValid = false;
                errorMessage = 'Format nomor telepon tidak valid';
            }
        }

        // Custom validation rules
        const fieldName = field.name || field.id;
        if (this.validationRules[fieldName]) {
            const customResult = this.validationRules[fieldName](field.value);
            if (customResult !== true) {
                isValid = false;
                errorMessage = customResult;
            }
        }

        if (!isValid) {
            this.showFieldError(field, errorMessage);
        } else {
            this.clearFieldError(field);
        }

        return isValid;
    }

    validateForm() {
        let isValid = true;
        const fields = this.form.querySelectorAll('input, select, textarea');

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    showFieldError(field, message) {
        field.classList.add('is-invalid');

        let errorElement = field.parentNode.querySelector('.invalid-feedback');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'invalid-feedback';
            field.parentNode.appendChild(errorElement);
        }

        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorElement = field.parentNode.querySelector('.invalid-feedback');
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }

    clearAllErrors() {
        this.form.querySelectorAll('.is-invalid').forEach(field => {
            this.clearFieldError(field);
        });
    }

    getFieldLabel(field) {
        const label = this.form.querySelector(`label[for="${field.id}"]`);
        return label ? label.textContent.replace('*', '').trim() : field.name || field.id;
    }

    getFormData() {
        const formData = new FormData(this.form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        return data;
    }

    setCustomRule(fieldName, validator) {
        this.validationRules[fieldName] = validator;
    }

    setCustomMessage(fieldName, message) {
        this.customMessages[fieldName] = message;
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidPhone(phone) {
        // Remove all non-digit characters for validation
        const cleanPhone = phone.replace(/\D/g, '');
        // Indonesian phone number: 8-15 digits
        return cleanPhone.length >= 8 && cleanPhone.length <= 15;
    }

    formatPhoneNumber(phone) {
        // Basic phone number formatting for Indonesian numbers
        const cleaned = phone.replace(/\D/g, '');

        if (cleaned.length <= 4) return cleaned;
        if (cleaned.length <= 8) return cleaned.replace(/(\d{4})(\d{0,4})/, '$1-$2');
        if (cleaned.length <= 12) return cleaned.replace(/(\d{4})(\d{4})(\d{0,4})/, '$1-$2-$3');

        return cleaned.replace(/(\d{4})(\d{4})(\d{4})(\d{0,3})/, '$1-$2-$3-$4');
    }

    showServerErrors(errors) {
        Object.keys(errors).forEach(fieldName => {
            const field = this.form.querySelector(`[name="${fieldName}"], #${fieldName}`);
            if (field && errors[fieldName]) {
                this.showFieldError(field, errors[fieldName][0]);
            }
        });
    }

    setLoading(isLoading = true) {
        const submitBtn = this.options.submitButton || this.form.querySelector('button[type="submit"]');
        if (submitBtn) {
            if (isLoading) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            } else {
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.dataset.originalText || 'Simpan';
            }
        }
    }
}

// Make FormValidation available globally
window.FormValidation = FormValidation;
