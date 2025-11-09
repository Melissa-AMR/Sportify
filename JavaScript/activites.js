// gestion des message de session 
function showModal(message, type) {
    const modal = document.getElementById('sportModal');
    const content = document.getElementById('modalContent');
    const msg = document.getElementById('modalMessage');

    content.className = type === 'success' ? 'sport-modal-content modal-success' : 'sport-modal-content modal-error';
    msg.textContent = message;

    modal.style.display = 'flex';
    setTimeout(closeModal, 5000);
}

function closeModal() {
    document.getElementById('sportModal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof alertMessage !== 'undefined' && typeof alertType !== 'undefined') {
        showModal(alertMessage, alertType);
    }
});