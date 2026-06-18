@extends('layouts.app')

@section('content')
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    .login-wrapper {
        min-height: 100vh;
        width: 100%;
        display: flex;
        align-items: stretch;
        font-family: 'Montserrat', sans-serif;
    }

    .login-left {
        flex: 1;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: clamp(24px, 5vw, 60px) clamp(20px, 4vw, 50px);
        min-width: 0;
    }

    .logo-marsu {
        width: clamp(70px, 12vw, 100px);
        height: clamp(70px, 12vw, 100px);
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: clamp(12px, 2.5vw, 20px);
    }

    .logo-marsu img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .welcome-title {
        font-size: clamp(22px, 4vw, 36px);
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
        text-align: center;
    }

    .welcome-subtitle {
        font-size: clamp(12px, 1.5vw, 14px);
        color: #666;
        margin-bottom: clamp(20px, 4vw, 40px);
        text-align: center;
    }

    .login-form {
        width: 100%;
        max-width: 360px;
    }

    .form-input {
        width: 100%;
        padding: 11px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 14px;
        font-size: 14px;
        transition: border-color 0.3s;
        font-family: 'Montserrat', sans-serif;
        background: #fff;
        color: #333;
    }

    .form-input:focus {
        outline: none;
        border-color: #6b1a1a;
    }

    .form-input.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 12px;
        margin-top: -10px;
        margin-bottom: 12px;
        display: block;
    }

    .forgot-password {
        font-size: 13px;
        color: #333;
        text-decoration: none;
        margin-bottom: 14px;
        display: inline-block;
    }

    .forgot-password:hover {
        text-decoration: underline;
        color: #6b1a1a;
    }

    .btn-signin {
        width: 100%;
        padding: 12px;
        background: #6b1a1a;
        color: white;
        border: none;
        border-radius: 15px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        margin-bottom: 14px;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-signin:hover {
        background: #4a0e0e;
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 12px 0;
        color: #666;
        font-size: 13px;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e0e0e0;
    }

    .divider span {
        padding: 0 14px;
        font-weight: 500;
    }

    .btn-google {
        width: 100%;
        padding: 11px 18px;
        background: white;
        color: #333;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        font-family: 'Montserrat', sans-serif;
    }

    .btn-google:hover {
        background: #f8f8f8;
        border-color: #d0d0d0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        text-decoration: none;
        color: #333;
    }

    .google-icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    .login-right {
        flex: 1;
        background: linear-gradient(135deg, #6b1a1a 0%, #4a0e0e 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: clamp(24px, 5vw, 60px) clamp(20px, 4vw, 50px);
        color: white;
        border-top-left-radius: 50px;
        border-bottom-left-radius: 50px;
        min-width: 0;
        text-align: center;
    }

    .right-logo-placeholder {
        width: clamp(80px, 14vw, 120px);
        height: clamp(80px, 14vw, 120px);
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: clamp(16px, 3vw, 28px);
        overflow: hidden;
    }

    .right-logo-placeholder img {
        width: 90%;
        height: 90%;
        object-fit: contain;
    }

    .system-title {
        font-size: clamp(24px, 4.5vw, 36px);
        font-weight: 700;
        letter-spacing: 3px;
        margin-bottom: 6px;
    }

    .system-subtitle {
        font-size: clamp(16px, 2.8vw, 28px);
        font-weight: 600;
        margin-bottom: clamp(16px, 3vw, 28px);
    }

    .signup-text {
        font-size: clamp(13px, 1.8vw, 18px);
        margin-bottom: 0;
        font-weight: 300;
        line-height: 1.7;
        opacity: 0.9;
    }

    @media (max-width: 900px) {
        .login-right {
            border-top-left-radius: 35px;
            border-bottom-left-radius: 35px;
        }
    }

    @media (max-width: 640px) {
        .login-wrapper {
            flex-direction: column;
        }

        .login-right {
            order: -1;
            border-radius: 0 0 40px 40px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            padding: 36px 24px;
            min-height: 260px;
        }

        .login-left {
            padding: 32px 24px 40px;
        }

        .login-form {
            max-width: 100%;
        }

        .system-title {
            font-size: 28px;
        }

        .system-subtitle {
            font-size: 16px;
        }

        .signup-text {
            font-size: 13px;
        }
    }

    @media (max-width: 360px) {
        .login-right {
            padding: 28px 16px;
        }

        .login-left {
            padding: 24px 16px 32px;
        }
    }
</style>

<div class="login-wrapper">
    <div class="login-left">
        <div class="logo-marsu">
            <img src="{{ asset('assets/images/marsu-logo.png') }}" alt="MarSU Logo">
        </div>

        <h1 class="welcome-title">Welcome Back!!</h1>
        <p class="welcome-subtitle">Please enter your credentials to log in</p>

        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf

            <input
                id="email"
                type="email"
                class="form-input{{ $errors->has('email') ? ' is-invalid' : '' }}"
                name="email"
                value="{{ old('email') }}"
                placeholder="Email Address"
                required
                autofocus
            >
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif

            <input
                id="password"
                type="password"
                class="form-input{{ $errors->has('password') ? ' is-invalid' : '' }}"
                name="password"
                placeholder="Password"
                required
            >
            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif

            @if (Route::has('password.request'))
                <a class="forgot-password" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif

            <button type="submit" class="btn-signin">SIGN IN</button>

            <div class="divider"><span>Sign In with</span></div>

            <a href="{{ url('auth/google') }}" class="btn-google">
                <svg class="google-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Continue with Google
            </a>
        </form>
    </div>

    <div class="login-right">
        <div class="right-logo-placeholder">
            <img src="{{ asset('assets/images/marsu-logo.png') }}" alt="MarSU Logo">
        </div>

        <h2 class="system-title">MARSU</h2>
        <h3 class="system-subtitle">Document Management System</h3>

        <p class="signup-text">
            "Your secure hub for managing<br>
            and accessing MARSU<br>
            documents"
        </p>
    </div>
</div>
@endsection