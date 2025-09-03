/**
 * Rumah Sakit CRUD functionality extending BaseCrud
 */
class RumahSakitCrud extends BaseCrud {
    constructor() {
        super({
            tableId: '#rs-table',
            formId: '#form-rs',
            modalId: '#modalForm',
            searchId: '#search',
            apiEndpoint: '/rumah-sakit',
            searchEndpoint: '/rumah-sakit/search/data',
            entityName: 'Rumah Sakit',
            fields: ['nama_rumah_sakit', 'alamat', 'email', 'telepon']
        });
    }

    createTableRow(rs, index) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${rs.nama_rumah_sakit}</td>
            <td>${rs.alamat}</td>
            <td>${rs.email}</td>
            <td>${rs.telepon}</td>
            <td>
                <button class="btn btn-sm btn-warning me-1 edit-btn" data-id="${rs.id}">
                    <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${rs.id}">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </td>
        `;
        return row;
    }

    populateForm(data) {
        document.getElementById('data-id').value = data.id || '';
        document.getElementById('nama_rumah_sakit').value = data.nama_rumah_sakit || '';
        document.getElementById('alamat').value = data.alamat || '';
        document.getElementById('email').value = data.email || '';
        document.getElementById('telepon').value = data.telepon || '';
    }

    getFormData() {
        return {
            nama_rumah_sakit: document.getElementById('nama_rumah_sakit').value,
            alamat: document.getElementById('alamat').value,
            email: document.getElementById('email').value,
            telepon: document.getElementById('telepon').value
        };
    }

    clearValidationErrors() {
        ['nama_rumah_sakit', 'alamat', 'email', 'telepon'].forEach(field => {
            const input = document.getElementById(field);
            const errorId = field === 'nama_rumah_sakit' ? 'err-nama' : `err-${field}`;
            const errorEl = document.getElementById(errorId);
            
            if (input) input.classList.remove('is-invalid');
            if (errorEl) errorEl.innerText = '';
        });
    }

    handleValidationErrors(error) {
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            const fieldMapping = {
                'nama_rumah_sakit': 'err-nama',
                'alamat': 'err-alamat',
                'email': 'err-email',
                'telepon': 'err-telepon'
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
    window.rumahSakitCrud = new RumahSakitCrud();
    
    // Make functions global for backward compatibility
    window.editRS = (id) => window.rumahSakitCrud.edit(id);
    window.deleteRS = (id) => window.rumahSakitCrud.delete(id);
});
