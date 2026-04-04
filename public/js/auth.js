// public/js/auth.js

document.addEventListener('DOMContentLoaded', () => {

    // ---------- 1. Toggle password visibility ----------
    document.querySelectorAll('.eye-btn').forEach(button => {
        button.addEventListener('click', () => {
            const input = button.parentElement.querySelector('input');
            if(input.type === 'password'){
                input.type = 'text';
                button.innerHTML = '<i class="fa fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                button.innerHTML = '<i class="fa fa-eye"></i>';
            }
        });
    });

    // ---------- 2. Autofocus first input ----------
    const firstInput = document.querySelector('form input');
    if(firstInput){
        firstInput.focus();
    }

    // ---------- 3. Remember Me checkbox optional enhancement ----------
    document.querySelectorAll('input[name="remember"]').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            console.log('Remember Me checked:', checkbox.checked);
        });
    });

});