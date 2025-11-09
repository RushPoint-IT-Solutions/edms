@extends('layouts.app')

@section('content')
<style>
    .reset-container {
        display: flex;
        height: 100vh;
        width: 100%;
        font-family: 'Montserrat', sans-serif;
        position: relative;
    }
    
    .back-button {
        position: absolute;
        top: 30px;
        left: 0;
        padding: 10px 30px;
        background: white;
        color: #6b1a1a;
        border: 2px solid #6b1a1a;
        border-left: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        z-index: 10;
        border-bottom-right-radius: 20px;
        border-top-right-radius: 20px;
    }

    .back-button:hover {
        background: #6b1a1a;
        color: white;
        text-decoration: none;
    }
    
    .reset-left {
        flex: 1;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
    }
    
    .reset-right {
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
        margin-bottom: 35px;
    }
    
    .reset-title {
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }
    
    .reset-subtitle {
        font-size: 14px;
        color: #666;
        margin-bottom: 40px;
        text-align: center;
        max-width: 350px;
    }
    
    .reset-form {
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
    
    .alert {
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        position: relative;
    }
    
    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
    
    .alert .close {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
        opacity: 0.5;
    }
    
    .alert .close:hover {
        opacity: 1;
    }
    
}
    
    .back-login:hover {
        text-decoration: underline;
        color: #6b1a1a;
    }
    
    .btn-reset {
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
    
    .btn-reset:hover {
        background: #4a0e0e;
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
    
    .info-text {
        font-size: 20px;
        margin-bottom: 15px;
        font-weight: 300 !important;
        text-align: center;
    }
    
    @media (max-width: 768px) {
        .reset-container {
            flex-direction: column;
        }
        
        .reset-right {
            order: -1;
            min-height: 300px;
        }
        
        .back-button {
            top: 15px;
        }
        
        .system-title {
            font-size: 32px;
        }
        
        .system-subtitle {
            font-size: 20px;
        }
    }
</style>

<div class="reset-container">
    <a href="{{ route('login') }}" class="back-button">
        BACK
    </a>
    
    <div class="reset-left">
        <div class="logo-placeholder">
            <img src="{{asset('assets/images/marsu-logo.png')}}" alt="" height="120">
        </div>
        
        <h1 class="reset-title">Reset Password</h1>
        <p class="reset-subtitle">Please enter your username</p>
        
        @if (session('status'))
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <strong>{{ session('status') }}</strong>
        </div>
        @endif
        
        @if($errors->any())
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <strong>{{$errors->first()}}</strong>
        </div>
        @endif
        
        <form method="POST" action="{{ route('password.email') }}" class="reset-form" onsubmit='show()'>
            @csrf
            
            <input 
                id="email" 
                type="email" 
                class="form-input" 
                name="email" 
                value="" 
                placeholder="Email Address"
                required 
                autofocus
            >
            
            <button type="submit" class="btn-reset">
                RESET PASSWORD
            </button>
        </form>
    </div>
    
    <div class="reset-right">
        <div class="right-logo-placeholder">
            <img src="{{asset('assets/images/marsu-logo.png')}}" alt="" height="120">
        </div>
        
        <h2 class="system-title">MARSU</h2>
        <h3 class="system-subtitle">Document Management System</h3>
        
        <p class="info-text">"Your secure hub for managing <br> and accessing MARSU <br> documents"</p>
    </div>
</div>
@endsection