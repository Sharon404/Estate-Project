<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Estate Project</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/velzon/css/bootstrap.min.css') }}">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{ asset('assets/velzon/css/icons.min.css') }}">

    <style>
        :root {
            --bs-primary: #4680ff;
            --bs-danger: #f46a6a;
        }

        html,
        body {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 1.5rem;
        }

        .login-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.15);
            padding: 2rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-wrapper {
            margin-bottom: 1.5rem;
        }

        .logo-wrapper img {
            height: 50px;
            display: inline-block;
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #323238;
            margin: 0.5rem 0 0.25rem;
        }

        .login-header p {
            color: #999;
            font-size: 0.95rem;
            margin: 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #323238;
            font-size: 0.95rem;
        }

        .form-control {
            border: 1px solid #e9ecef;
            border-radius: 0.25rem;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(70, 128, 255, 0.25);
            outline: none;
        }

        .form-control::placeholder {
            color: #ccc;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 0.5rem;
            cursor: pointer;
            accent-color: var(--bs-primary);
        }

        .form-check-label {
            margin: 0;
            cursor: pointer;
            color: #323238;
            font-size: 0.95rem;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--bs-primary);
            color: white;
            border: none;
            border-radius: 0.25rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #3568dd;
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(70, 128, 255, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.25rem;
            font-size: 0.9rem;
        }

        .alert-danger {
            background-color: #fff5f5;
            color: #f46a6a;
            border: 1px solid #f46a6a;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #34c38f;
            border: 1px solid #34c38f;
        }

        .invalid-feedback {
            display: block;
            color: var(--bs-danger);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .form-control.is-invalid {
            border-color: var(--bs-danger);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(244, 106, 106, 0.25);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
            color: #999;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: var(--bs-primary);
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .loader {
            display: none;
            width: 16px;
            height: 16px;
            margin-right: 0.5rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spinner 0.6s linear infinite;
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-login.loading {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-login.loading .loader {
            display: inline-block;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1rem;
            }

            .login-card {
                padding: 1.5rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="logo-wrapper">
                    <img src="{{ asset('assets/velzon/images/logo-dark.png') }}" alt="Logo">
                </div>
                <h1>Admin Login</h1>
                <p>Estate Project Management System</p>
            </div>

            <!-- Display Messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Login Failed</strong>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        placeholder="admin@example.com"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="remember" 
                        name="remember"
                    >
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="loader"></span>
                    <span class="btn-text">Sign In</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="login-footer">
                <p style="margin: 0;">
                    Admin access only • Contact support for credentials
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });
    </script>
</body>
</html>
