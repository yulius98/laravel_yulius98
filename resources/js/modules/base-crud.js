/**
 * Base CRUD functionality for data management
 */
class BaseCrud {
    constructor(config) {
        this.config = {
            tableId: config.tableId || '#data-table',
            formId: config.formId || '#form-data',
            modalId: config.modalId || '#modalForm',
            searchId: config.searchId || '#search',
            paginationId: config.paginationId || '#pagination-wrapper',
            apiEndpoint: config.apiEndpoint,
            searchEndpoint: config.searchEndpoint,
            entityName: config.entityName || 'data',
            fields: config.fields || [],
            ...config
        };

        this.init();
    }

    init() {
        this.setupAxios();
        this.setupEventListeners();
        this.initModal();
    }

    setupAxios() {
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    setupEventListeners() {
        // Search functionality
        const searchInput = document.getElementById(this.config.searchId.replace('#', ''));
        if (searchInput) {
            let timer = null;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    this.handleSearch(e.target.value);
                }, 400);
            });
        }

        // Form submission
        const form = document.querySelector(this.config.formId);
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleFormSubmit();
            });
        }

        // Add button
        const addButton = document.querySelector(`[data-bs-target="${this.config.modalId}"]`);
        if (addButton) {
            addButton.addEventListener('click', () => {
                this.resetForm();
                this.setModalTitle(`Tambah ${this.config.entityName}`);
            });
        }

        // Dynamic button handlers
        this.attachDynamicHandlers();
    }

    initModal() {
        const modalEl = document.querySelector(this.config.modalId);
        if (modalEl) {
            this.modal = new bootstrap.Modal(modalEl);
        }
    }

    attachDynamicHandlers() {
        // For server-side rendered buttons
        document.querySelectorAll('.edit-btn, [onclick*="edit"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const id = this.extractIdFromElement(btn);
                if (id) this.edit(id);
            });
        });

        document.querySelectorAll('.delete-btn, [onclick*="delete"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const id = this.extractIdFromElement(btn);
                if (id) this.delete(id);
            });
        });
    }

    extractIdFromElement(element) {
        return element.dataset.id ||
               (element.getAttribute('onclick') && element.getAttribute('onclick').match(/\d+/)?.[0]);
    }

    handleSearch(query) {
        const additionalParams = this.getAdditionalSearchParams();

        if (!query.trim() && !Object.keys(additionalParams).length) {
            window.location.reload();
            return;
        }

        this.fetchData(query, additionalParams);
    }

    getAdditionalSearchParams() {
        // Override in child classes for additional search parameters
        return {};
    }

    async fetchData(query = '', additionalParams = {}) {
        try {
            const params = { q: query, ...additionalParams };
            const response = await axios.get(this.config.searchEndpoint, { params });

            this.updateTable(response.data);
            this.togglePagination(!query && !Object.keys(additionalParams).length);
        } catch (error) {
            console.error('Error fetching data:', error);
            this.showError('Error loading data');
        }
    }

    updateTable(data) {
        const tbody = document.querySelector(`${this.config.tableId} tbody`);
        if (!tbody) return;

        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="${this.getColumnCount()}" class="text-center">Tidak ada data</td></tr>`;
            return;
        }

        data.forEach((item, index) => {
            const row = this.createTableRow(item, index);
            tbody.appendChild(row);
        });

        // Reattach event handlers for new buttons
        this.attachDynamicHandlers();
    }

    createTableRow(item, index) {
        // Override in child classes
        const row = document.createElement('tr');
        row.innerHTML = `<td colspan="${this.getColumnCount()}">Override createTableRow method</td>`;
        return row;
    }

    getColumnCount() {
        const headerRow = document.querySelector(`${this.config.tableId} thead tr`);
        return headerRow ? headerRow.children.length : 1;
    }

    togglePagination(show) {
        const pagination = document.querySelector(this.config.paginationId);
        if (pagination) {
            pagination.style.display = show ? 'block' : 'none';
        }
    }

    async edit(id) {
        try {
            const response = await axios.get(`${this.config.apiEndpoint}/detail/${id}`);
            this.populateForm(response.data);
            this.setModalTitle(`Edit ${this.config.entityName}`);
            if (this.modal) this.modal.show();
        } catch (error) {
            console.error('Error loading data:', error);
            this.showError('Gagal memuat data');
        }
    }

    populateForm(data) {
        // Override in child classes
        this.config.fields.forEach(field => {
            const input = document.getElementById(field);
            if (input && data[field] !== undefined) {
                input.value = data[field];
            }
        });

        // Set ID for update
        const idInput = document.getElementById('data-id');
        if (idInput) idInput.value = data.id;
    }

    async delete(id) {
        if (!confirm(`Yakin ingin menghapus ${this.config.entityName} ini?`)) return;

        try {
            await axios.delete(`${this.config.apiEndpoint}/${id}`);
            this.showSuccess('Data berhasil dihapus!');
            this.refreshData();
        } catch (error) {
            console.error('Error deleting data:', error);
            this.showError('Gagal menghapus data!');
        }
    }

    async handleFormSubmit() {
        this.clearValidationErrors();

        const formData = this.getFormData();
        const idInput = document.getElementById('data-id');
        const id = idInput ? idInput.value : null;

        try {
            if (id) {
                await axios.put(`${this.config.apiEndpoint}/${id}`, formData);
            } else {
                await axios.post(this.config.apiEndpoint, formData);
            }

            if (this.modal) this.modal.hide();
            this.showSuccess('Data berhasil disimpan!');
            this.refreshData();
        } catch (error) {
            this.handleValidationErrors(error);
        }
    }

    getFormData() {
        const formData = {};
        this.config.fields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                formData[field] = input.value;
            }
        });
        return formData;
    }

    clearValidationErrors() {
        this.config.fields.forEach(field => {
            const input = document.getElementById(field);
            const errorEl = document.getElementById(`err-${field.replace('_', '-')}`);

            if (input) input.classList.remove('is-invalid');
            if (errorEl) errorEl.innerText = '';
        });
    }

    handleValidationErrors(error) {
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            Object.keys(errors).forEach(field => {
                const input = document.getElementById(field);
                const errorEl = document.getElementById(`err-${field.replace('_', '-')}`);

                if (input) input.classList.add('is-invalid');
                if (errorEl) errorEl.innerText = errors[field][0];
            });
        } else {
            this.showError('Gagal menyimpan data!');
        }
    }

    resetForm() {
        const form = document.querySelector(this.config.formId);
        if (form) form.reset();

        const idInput = document.getElementById('data-id');
        if (idInput) idInput.value = '';

        this.clearValidationErrors();
    }

    setModalTitle(title) {
        const modalTitle = document.getElementById('modalFormLabel');
        if (modalTitle) modalTitle.innerText = title;
    }

    refreshData() {
        const searchInput = document.getElementById(this.config.searchId.replace('#', ''));
        const searchValue = searchInput ? searchInput.value : '';
        const additionalParams = this.getAdditionalSearchParams();

        if (!searchValue && !Object.keys(additionalParams).length) {
            window.location.reload();
        } else {
            this.fetchData(searchValue, additionalParams);
        }
    }

    showSuccess(message) {
        alert(message); // Replace with better notification system
    }

    showError(message) {
        alert(message); // Replace with better notification system
    }
}

// Export for use in other modules
window.BaseCrud = BaseCrud;
