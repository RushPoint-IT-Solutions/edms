@extends('layouts.app')

@section('content')
<style>
    .login-container {
        display: flex;
        height: 100vh;
        width: 100%;
        font-family: 'Montserrat', sans-serif;
    }
    
    .login-left {
        flex: 1;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
    }
    
    .login-right {
        flex: 1;
        background: linear-gradient(135deg, #6b1a1a 0%, #4a0e0e 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
        color: white;
        border-top-left-radius: 50px;
        border-bottom-left-radius: 50px;
    }

    
    .logo-placeholder {
        width: 100px;
        height: 100px;
        background: #f0f0f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #999;
        text-align: center;
        margin-bottom: 20px;
    }
    
    .welcome-title {
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 25px;
        color: #333;
    }
    
    .welcome-subtitle {
        font-size: 14px;
        color: #666;
        margin-bottom: 40px;
    }
    
    .login-form {
        width: 100%;
        max-width: 350px;
    }
    
    .form-input {
        width: 100%;
        padding: 12px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 15px;
        font-size: 14px;
        transition: border-color 0.3s;
        box-sizing: border-box;
        font-family: 'Montserrat', sans-serif;
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
        margin-top: -15px;
        margin-bottom: 15px;
        display: block;
    }
    
    .forgot-password {
        font-size: 13px;
        color: #333;
        text-decoration: none;
        margin-bottom: 15px;
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
        margin-bottom: 15px;
    }
    
    .btn-signin:hover {
        background: #4a0e0e;
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 15px 0;
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
        padding: 0 15px;
        font-weight: 500;
    }

    .btn-google {
        width: 100%;
        padding: 12px 20px;
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
    }

    .btn-google:hover {
        background: #f8f8f8;
        border-color: #d0d0d0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-decoration: none;
        color: #333;
    }

    .google-icon {
        width: 20px;
        height: 20px;
    }
    
    .right-logo-placeholder {
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.5);
        text-align: center;
        margin-bottom: 25px;
    }
    
    .system-title {
        font-size: 32px;
        font-weight: 700;
        letter-spacing: 2px;
        margin-bottom: 25px;
    }
    
    .system-subtitle {
        font-size: 32px;
        font-weight: 700;
        margin-top: -20px;
        margin-bottom: 45px;
    }
    
    .signup-text {
        font-size: 20px;
        margin-bottom: 15px;
        font-weight: 300 !important;
        text-align: center;
    }
    
    .btn-signup {
        padding: 12px 60px;
        background: white;
        color: #6b1a1a;
        border: none;
        border-radius: 15px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-signup:hover {
        background: #f0f0f0;
        color: #6b1a1a;
        text-decoration: none;
    }
    
    @media (max-width: 768px) {
        .login-container {
            flex-direction: column;
        }
        
        .login-right {
            order: -1;
            min-height: 300px;
        }
        
        .system-title {
            font-size: 32px;
        }
        
        .system-subtitle {
            font-size: 20px;
        }
    }
</style>

<div class="login-container">
    <div class="login-left">
        <div class="logo-placeholder">
            <img src="{{asset('assets/images/marsu-logo.png')}}" alt="" height="120">
        </div>
        
        <h1 class="welcome-title">Welcome Back !!</h1>
        <p class="welcome-subtitle">Please enter your credentials to log in</p>
        
        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf
            
            <input 
                id="email" 
                type="email" 
                class="form-input{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                name="email" 
                value="{{ old('email') }}" 
                placeholder="Username"
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
            
            <button type="submit" class="btn-signin">
                SIGN IN
            </button>

            <div class="divider">
                <span>Sign In with</span>
            </div>

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
            <img src="{{asset('assets/images/marsu-logo.png')}}" alt="" height="120">
        </div>
        
        <h2 class="system-title">MARSU</h2>
        <h3 class="system-subtitle">Document Management System</h3>
        
        <p class="signup-text">"Your secure hub for managing <br> and accessing MARSU <br> documents"</p>
    </div>
</div>
@endsection