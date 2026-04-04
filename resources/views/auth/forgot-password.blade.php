<x-guest-layout>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="login-wrapper">

    <!-- Reset Password Form -->
    <div class="login-card">
        <h1>Reset Password</h1>
        <p class="subtitle">Enter your email and we’ll send you a reset link</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Input -->
            <div class="input-group">
                <i class="fa fa-envelope"></i>
                <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                    value="{{ old('email') }}"
                    required
                    autofocus
                />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="login-btn">
                Send Reset Link
            </button>

            <!-- Back to Login -->
            <a href="{{ route('login') }}" class="forgot">
                Back to Login
            </a>
        </form>
    </div>

    <!-- Register Panel -->
    <div class="register-panel">
        <h2>Need an Account?</h2>
        <p>Create one now to get started!</p>
        <a href="{{ route('register') }}">
            <button class="register-btn">Register</button>
        </a>
    </div>

</div>

<style>
/* Color scheme using #1a2c3e as primary */
.login-wrapper {
    display: flex;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    max-width: 1200px;
    margin: 0 auto;
}

.login-card {
    flex: 1;
    padding: 40px;
}

.register-panel {
    flex: 1;
    background: #1a2c3e;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 40px;
}

.login-card h1 {
    font-size: 28px;
    margin-bottom: 10px;
    color: #1a2c3e;
}

.subtitle {
    color: #666;
    margin-bottom: 30px;
}

.input-group {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 20px;
    padding: 0 15px;
    background: #f9f9f9;
}

.input-group i {
    color: #1a2c3e;
    margin-right: 10px;
}

.input-group input {
    flex: 1;
    border: none;
    padding: 12px 0;
    background: transparent;
    outline: none;
    font-size: 14px;
}

.input-group input:focus {
    border: none;
    outline: none;
}

.login-btn {
    width: 100%;
    background: #1a2c3e;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
    margin-top: 10px;
}

.login-btn:hover {
    background: #0f1e2c;
}

.register-btn {
    background: transparent;
    border: 2px solid white;
    color: white;
    padding: 10px 30px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 15px;
}

.register-btn:hover {
    background: white;
    color: #1a2c3e;
}

.forgot {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #1a2c3e;
    text-decoration: none;
    font-size: 14px;
}

.forgot:hover {
    color: #0f1e2c;
    text-decoration: underline;
}

.mb-4 {
    margin-bottom: 1rem;
}

/* Error styling */
.error {
    color: #e3342f;
    font-size: 12px;
    margin-top: -15px;
    margin-bottom: 15px;
}

.input-group.error-border {
    border-color: #e3342f;
}
</style>

<script src="{{ asset('js/auth.js') }}"></script>
</x-guest-layout>