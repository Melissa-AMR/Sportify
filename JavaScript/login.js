// mise en form 
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const emailInput = form.querySelector('input[name="email"]');
    const passwordInput = form.querySelector('input[name="password"]');

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // le mot de passe (au moins 4 caractères)
    function validatePassword(password) {
        return password.length >= 4;
    }

    //  style d'erreur
    function setErrorStyle(input, isValid) {
        if (isValid) {
            input.style.backgroundColor = '';
        } else {
            input.style.backgroundColor = 'rgba(255, 0, 0, 0.1)'; 
        }
    }

    emailInput.addEventListener('input', function() {
        const isValid = validateEmail(emailInput.value);
        setErrorStyle(emailInput, isValid);
    });

    passwordInput.addEventListener('input', function() {
        const isValid = validatePassword(passwordInput.value);
        setErrorStyle(passwordInput, isValid);
    });

    // verification au moment de soumission du formulaire 
    form.addEventListener('submit', function(event) {
        let isFormValid = true;

        // email
        const isEmailValid = validateEmail(emailInput.value);
        setErrorStyle(emailInput, isEmailValid);
        if (!isEmailValid) isFormValid = false;

        //  mot de passe
        const isPasswordValid = validatePassword(passwordInput.value);
        setErrorStyle(passwordInput, isPasswordValid);
        if (!isPasswordValid) isFormValid = false;

        // empecher la soumission 
        if (!isFormValid) {
            event.preventDefault();
            alert('Veuillez corriger les erreurs dans le formulaire , Le mot de passe doit contenire au moin 4 caractere');
        }
    });
});