// basculer entre les sections hero
function toggleSections() {
    const current = document.querySelector('.hero[style*="display: flex"]');
    const next = current.id === 'section1' ? document.getElementById('section2') : document.getElementById('section1');

    current.style.display = 'none';
    next.style.display = 'flex';
}

// Gestion des message de session
function setupModal() {
    const modal = document.getElementById('notificationModal');
    const modalMessage = document.getElementById('modalMessage');
    const closeBtn = document.getElementById('modalCloseBtn');

    // Fermer la modale
    closeBtn.addEventListener('click', function() {
        modal.classList.remove('active');
    });

    // Fermer la modale en cliquant à l'extérieur
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
}

// verification des formats 
function validateReviewForm() {
    const form = document.querySelector('form[method="POST"]');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validation de activite
        const activitySelect = document.getElementById('activity_id');
        if (!activitySelect.value) {
            activitySelect.classList.add('error-border');
            isValid = false;
        } else {
            activitySelect.classList.remove('error-border');
        }

        // Validation de la note
        const ratingSelect = document.getElementById('rating');
        if (!ratingSelect.value) {
            ratingSelect.classList.add('error-border');
            isValid = false;
        } else {
            ratingSelect.classList.remove('error-border');
        }

        // Validation du commentaire
        const reviewText = document.getElementById('review_text');
        if (!reviewText.value.trim() || reviewText.value.trim().length < 10) {
            reviewText.classList.add('error-border');
            isValid = false;
        } else {
            reviewText.classList.remove('error-border');
        }

        if (!isValid) {
            e.preventDefault();
            // Afficher un message d'erreur général si besoin
            const errorMessage = document.getElementById('form-error-message');
            if (errorMessage) {
                errorMessage.style.display = 'block';
            }
        }
    });

    // supprimer le style .. 
    const inputs = form.querySelectorAll('select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('error-border');
            const errorMessage = document.getElementById('form-error-message');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Afficher la première section et le basculement
    document.getElementById('section1').style.display = 'flex';
    document.getElementById('section2').style.display = 'none';
    setInterval(toggleSections, 5000);
    setupModal();
    validateReviewForm();
});