<x-layout_admin>
    <x-slot:title>{{ $title }}</x-slot:title>
    @section('content')
    <div class="container">
        <h2 class="mb-4">Data Pasien</h2>

        <!-- Filter dan Search Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="search" class="form-control" placeholder="Cari nama pasien atau rumah sakit...">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Ketik minimal 2 karakter untuk pencarian
                </small>
            </div>
            <div class="col-md-4">
                <select id="filterRumahSakit" class="form-select">
                    <option value="">🏥 Semua Rumah Sakit</option>
                    @foreach($dtrumahsakit as $rs)
                        <option value="{{ $rs->id }}">{{ $rs->nama_rumah_sakit }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 text-end d-flex align-items-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
                    <i class="bi bi-person-plus me-2"></i>Tambah Pasien
                </button>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-3" id="pagination-wrapper">
            @if(isset($dtpasien))
                {{ $dtpasien->links('pagination::bootstrap-5') }}
            @endif
        </div>

        <!-- Table Section -->
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="pasien-table">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Alamat</th>
                        <th>No Telepon</th>
                        <th>ID Rumah Sakit</th>
                        <th>Nama Rumah Sakit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($dtpasien) && count($dtpasien))
                        @foreach($dtpasien as $index => $pasien)
                            <tr>
                                <td>{{ $dtpasien->firstItem() + $index }}</td>
                                <td>{{ $pasien->nama_pasien }}</td>
                                <td>{{ $pasien->alamat }}</td>
                                <td>{{ $pasien->no_telp }}</td>
                                <td>{{ $pasien->id_rumah_sakit }}</td>
                                <td>{{ $pasien->rumah_sakit->nama_rumah_sakit ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editPasien({{ $pasien->id }})">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deletePasien({{ $pasien->id }})">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="7" class="text-center">Tidak ada data</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Modal Form Tambah/Edit -->
        <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="form-pasien" novalidate>
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalFormLabel">
                                <i class="bi bi-person-plus me-2"></i>Tambah Pasien
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="data-id">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_pasien" class="form-label fw-medium">
                                            <i class="bi bi-person me-1"></i>Nama Lengkap Pasien
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person"></i>
                                            </span>
                                            <input type="text"
                                                   class="form-control"
                                                   id="nama_pasien"
                                                   required
                                                   placeholder="Masukkan nama lengkap pasien"
                                                   maxlength="255">
                                        </div>
                                        <div class="invalid-feedback" id="err-nama"></div>
                                        <small class="text-muted">Nama lengkap sesuai identitas resmi</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="no_telp" class="form-label fw-medium">
                                            <i class="bi bi-telephone me-1"></i>Nomor Telepon
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-telephone"></i>
                                            </span>
                                            <input type="tel"
                                                   class="form-control"
                                                   id="no_telp"
                                                   required
                                                   placeholder="081234567890"
                                                   maxlength="20">
                                        </div>
                                        <div class="invalid-feedback" id="err-telp"></div>
                                        <small class="text-muted">Nomor yang dapat dihubungi (WA/Telp)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-medium">
                                    <i class="bi bi-geo-alt me-1"></i>Alamat Lengkap
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control"
                                          id="alamat"
                                          rows="3"
                                          required
                                          placeholder="Jl. Nama Jalan No. XX, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi"
                                          maxlength="500"></textarea>
                                <div class="invalid-feedback" id="err-alamat"></div>
                                <small class="text-muted">Alamat tempat tinggal lengkap dengan RT/RW (maksimal 500 karakter)</small>
                            </div>

                            <div class="mb-3">
                                <label for="id_rumah_sakit" class="form-label fw-medium">
                                    <i class="bi bi-hospital me-1"></i>Rumah Sakit Tujuan
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="id_rumah_sakit" required>
                                    <option value="">🏥 Pilih Rumah Sakit Tujuan</option>
                                    @foreach($dtrumahsakit as $rs)
                                        <option value="{{ $rs->id }}"
                                                data-alamat="{{ $rs->alamat }}"
                                                data-telepon="{{ $rs->telepon }}">
                                            {{ $rs->nama_rumah_sakit }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="err-rumah-sakit"></div>
                                <small class="text-muted">Pilih rumah sakit tempat pasien akan dirawat</small>
                            </div>

                            <!-- Hospital Info Display -->
                            <div id="hospital-info" class="alert alert-info d-none" role="alert">
                                <h6 class="alert-heading">
                                    <i class="bi bi-info-circle me-1"></i>Informasi Rumah Sakit:
                                </h6>
                                <p class="mb-1">
                                    <strong>📍 Alamat:</strong> <span id="hospital-alamat"></span>
                                </p>
                                <p class="mb-0">
                                    <strong>📞 Telepon:</strong> <span id="hospital-telepon"></span>
                                </p>
                            </div>

                            <div class="alert alert-warning" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <small>
                                    <strong>Penting:</strong> Pastikan data pasien sudah benar sebelum menyimpan.
                                    Data ini akan digunakan untuk administrasi rumah sakit.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary" data-original-text="Simpan Data Pasien">
                                <i class="bi bi-check-circle me-2"></i>Simpan Data Pasien
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('styles')
    <style>
        .table-primary th {
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            color: white;
            font-weight: 600;
            border: none;
        }
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .btn-sm {
            font-size: 0.775rem;
            padding: 0.25rem 0.5rem;
        }
        .modal-header.bg-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%) !important;
        }
        .form-label .text-danger {
            font-size: 0.875rem;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
    @endpush

    @push('scripts')
    <script src="{{ asset('js/modules/notification-system.js') }}"></script>
    <script src="{{ asset('js/modules/form-validation.js') }}"></script>
    <script src="{{ asset('js/modules/base-crud.js') }}"></script>
    <script src="{{ asset('js/modules/pasien-crud.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Clear search functionality
            const clearSearchBtn = document.getElementById('clearSearch');
            const searchInput = document.getElementById('search');

            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            });

            // Show clear button when there's text
            searchInput.addEventListener('input', function() {
                clearSearchBtn.style.display = this.value ? 'block' : 'none';
            });

            // Initial state
            clearSearchBtn.style.display = searchInput.value ? 'block' : 'none';

            // Hospital info display
            const hospitalSelect = document.getElementById('id_rumah_sakit');
            const hospitalInfo = document.getElementById('hospital-info');
            const hospitalAlamat = document.getElementById('hospital-alamat');
            const hospitalTelepon = document.getElementById('hospital-telepon');

            hospitalSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];

                if (this.value && selectedOption.dataset.alamat) {
                    hospitalAlamat.textContent = selectedOption.dataset.alamat;
                    hospitalTelepon.textContent = selectedOption.dataset.telepon;
                    hospitalInfo.classList.remove('d-none');
                } else {
                    hospitalInfo.classList.add('d-none');
                }
            });
        });
    </script>
    @endpush
</x-layout_admin>
