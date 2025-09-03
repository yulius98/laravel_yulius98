<x-layout>
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="bi bi-shield-lock text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="h4 mb-2 fw-bold text-primary">Masuk ke Akun Anda</h2>
                        <p class="text-muted small">Silakan masukkan username dan password Anda untuk melanjutkan</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('auth.login') }}" method="POST" id="loginForm" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label fw-medium">
                                <i class="bi bi-person-circle me-1"></i>Username
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text"
                                       name="username"
                                       id="username"
                                       class="form-control @error('username') is-invalid @enderror"
                                       placeholder="Masukkan username Anda"
                                       value="{{ old('username') }}"
                                       required
                                       autocomplete="username"
                                       autofocus>
                                <div class="invalid-feedback" id="username-error"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">
                                <i class="bi bi-lock me-1"></i>Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Masukkan password Anda"
                                       required
                                       autocomplete="current-password">
                                        <div class="input-group-text" id="togglePassword"
                                             style="cursor: pointer; user-select: none; background: #f8f9fa; border-color: #ced4da;"
                                             title="Tampilkan Password">
                                            <i class="bi bi-eye" id="toggleIcon"></i>
                                            <span id="toggleText" class="d-none">👁️</span>
                                        </div>
                                <div class="invalid-feedback" id="password-error"></div>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg" id="loginButton">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                <span id="loginText">Masuk</span>
                            </button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .card {
            border-radius: 1rem;
            overflow: hidden;
        }

        .input-group-text {
            border-right: none;
            background-color: #f8f9fa;
        }

        .form-control {
            border-left: none;
        }

        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .input-group:focus-within .input-group-text {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Fix for toggle password button */
        #togglePassword {
            border-left: none;
            padding: 0.375rem 0.75rem;
        }

        #togglePassword:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Ensure icons are visible */
        .bi {
            display: inline-block;
            width: 1em;
            height: 1em;
            vertical-align: -0.125em;
            fill: currentColor;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            padding: 0.75rem 1rem;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
        }

        .alert {
            border-radius: 0.5rem;
            border: none;
        }

        .text-primary {
            color: #0d6efd !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Bootstrap Icons are loaded
            function areBootstrapIconsLoaded() {
                const testElement = document.createElement('i');
                testElement.className = 'bi bi-eye';
                document.body.appendChild(testElement);
                const hasContent = window.getComputedStyle(testElement, ':before').content !== 'none';
                document.body.removeChild(testElement);
                return hasContent;
            }

            // Fallback for icons if Bootstrap Icons don't load
            if (!areBootstrapIconsLoaded()) {
                console.warn('Bootstrap Icons not loaded, using text fallbacks');
                const toggleIcon = document.getElementById('toggleIcon');
                const toggleText = document.getElementById('toggleText');
                if (toggleIcon && toggleText) {
                    toggleIcon.style.display = 'none';
                    toggleText.classList.remove('d-none');
                }
            }

            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            const toggleText = document.getElementById('toggleText');

            if (togglePassword && passwordField && toggleIcon) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);

                    // Update icon
                    if (type === 'password') {
                        toggleIcon.className = 'bi bi-eye';
                        if (toggleText) toggleText.textContent = '👁️';
                    } else {
                        toggleIcon.className = 'bi bi-eye-slash';
                        if (toggleText) toggleText.textContent = '🙈';
                    }

                    // Update tooltip
                    togglePassword.title = type === 'password' ? 'Tampilkan Password' : 'Sembunyikan Password';
                });
            }

            // Form validation
            const form = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const loginText = document.getElementById('loginText');

            if (form && loginButton && loginText) {
                form.addEventListener('submit', function(e) {
                    let isValid = true;

                    // Clear previous errors
                    document.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });

                    // Validate username
                    const username = document.getElementById('username');
                    const usernameError = document.getElementById('username-error');
                    if (username && !username.value.trim()) {
                        username.classList.add('is-invalid');
                        if (usernameError) usernameError.textContent = 'Username wajib diisi';
                        isValid = false;
                    }

                    // Validate password
                    const password = document.getElementById('password');
                    const passwordError = document.getElementById('password-error');
                    if (password && !password.value.trim()) {
                        password.classList.add('is-invalid');
                        if (passwordError) passwordError.textContent = 'Password wajib diisi';
                        isValid = false;
                    } else if (password && password.value.length < 3) {
                        password.classList.add('is-invalid');
                        if (passwordError) passwordError.textContent = 'Password minimal 3 karakter';
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                        return false;
                    }

                    // Show loading state
                    loginButton.disabled = true;
                    loginText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                });

                // Real-time validation
                const username = document.getElementById('username');
                const password = document.getElementById('password');

                if (username) {
                    username.addEventListener('input', function() {
                        if (this.value.trim()) {
                            this.classList.remove('is-invalid');
                        }
                    });
                }

                if (password) {
                    password.addEventListener('input', function() {
                        if (this.value.trim()) {
                            this.classList.remove('is-invalid');
                        }
                    });
                }
            }
        });
    </script>
    @endpush
</x-layout>
