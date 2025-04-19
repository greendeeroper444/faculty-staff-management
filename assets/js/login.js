document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const loginBtn = document.getElementById('login-btn');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const usernameInput = document.getElementById('username');
    const rememberCheckbox = document.getElementById('remember');
    
    //check if credentials are stored in localStorage and populate the fields
    if (localStorage.getItem('rememberedUsername')) {
        usernameInput.value = localStorage.getItem('rememberedUsername');
        if (localStorage.getItem('rememberedPassword')) {
            passwordInput.value = localStorage.getItem('rememberedPassword');
        }
        rememberCheckbox.checked = true;
    }
    
    //toggle password visibility
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    //add loading effect on form submission and handle remember me
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            //handle remember me functionality
            const username = usernameInput.value;
            const password = passwordInput.value;
            
            if (rememberCheckbox.checked) {
                localStorage.setItem('rememberedUsername', username);
                localStorage.setItem('rememberedPassword', password);
            } else {
                localStorage.removeItem('rememberedUsername');
                localStorage.removeItem('rememberedPassword');
            }
            
            //only show loading effect if fields are filled
            if (username && password) {
                loginBtn.classList.add('loading');
            }
        });
    }
    
    //function to clear remembered credentials (can be called if needed)
    function clearRememberedCredentials() {
        localStorage.removeItem('rememberedUsername');
        localStorage.removeItem('rememberedPassword');
        usernameInput.value = '';
        passwordInput.value = '';
        rememberCheckbox.checked = false;
    }
});