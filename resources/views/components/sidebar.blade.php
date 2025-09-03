<div class="d-flex flex-column shadow-lg p-4 sidebar-gradient" style="min-height:100vh; width:250px; border-radius: 0 1rem 1rem 0;">
    <div class="mb-5 text-center">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($dtuser->nameuser ?? $dtuser->username ?? $dtuser ?? 'User') }}&background=0D8ABC&color=fff&size=64" class="rounded-circle mb-2 border-3 border-white shadow" alt="User Avatar">
        <h5 class="text-white mb-1">{{ $slot }}</h5>

    </div>
    <ul class="nav nav-pills flex-column gap-2">
        <li class="nav-item">
            <a href="/dashboard/{{  $slot }}" class="nav-link text-white text-start d-flex align-items-center sidebar-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="/rumah-sakit/{{ $slot }}" class="nav-link text-white text-start d-flex align-items-center sidebar-link {{ request()->is('rumah-sakit*') ? 'active' : '' }}">
                <i class="bi bi-hospital me-2"></i> Data Rumah Sakit
            </a>
        </li>
        <li class="nav-item">
            <a href="/pasien/{{ $slot }}" class="nav-link text-white text-start d-flex align-items-center sidebar-link {{ request()->is('pasien*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill me-2"></i> Data Pasien
            </a>
        </li>
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="w-100">
                @csrf
                <button type="submit" class="nav-link text-white text-start d-flex align-items-center sidebar-link border-0 bg-transparent p-0 w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</div>
<style>
.sidebar-gradient {
    background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
    box-shadow: 0 4px 24px rgba(0,0,0,0.12);
}
.sidebar-link {
    transition: background 0.2s, color 0.2s;
    border-radius: 0.5rem;
}
.sidebar-link:hover, .sidebar-link.active {
    background: rgba(255,255,255,0.15);
    color: #fff;
}
</style>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
