// Gestion du lien actif dans la navigation
// aide les gens a se retrouver dans les differentes page du site web 
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = location.pathname.split('/').pop() || 'accueil.php';
    const navLinks = document.querySelectorAll('.nav-link:not(.btn)');
    navLinks.forEach(link => {
        const linkPage = link.getAttribute('href').split('/').pop();
        if (currentPage === linkPage) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
});