<x-guest-layout>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="container">

<!-- Add Back to Home Link -->
<div style="margin-bottom: 1rem; text-align: left;">
    <a href="{{ route('welcome') }}" style="color: #1a2c3e; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>
</div>

<div class="login-wrapper">

<!-- LOGIN FORM -->
<div class="login-card">
    <h1>Welcome Back</h1>
    <p class="subtitle">Login to your account</p>

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf  
        <!-- Name/Email Field -->
        <div class="input-group">
            <i class="fa fa-user"></i>
            <input type="text" name="email" placeholder="Email" required>
        </div>

        <!-- Password Field -->
        <div class="input-group password-field">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
            <button type="button" class="eye-btn"><i class="fa fa-eye"></i></button>
        </div>

        <!-- Remember Me + Forgot Password -->
        <div class="login-options">
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
            <a href="{{ route('password.request') }}" class="forgot">Forgot Password?</a>
        </div>

        <!-- Login Button -->
        <button type="submit" class="login-btn">Login</button>
        
      
    </form>
</div>

<!-- REGISTER PANEL -->
<div class="register-panel">
    <h2>New Here?</h2>
    <p>
        Create your account and start your journey with us.
    </p>
    <a href="{{ route('register') }}">
        <button class="register-btn">
            Register Now
        </button>
    </a>
 
</div>

</div>
</div>

<style>
/* ONLY CHANGED THE CONTAINER SIZE - EXACTLY MATCHING REGISTER PAGE */

.login-options label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: #666;
}

.login-options input[type="checkbox"] {
    width: auto;
    margin: 0;
    cursor: pointer;
    accent-color: #3b82f6;
}



.login-wrapper {
    display: flex;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    max-width: 1200px;  
    margin: 0 auto;
}

/* ALL ORIGINAL STYLES PRESERVED - NO OTHER CHANGES */
.login-wrapper .login-btn {
    background: #1a2c3e !important;
}
.login-wrapper .login-btn:hover {
    background: #0f1e2c !important;
}
.login-wrapper .register-panel {
    background: linear-gradient(135deg, #1a2c3e 0%, #0f1e2c 100%) !important;
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
</style>

<script src="{{ asset('js/auth.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Toggle password visibility
    const eyeButtons = document.querySelectorAll('.eye-btn');
    eyeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
});
</script>

</x-guest-layout>