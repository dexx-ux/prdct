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

<div id="loginLoadingOverlay" class="login-loading-overlay" aria-hidden="true">
    <div class="loader-wrapper">
        <div class="center-group">
            <div class="pulse-glow"></div>
            <div class="ring-primary"></div>
            <div class="ring-secondary"></div>
            <div class="ring-tertiary"></div>
            <div class="orbiting-dot"></div>
            <div class="orbiting-dot-delayed"></div>
            <img src="{{ asset('images/logos.png') }}" class="loader-logo" alt="Loading logo"
                 onerror="this.src='https://placehold.co/140x140/1a2c3e/white?text=LOGO'; this.style.borderRadius='12px';">
        </div>
        <div class="loading-text">LOADING...</div>
    </div>
</div>

<style>
/* Login loading overlay styles */

#loginLoadingOverlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 99999;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle at 50% 50%, #0f1a24, #03080f);
    padding: 24px;
}

#loginLoadingOverlay.active {
    display: flex;
}

#loginLoadingOverlay .loader-wrapper {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    max-width: 360px;
    gap: 32px;
}

#loginLoadingOverlay .center-group {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 280px;
    height: 280px;
}

#loginLoadingOverlay .loader-logo {
    width: 120px;
    height: auto;
    z-index: 15;
    filter: drop-shadow(0 0 18px rgba(26, 44, 62, 0.7));
    object-fit: contain;
    border-radius: 50%;
}

#loginLoadingOverlay .ring-primary,
#loginLoadingOverlay .ring-secondary,
#loginLoadingOverlay .ring-tertiary,
#loginLoadingOverlay .pulse-glow,
#loginLoadingOverlay .orbiting-dot,
#loginLoadingOverlay .orbiting-dot-delayed {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

#loginLoadingOverlay .ring-primary {
    width: 240px;
    height: 240px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: #1a2c3e;
    border-right-color: #1a2c3e;
    animation: spinnerRotate 1.1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
    box-shadow: 0 0 8px rgba(26, 44, 62, 0.5);
}

#loginLoadingOverlay .ring-secondary {
    width: 258px;
    height: 258px;
    border-radius: 50%;
    border: 2.5px solid transparent;
    border-bottom-color: #1a2c3e;
    border-left-color: #1a2c3e;
    animation: spinnerRotateReverse 1.6s linear infinite;
    opacity: 0.85;
}

#loginLoadingOverlay .ring-tertiary {
    width: 276px;
    height: 276px;
    border-radius: 50%;
    border: 1.8px solid rgba(26, 44, 62, 0.7);
    border-top-color: #1a2c3e;
    border-right-color: rgba(26, 44, 62, 0.5);
    animation: spinnerRotate 2.4s linear infinite;
    opacity: 0.7;
}

#loginLoadingOverlay .pulse-glow {
    width: 310px;
    height: 310px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(26, 44, 62, 0.3), rgba(26, 44, 62, 0) 75%);
    animation: gentlePulse 2.2s ease-in-out infinite;
    pointer-events: none;
    z-index: 0;
}

#loginLoadingOverlay .orbiting-dot,
#loginLoadingOverlay .orbiting-dot-delayed {
    width: 10px;
    height: 10px;
    background: #1a2c3e;
    border-radius: 50%;
    box-shadow: 0 0 12px #2c4a6e;
    z-index: 6;
}

#loginLoadingOverlay .orbiting-dot {
    animation: orbitRotate 3.2s linear infinite;
}

#loginLoadingOverlay .orbiting-dot-delayed {
    width: 8px;
    height: 8px;
    animation: orbitRotateDelayed 3.8s linear infinite;
    opacity: 0.9;
}

#loginLoadingOverlay .loading-text {
    font-size: 0.95rem;
    font-weight: 600;
    letter-spacing: 5px;
    text-transform: uppercase;
    font-family: 'Segoe UI', 'Inter', monospace;
    background: linear-gradient(135deg, #e0ecff, #8ba3bc);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    animation: textPulse 1.6s infinite alternate;
    text-shadow: 0 0 6px rgba(26, 44, 62, 0.4);
}

@keyframes spinnerRotate {
    100% {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}

@keyframes spinnerRotateReverse {
    100% {
        transform: translate(-50%, -50%) rotate(-360deg);
    }
}

@keyframes gentlePulse {
    0% {
        transform: translate(-50%, -50%) scale(0.92);
        opacity: 0.4;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.07);
        opacity: 0.18;
    }
    100% {
        transform: translate(-50%, -50%) scale(0.92);
        opacity: 0.4;
    }
}

@keyframes orbitRotate {
    0% { transform: translate(-50%, -50%) rotate(0deg) translateX(148px) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg) translateX(148px) rotate(-360deg); }
}

@keyframes orbitRotateDelayed {
    0% { transform: translate(-50%, -50%) rotate(45deg) translateX(160px) rotate(-45deg); }
    100% { transform: translate(-50%, -50%) rotate(405deg) translateX(160px) rotate(-405deg); }
}

@keyframes textPulse {
    0% {
        opacity: 0.7;
        letter-spacing: 4px;
        background-position: 0% 50%;
    }
    100% {
        opacity: 1;
        letter-spacing: 6px;
        background-position: 100% 50%;
    }
}

body.login-loading-active {
    overflow: hidden;
}

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

    const loginForm = document.getElementById('loginForm');
    const overlay = document.getElementById('loginLoadingOverlay');
    const submitButton = loginForm?.querySelector('button[type="submit"]');

    if (loginForm && overlay) {
        loginForm.addEventListener('submit', () => {
            overlay.classList.add('active');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.classList.add('login-loading-active');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerText = 'Signing in...';
            }
        });
    }
});
</script>

</x-guest-layout>