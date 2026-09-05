<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Innotech Medical</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(\App\Models\Setting::get('favicon_path', 'assets/img/logo/favicon.png')) }}">
    
    <!-- Google Font & Bootstrap -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome-pro.css') }}">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0A192F 0%, #002244 50%, #0E63FF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            max-width: 440px;
            width: 100%;
            overflow: hidden;
        }

        .login-header {
            background: #002244;
            padding: 35px 30px;
            text-align: center;
            color: #fff;
        }

        .login-header img {
            max-height: 50px;
            margin-bottom: 12px;
        }

        .login-body {
            padding: 35px 30px;
        }

        .btn-theme-login {
            background: #0E63FF;
            color: #fff;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            border: none;
            width: 100%;
            font-size: 15px;
            transition: all 0.2s;
        }

        .btn-theme-login:hover {
            background: #094ecc;
            color: #fff;
            box-shadow: 0 6px 16px rgba(14, 99, 255, 0.35);
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid #E2E8F0;
        }

        .form-control:focus {
            border-color: #0E63FF;
            box-shadow: 0 0 0 3px rgba(14, 99, 255, 0.15);
        }

        .demo-badge {
            background: #EBF2FE;
            color: #0E63FF;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('assets/img/logo/white-logo.png') }}" alt="Innotech Medical">
            <h5 class="mb-1 text-white font-weight-bold">Admin Portal</h5>
            <small class="text-light opacity-75">Sign in to manage website content and inquiries</small>
        </div>

        <div class="login-body">
            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger p-2 small mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success p-2 small mb-3">
                    {{ session('success') }}
                </div>
            @endif

            <form id="loginForm" action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label font-weight-bold" style="font-size: 14px;">Email or Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-user-shield text-muted"></i></span>
                        <input type="text" name="login" class="form-control" placeholder="Enter email or phone number" value="{{ old('login', old('email', '')) }}" required autofocus autocomplete="username">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label font-weight-bold" style="font-size: 14px;">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" value="" required autocomplete="current-password">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" id="loginSubmitBtn" class="btn-theme-login d-flex align-items-center justify-content-center">
                    <span id="loginBtnText">Access Dashboard <i class="fa-solid fa-arrow-right ms-2"></i></span>
                    <span id="loginBtnSpinner" class="d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Signing in...
                    </span>
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-muted small text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> Back to Website</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginSubmitBtn');
            const btnText = document.getElementById('loginBtnText');
            const btnSpinner = document.getElementById('loginBtnSpinner');

            // Show spinner & disable button
            btn.disabled = true;
            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');
        });
    </script>
</body>
</html>
