<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory Portal</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --dark-bg: #0f172a;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            background-image: radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.15) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, rgba(124, 58, 237, 0.15) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-card {
            background-color: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            color: #ffffff;
        }

        .login-title {
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .form-control {
            background-color: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: rgba(15, 23, 42, 0.8);
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
            color: #ffffff;
        }

        .form-label {
            color: #94a3b8;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .btn-login {
            background: var(--primary-gradient);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            color: #ffffff;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-dismissible .btn-close {
            filter: invert(1);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <div class="d-inline-flex p-3 rounded-4 mb-3" style="background-color: rgba(99, 102, 241, 0.15);">
                <i class="bi bi-boxes fs-1" style="color: #818cf8;"></i>
            </div>
            <h2 class="login-title">Inventory Portal</h2>
            <p class="text-muted">Sign in to manage your spaces and materials</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 text-white" role="alert" style="background-color: rgba(25, 135, 84, 0.2); border-left: 4px solid #198754;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="magasinier@example.com">
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me" style="background-color: rgba(15, 23, 42, 0.5); border-color: rgba(255, 255, 255, 0.1);">
                <label class="form-check-label text-muted" for="remember_me">
                    Remember my credentials
                </label>
            </div>

            <button type="submit" class="btn btn-login w-100 mt-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="text-center mt-4">
            <div class="text-muted small">
                <strong>Chef Magasinier:</strong> <code>chef@example.com</code> / <code>Admin123!</code><br>
                <strong>Magasinier:</strong> <code>magasinier@example.com</code> / <code>Admin123!</code>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
