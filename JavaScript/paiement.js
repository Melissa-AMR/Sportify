document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const payBtn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function(e) {
        const cardNumber = document.getElementById('card-number').value.replace(/\s/g, '');
        const cardExpiry = document.getElementById('card-expiry').value;
        const cardCvc = document.getElementById('card-cvc').value;
        
        if (cardNumber.length !== 16 || !/^\d+$/.test(cardNumber)) {
            alert('Numéro de carte invalide (16 chiffres requis)');
            e.preventDefault();
            return;
        }
        
        if (!/^\d{2}\/\d{2}$/.test(cardExpiry)) {
            alert('Format date expiration invalide (MM/AA)');
            e.preventDefault();
            return;
        }
        
        if (cardCvc.length < 3 || !/^\d+$/.test(cardCvc)) {
            alert('Code sécurité invalide (3 chiffres minimum)');
            e.preventDefault();
            return;
        }
        
    });
    
    // mettre en forme le numero de la carte 
    document.getElementById('card-number').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').replace(/(\d{4})/g, '$1 ').trim();
    });
    
    // mettre en form la date 
    document.getElementById('card-expiry').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').replace(/(\d{2})(\d)/, '$1/$2');
    });
});