<x-layout_admin>
    <x-slot:title>{{ $title }}</x-slot:title>
    @section('content')
    <div class="container">
        <h2 class="mb-4">Data Rumah Sakit</h2>

        <!-- Filter dan Search Section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="search" class="form-control" placeholder="Cari nama rumah sakit...">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Ketik minimal 2 karakter untuk pencarian otomatis
                </small>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Rumah Sakit
                </button>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-3" id="pagination-wrapper">
            @if(isset($dtrumahsakit))
                {{ $dtrumahsakit->links('pagination::bootstrap-5') }}
            @endif
        </div>

        <!-- Table Section -->
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="rs-table">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Rumah Sakit</th>
                        <th>Alamat</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($dtrumahsakit) && count($dtrumahsakit))
                        @foreach($dtrumahsakit as $index => $rs)
                            <tr>
                                <td>{{ $dtrumahsakit->firstItem() + $index }}</td>
                                <td>{{ $rs->nama_rumah_sakit }}</td>
                                <td>{{ $rs->alamat }}</td>
                                <td>{{ $rs->email }}</td>
                                <td>{{ $rs->telepon }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editRS({{ $rs->id }})">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteRS({{ $rs->id }})">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Modal Form Tambah/Edit -->
        <div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="form-rs" novalidate>
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalFormLabel">
                                <i class="bi bi-hospital me-2"></i>Tambah Rumah Sakit
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="data-id">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="nama_rumah_sakit" class="form-label fw-medium">
                                            <i class="bi bi-hospital me-1"></i>Nama Rumah Sakit
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               id="nama_rumah_sakit"
                                               required
                                               placeholder="Contoh: RS Umum Pusat Sanglah"
                                               maxlength="255">
                                        <div class="invalid-feedback" id="err-nama"></div>
                                        <small class="text-muted">Nama lengkap rumah sakit (maksimal 255 karakter)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-medium">
                                            <i class="bi bi-envelope me-1"></i>Email
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input type="email"
                                                   class="form-control"
                                                   id="email"
                                                   required
                                                   placeholder="admin@rumahsakit.com"
                                                   maxlength="255">
                                        </div>
                                        <div class="invalid-feedback" id="err-email"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telepon" class="form-label fw-medium">
                                            <i class="bi bi-telephone me-1"></i>Telepon
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-telephone"></i>
                                            </span>
                                            <input type="tel"
                                                   class="form-control"
                                                   id="telepon"
                                                   required
                                                   placeholder="021-1234567 atau 0811234567"
                                                   maxlength="20">
                                        </div>
                                        <div class="invalid-feedback" id="err-telepon"></div>
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
                                          placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi"
                                          maxlength="500"></textarea>
                                <div class="invalid-feedback" id="err-alamat"></div>
                                <small class="text-muted">Alamat lengkap termasuk jalan, kelurahan, kecamatan, dan kota (maksimal 500 karakter)</small>
                            </div>

                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>
                                    <strong>Petunjuk:</strong> Pastikan semua data yang dimasukkan sudah benar.
                                    Email akan digunakan untuk komunikasi resmi dengan rumah sakit.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary" data-original-text="Simpan">
                                <i class="bi bi-check-circle me-2"></i>Simpan
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
    <script src="{{ asset('js/modules/rumah-sakit-crud.js') }}"></script>
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
        });
    </script>
    @endpush
</x-layout_admin>
