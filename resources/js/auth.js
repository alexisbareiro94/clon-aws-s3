const togglePassword = document.querySelectorAll('.togglePassword');

togglePassword.forEach(element => {
    element.addEventListener('click', function () {
        const password = document.getElementById('password');
        const showPassword = document.getElementById('show-password');
        const hiddePassword = document.getElementById('hidde-password');
        if (password.type == 'password') {
            password.type = 'text';
            showPassword.classList.add('hidden');
            hiddePassword.classList.remove('hidden');
        } else {
            password.type = 'password';
            showPassword.classList.remove('hidden');
            hiddePassword.classList.add('hidden');
        }
    });
});