<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Nikel Fleet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f5f6fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .08);
        }

        .login-header {
            background: #1e3a5f;
            border-radius: 12px 12px 0 0;
            padding: 2rem;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="login-card card">
        <div class="login-header">
            <i class="bi bi-truck text-white" style="font-size:2rem"></i>
            <h5 class="text-white mt-2 mb-0">Nikel Fleet</h5>
            <small class="text-white-50">Vehicle Booking System</small>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="nama@perusahaan.com" autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="background:#1e3a5f;border-color:#1e3a5f">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </button>
            </form>

            <hr class="my-3">
            <small class="text-muted d-block text-center">Demo Akun:</small>
            <small class="text-muted d-block text-center">admin@demo.com / approver1@demo.com</small>
            <small class="text-muted d-block text-center">Password: <code>password123</code></small>
        </div>
    </div>
</body>

</html>
