
<x-layout_admin>
    <x-slot:title>{{ $title }}</x-slot:title>
    @section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h1 class="card-title mb-3">Selamat Datang di Dashboard</h1>
                        <p class="card-text">Halo, <span class="fw-bold">{{ $username }}</span><br>Selamat datang di aplikasi manajemen data Rumah Sakit dan Pasien.</p>
                    </div>
                </div>

                <!-- Dashboard Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-hospital text-primary" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Total Rumah Sakit</h5>
                                <h3 class="text-primary">{{ $jmlrs }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-person-lines-fill text-success" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Total Pasien</h5>
                                <h3 class="text-success">{{ $jmlpasien }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-check text-info" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-2">Data Hari Ini</h5>
                                <h3 class="text-info">{{ date('d/m/Y') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Aksi Cepat</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <a href="{{ route('rumahsakit.index', ['user' => $username]) }}" class="btn btn-outline-primary w-100">
                                            <i class="bi bi-hospital me-2"></i> Kelola Data Rumah Sakit
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="{{ route('pasien.index', ['user' => $username]) }}" class="btn btn-outline-success w-100">
                                            <i class="bi bi-person-lines-fill me-2"></i> Kelola Data Pasien
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
</x-layout_admin>
