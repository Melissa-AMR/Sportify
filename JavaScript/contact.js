document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const fields = [
        { id: 'name', min: 2, msg: "2 caractères minimum" }, // minimum 2 caractères
        { id: 'email', regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, msg: "Email invalide" }, //  validation par regex
        { id: 'message', min: 10, msg: "10 caractères minimum" } //  minimum 10 caractères
    ];

    const validateField = (field, value) => {
        const input = document.getElementById(field.id);
        const isValid = field.regex ? field.regex.test(value) : value.trim().length >= field.min;
        // couleur de fond selon la validation
        input.style.backgroundColor = isValid ? '' : 'rgba(255, 0, 0, 0.1)'; 
        // recuperer le msg erreur 
        let errorMsg = input.nextElementSibling;
        
        if (!isValid) {
            // champ est invalide 
            if (!errorMsg || !errorMsg.classList.contains('error-msg')) {
                // nouvel élément pour le message d'erreur
                errorMsg = document.createElement('div');
                errorMsg.className = 'error-msg text-danger small mt-1';
                // Insere message apres input
                input.parentNode.insertBefore(errorMsg, input.nextSibling);
            }
            errorMsg.textContent = field.msg;
        } else if (errorMsg) {
            // champ est valide mais qu'un message derreur existe, on le supprime
            errorMsg.remove();
        }
        return isValid;
    };

    fields.forEach(field => {
        const input = document.getElementById(field.id);
        input.addEventListener('input', () => validateField(field, input.value));
    });

    form.addEventListener('submit', (e) => {
        let formValid = true;

        fields.forEach(field => {
            const value = document.getElementById(field.id).value;
            if (!validateField(field, value)) {
                formValid = false; 
            }
        });
        
        if (!formValid) {
            e.preventDefault(); // Empêche l'envoi du formulaire si le form est invalide 
            // defiler jusqu'au premier champ invalide
            document.querySelector('[style*="background-color: rgba(255, 0, 0, 0.1)"]')?.scrollIntoView({behavior: 'smooth', block: 'center' });
        }
    });
});