<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>Login - SATELITE_SISTEM</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <style>
        :root { --tblr-font-sans-serif: 'Inter var', sans-serif; }

        /* Tema Latar Belakang Luar Angkasa / Cyber */
        body {
            background-color: #0f172a;
            background-image:
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.15), transparent 40%),
                radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.1), transparent 40%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter var', sans-serif;
            padding: 2rem 0;
        }

        /* Kartu Form Login */
        .auth-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            margin: auto;
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Kartu (Logo) */
        .auth-header {
            background: #f8fafc;
            padding: 2.5rem 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
        }
        .brand-icon-wrapper {
            display: inline-flex; align-items: center; justify-content: center;
            width: 54px; height: 54px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 14px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            margin-bottom: 1.25rem;
        }
        .brand-logo-icon {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #93c5fd, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .brand-text-wrapper {
            font-size: 1.4rem; letter-spacing: 0.5px; display: flex; justify-content: center;
        }
        .brand-text-primary { color: #0f172a; font-weight: 800; }
        .brand-text-secondary { color: #3b82f6; font-weight: 300; margin-left: 2px; }

        /* Area Input */
        .auth-body { padding: 2rem; }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        /* Ikon Toggle Password */
        .toggle-password {
            cursor: pointer;
            z-index: 10;
            transition: color 0.2s ease;
        }
        .toggle-password:hover {
            color: #3b82f6 !important;
        }

        /* Tombol Utama */
        .btn-primary {
            background: linear-gradient(135deg, #206bc4, #3b82f6);
            border: none; border-radius: 10px;
            padding: 0.8rem 1rem; font-weight: 600; letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1a569d, #2563eb);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <div class="brand-icon-wrapper">
                <i class="fas fa-satellite-dish brand-logo-icon"></i>
            </div>
            <div class="brand-text-wrapper mb-2">
                <span class="brand-text-primary">SATELITE</span><span class="brand-text-secondary">_SISTEM</span>
            </div>
            <p class="text-muted mb-0 fw-medium small text-uppercase" style="letter-spacing: 1px;">Portal Akses Pusat Kendali</p>
        </div>

        <div class="auth-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark">Alamat Email</label>
                    <div class="input-icon mb-1">
                        <span class="input-icon-addon"><i class="fas fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email anda..." value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <span class="text-danger small fw-medium d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark d-flex justify-content-between align-items-center">
                        Kata Sandi
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small text-decoration-none fw-medium text-primary">Lupa Sandi?</a>
                        @endif
                    </label>

                    <div class="position-relative mb-1">
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" name="password" id="passwordField" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan sandi..." required style="padding-right: 2.5rem;">
                        </div>
                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-password" id="togglePasswordBtn" title="Tampilkan Sandi">
                            <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                        </span>
                    </div>

                    @error('password')
                        <span class="text-danger small fw-medium d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="form-check-label text-secondary fw-medium user-select-none">Ingat sesi saya di perangkat ini</span>
                    </label>
                </div>

                <div class="form-footer mt-5">
                    <button type="submit" class="btn btn-primary w-100 fs-4">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Sistem
                    </button>
                </div>
            </form>
        </div>

        @if (Route::has('register'))
        <div class="card-footer bg-light text-center py-4 border-top">
            <span class="text-muted fw-medium">Belum memiliki akun? <br>
                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none hover-primary mt-1 d-inline-block">Register</a>
            </span>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const passwordField = document.getElementById('passwordField');
            const toggleIcon = document.getElementById('toggleIcon');

            toggleBtn.addEventListener('click', function() {
                // Periksa tipe saat ini
                const isPassword = passwordField.type === 'password';

                // Ubah tipe input
                passwordField.type = isPassword ? 'text' : 'password';

                // Ubah ikon mata (fa-eye / fa-eye-slash)
                toggleIcon.className = isPassword ? 'fas fa-eye-slash text-primary' : 'fas fa-eye text-muted';

                // Ubah tooltip title
                toggleBtn.title = isPassword ? 'Sembunyikan Sandi' : 'Tampilkan Sandi';
            });
        });
    </script>
</body>
</html>
