// verification du format des données ... 
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');

    function showError(input, message) {
        input.style.backgroundColor = 'rgba(255, 0, 0, 0.1)';
        let errorDiv = input.nextElementSibling;
        if (!errorDiv || !errorDiv.classList.contains('error-message')) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-danger small mt-1';
            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }
        errorDiv.textContent = message;
    }

    //enlever les erreurs
    function removeError(input) {
        input.style.backgroundColor = '';
        const errorDiv = input.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('error-message')) {
            errorDiv.remove();
        }
    }

    //nom d'utilisateur
    username.addEventListener('input', function() {
        if (this.value.length < 3) {
            showError(this, "Le nom d'utilisateur doit faire au moins 3 caractères");
        } else {
            removeError(this);
        }
    });

    //email
    email.addEventListener('input', function() {
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value)) {
            showError(this, "Veuillez entrer une adresse email valide");
        } else {
            removeError(this);
        }
    });

    // mot de passe
    password.addEventListener('input', function() {
        if (this.value.length < 4) {
            showError(this, "Le mot de passe doit faire au moins 4 caractères");
        } else {
            removeError(this);
            if (confirm_password.value !== this.value) {
                showError(confirm_password, "Les mots de passe ne correspondent pas");
            } else {
                removeError(confirm_password);
            }
        }
    });

    confirm_password.addEventListener('input', function() {
        if (this.value !== password.value) {
            showError(this, "Les mots de passe ne correspondent pas");
        } else {
            removeError(this);
        }
    });

    // Verification au moment de soumission
    form.addEventListener('submit', function(event) {
        let isValid = true;

        // Vérifie chaque champ
        if (username.value.length < 3) {
            showError(username, "Le nom d'utilisateur doit faire au moins 3 caractères");
            isValid = false;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            showError(email, "Veuillez entrer une adresse email valide");
            isValid = false;
        }

        if (password.value.length < 4) {
            showError(password, "Le mot de passe doit faire au moins 4 caractères");
            isValid = false;
        }

        if (confirm_password.value !== password.value) {
            showError(confirm_password, "Les mots de passe ne correspondent pas");
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
            const firstError = document.querySelector('[style*="background-color: rgba(255, 0, 0, 0.1)"]');
            firstError?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});