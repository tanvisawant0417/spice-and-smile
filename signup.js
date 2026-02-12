
function toggleForms() {
    const signupBox = document.getElementById('signup-box');
    const loginBox = document.getElementById('login-box');

    signupBox.classList.toggle('hidden');
    loginBox.classList.toggle('hidden');

    const activeBox = signupBox.classList.contains('hidden') ? loginBox : signupBox;
    activeBox.style.animation = 'none';
    activeBox.offsetHeight;
    activeBox.style.animation = '';
}
