document.addEventListener('DOMContentLoaded', function() {
    // Calcul du total
    function calculateTotal() {
        const basePrice = parseFloat(document.getElementById('base-price').textContent);
        const supplements = (document.getElementById('course_type').value === 'individual') ? 50 : 0;
        
        document.getElementById('supplements-display').textContent = supplements + '€';
        document.getElementById('total-display').textContent = (basePrice + supplements).toFixed(2) + '€';
    }
    // changement de type ( individuel ) 
    document.getElementById('course_type').addEventListener('change', calculateTotal);
    // Calcul initial
    calculateTotal();
});