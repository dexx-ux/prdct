<x-guest-layout>

<div class="container">

<!-- Add Back to Home Link -->
<div style="margin-bottom: 1rem; text-align: left;">
    <a href="{{ route('welcome') }}" style="color: #1a2c3e; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>
</div>


<div class="login-wrapper">

    <!-- Left Panel (Branding / Login Link) - MOVED TO LEFT -->
    <div class="register-panel">
        <h2>Welcome Back!</h2>
        <p>Already have an account? Login now to continue.</p>
        <a href="{{ route('login') }}">
            <button class="register-btn">Login</button>
        </a>
    </div>

    <!-- Registration Form Panel - NOW ON RIGHT -->
    <div class="login-card">
        <h1>Create Account</h1>
        <p class="subtitle">Sign up to get started</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <!-- Name -->
            <div class="input-group">
                <i class="fa fa-user"></i>
                <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required autofocus autocomplete="name">
            </div>

            <!-- Email -->
            <div class="input-group">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required autocomplete="username">
            </div>

           <!-- Password Field -->
            <div class="input-group password-field">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required autocomplete="new-password" id="password">
                <button type="button" class="eye-btn"><i class="fa fa-eye"></i></button>
            </div>
            <small class="password-hint" style="color:red; display:none;">Password must be at least 8 characters.</small>

            <!-- Confirm Password -->
            <div class="input-group password-field">
                <i class="fa fa-lock"></i>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password" id="password_confirmation">
                <button type="button" class="eye-btn"><i class="fa fa-eye"></i></button>
            </div>
            <small class="password-match-warning" style="color:red; display:none;">Passwords do not match!</small>

            <!-- Register Button -->
            <button type="submit" class="login-btn">Register</button>

            <!-- Link to Login -->
            <a href="{{ route('login') }}" class="forgot">Already have an account? Login</a>
            
        
        </form>
    </div>

</div>

<style>
/* Added flexbox to position panels side by side */
.login-wrapper {
    display: flex;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    max-width: 1000px;
    margin: 0 auto;
}

/* Left panel (blue container) */
.login-wrapper .register-panel {
    flex: 1;
    background: linear-gradient(135deg, #1a2c3e 0%, #0f1e2c 100%) !important;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
    color: #fff;
}

.login-wrapper .register-panel h2 {
    font-size: 28px;
    margin-bottom: 15px;
    font-weight: 600;
}

.login-wrapper .register-panel p {
    margin-bottom: 25px;
    line-height: 1.5;
    opacity: 0.9;
}

.login-wrapper .register-panel .register-btn {
    background: transparent;
    border: 2px solid #fff;
    color: #fff;
    padding: 10px 30px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.login-wrapper .register-panel .register-btn:hover {
    background: #fff;
    color: #1a2c3e;
}

/* Right panel (form) - keeping all original styles */
.login-wrapper .login-card {
    flex: 1;
    padding: 40px;
    background: #fff;
}

/* Original styles preserved exactly */
.login-wrapper .login-btn {
    background: #1a2c3e !important;
}
.login-wrapper .login-btn:hover {
    background: #0f1e2c !important;
}
.login-wrapper .input-group i {
    color: #1a2c3e !important;
}
.login-wrapper .forgot {
    color: #1a2c3e !important;
}
.login-wrapper .forgot:hover {
    color: #0f1e2c !important;
}
.back-link a {
    color: #1a2c3e !important;
}
.back-link a:hover {
    color: #0f1e2c !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .login-wrapper {
        flex-direction: column;
    }
}
</style>

<script src="{{ asset('js/auth.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const password = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');
    const hint = document.querySelector('.password-hint');
    const warning = document.querySelector('.password-match-warning');

    // ---------- Password length check ----------
    password.addEventListener('input', () => {
        if(password.value.length < 8){
            hint.style.display = 'block';
        } else {
            hint.style.display = 'none';
        }

        // Also check confirm password match dynamically
        if(confirm.value && confirm.value !== password.value){
            warning.style.display = 'block';
        } else {
            warning.style.display = 'none';
        }
    });

    // ---------- Confirm password match check ----------
    confirm.addEventListener('input', () => {
        if(confirm.value !== password.value){
            warning.style.display = 'block';
        } else {
            warning.style.display = 'none';
        }
    });

});
</script>

</x-guest-layout>