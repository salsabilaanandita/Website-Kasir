@extends('layouts.auth')
@section('title', 'Masuk')

@push('styles')
<style>
    .login-card {
        background: #ffffff;
        border: 1px solid #d2d2d7;
        border-radius: 18px;
        padding: 40px 36px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        text-align: left;
    }

    .login-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .login-brand-icon {
        width: 48px;
        height: 48px;
        background: #1d1d1f;
        color: #ffffff;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 16px;
    }

    .login-title {
        font-size: 22px;
        font-weight: 600;
        color: #1d1d1f;
        letter-spacing: -0.015em;
        margin-bottom: 6px;
    }

    .login-subtitle {
        font-size: 14px;
        color: #86868b;
        font-weight: 400;
    }

    /* Alert */
    .login-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #fff2f2;
        border: 1px solid #ffccd0;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 24px;
        color: #d70015;
        font-size: 13px;
        line-height: 1.4;
    }

    .login-alert i {
        font-size: 15px;
        margin-top: 1px;
        flex-shrink: 0;
    }

    .login-alert.alert-info {
        background: #f0f6ff;
        border-color: #cce0ff;
        color: #0066cc;
    }

    /* Form Fields */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #1d1d1f;
        margin-bottom: 6px;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-input {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        font-size: 14px;
        font-family: inherit;
        color: #1d1d1f;
        background: #ffffff;
        border: 1px solid #d2d2d7;
        border-radius: 10px;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-input.has-toggle {
        padding-right: 44px;
    }

    .form-input::placeholder {
        color: #86868b;
    }

    .form-input:focus {
        border-color: #0071e3;
        box-shadow: 0 0 0 3px rgba(0, 113, 227, 0.15);
    }

    .toggle-password-btn {
        position: absolute;
        right: 10px;
        width: 30px;
        height: 30px;
        background: none;
        border: none;
        color: #86868b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        border-radius: 6px;
        transition: color 0.15s;
    }

    .toggle-password-btn:hover {
        color: #1d1d1f;
    }

    /* Options */
    .form-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        font-size: 13px;
    }

    .remember-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #515154;
        cursor: pointer;
        user-select: none;
    }

    .remember-label input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #0071e3;
        cursor: pointer;
    }

    /* Submit Button (Apple Action Blue / Pill) */
    .btn-login {
        width: 100%;
        height: 44px;
        background-color: #0071e3;
        color: #ffffff;
        border: none;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 500;
        font-family: inherit;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background-color 0.15s ease, transform 0.1s ease;
    }

    .btn-login:hover {
        background-color: #0077ed;
    }

    .btn-login:active {
        transform: scale(0.98);
        background-color: #0062c4;
    }

    .login-footer {
        text-align: center;
        margin-top: 24px;
        font-size: 12px;
        color: #86868b;
    }

    @media (max-width: 480px) {
        .login-card {
            padding: 30px 22px;
            border-radius: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="login-card">
    <div class="login-header">
        <div class="login-brand-icon">
            <i class="fas fa-cash-register"></i>
        </div>
        <h1 class="login-title">
            {{ \App\Models\Setting::get('nama_toko', 'Kasir App') }}
        </h1>
        <p class="login-subtitle">Masuk ke sistem kasir</p>
    </div>

    {{-- Error Alerts --}}
    @if ($errors->any())
    <div class="login-alert">
        <i class="fas fa-circle-exclamation"></i>
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="login-alert">
        <i class="fas fa-circle-exclamation"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if (session('status'))
    <div class="login-alert alert-info">
        <i class="fas fa-circle-info"></i>
        <span>{{ session('status') }}</span>
    </div>
    @endif

    <form method="POST" action="{{ url('/login') }}" id="loginForm">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <div class="input-wrapper">
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="email"
                    class="form-input"
                    placeholder="nama@email.com">
            </div>
        </div>

        <div class="form-group">
            <label for="passwordInput" class="form-label">Password</label>
            <div class="input-wrapper">
                <input 
                    type="password" 
                    id="passwordInput" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    class="form-input has-toggle"
                    placeholder="••••••••">
                <button type="button" onclick="togglePassword()" class="toggle-password-btn" aria-label="Lihat Password">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <div class="form-options">
            <label class="remember-label">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <span>Ingat saya</span>
            </label>
        </div>

        <button type="submit" class="btn-login" id="submitBtn">
            <span>Masuk</span>
            <i class="fas fa-arrow-right"></i>
        </button>
    </form>
</div>

<div class="login-footer">
    &copy; {{ date('Y') }} {{ \App\Models\Setting::get('nama_toko', 'Kasir App') }}. All rights reserved.
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

document.getElementById('loginForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    if (btn) {
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> <span>Memproses...</span>';
        btn.style.opacity = '0.8';
        btn.style.pointerEvents = 'none';
    }
});
</script>
@endpush
