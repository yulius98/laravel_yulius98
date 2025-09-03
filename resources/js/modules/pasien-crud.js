/**
 * Pasien CRUD functionality extending BaseCrud
 */
class PasienCrud extends BaseCrud {
    constructor() {
        super({
            tableId: '#pasien-table',
            formId: '#form-pasien',
            modalId: '#modalForm',
            searchId: '#search',
            apiEndpoint: '/pasien',
            searchEndpoint: '/pasien/search/data',
            entityName: 'Pasien',
            fields: ['nama_pasien', 'alamat', 'no_telp', 'id_rumah_sakit']
        });

        this.setupFilterHandlers();
    }

    setupFilterHandlers() {
        const filterRumahSakit = document.getElementById('filterRumahSakit');
        if (filterRumahSakit) {
            filterRumahSakit.addEventListener('change', () => {
                this.handleSearch(document.getElementById('search').value);
            });
        }
    }

    getAdditionalSearchParams() {
        const filterRumahSakit = document.getElementById('filterRumahSakit');
        return {
            rumah_sakit_id: filterRumahSakit ? filterRumahSakit.value : ''
        };
    }

    createTableRow(pasien, index) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${pasien.nama_pasien}</td>
            <td>${pasien.alamat}</td>
            <td>${pasien.no_telp}</td>
            <td>${pasien.id_rumah_sakit}</td>
            <td>${pasien.rumah_sakit ? pasien.rumah_sakit.nama_rumah_sakit : '-'}</td>
            <td>
                <button class="btn btn-sm btn-warning me-1 edit-btn" data-id="${pasien.id}">
                    <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${pasien.id}">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </td>
        `;
        return row;
    }

    populateForm(data) {
        document.getElementById('data-id').value = data.id || '';
        document.getElementById('nama_pasien').value = data.nama_pasien || '';
        document.getElementById('alamat').value = data.alamat || '';
        document.getElementById('no_telp').value = data.no_telp || '';
        document.getElementById('id_rumah_sakit').value = data.id_rumah_sakit || '';
    }

    getFormData() {
        return {
            nama_pasien: document.getElementById('nama_pasien').value,
            alamat: document.getElementById('alamat').value,
            no_telp: document.getElementById('no_telp').value,
            id_rumah_sakit: document.getElementById('id_rumah_sakit').value
        };
    }

    clearValidationErrors() {
        ['nama_pasien', 'alamat', 'no_telp', 'id_rumah_sakit'].forEach(field => {
            const input = document.getElementById(field);
            const errorId = field === 'id_rumah_sakit' ? 'err-rumah-sakit' :
                           field === 'no_telp' ? 'err-telp' :
                           `err-${field.replace('_', '-')}`;
            const errorEl = document.getElementById(errorId);

            if (input) input.classList.remove('is-invalid');
            if (errorEl) errorEl.innerText = '';
        });
    }

    handleValidationErrors(error) {
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            const fieldMapping = {
                'nama_pasien': 'err-nama',
                'alamat': 'err-alamat',
                'no_telp': 'err-telp',
                'id_rumah_sakit': 'err-rumah-sakit'
            };

            Object.keys(errors).forEach(field => {
                const input = document.getElementById(field);
                const errorEl = document.getElementById(fieldMapping[field]);

                if (input) input.classList.add('is-invalid');
                if (errorEl) errorEl.innerText = errors[field][0];
            });
        } else {
            this.showError('Gagal menyimpan data!');
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.pasienCrud = new PasienCrud();

    // Make functions global for backward compatibility
    window.editPasien = (id) => window.pasienCrud.edit(id);
    window.deletePasien = (id) => window.pasienCrud.delete(id);
});
