<link rel="stylesheet" href="style/footer.css">
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-start">

            <!-- Colonne 1 : Logo et Nom -->
            <div class="footer-col mb-3 mb-md-0 px-3">
                <h5 class="text-info">Sportify</h5> <!-- Couleur bleue (info) -->
                <p class="mb-0">Votre partenaire fitness en ligne</p>
            </div>

            <!-- Colonne 2 : Liens vers les pages essentielles   -->
            <div class="footer-col mb-3 mb-md-0 px-3">
                <h5 class="text-info">Liens utiles</h5>
                <ul class="list-unstyled"> 
                    <li class="mb-1"><a href="accueil.php" class="text-white text-decoration-none">Accueil</a></li>
                    <li class="mb-1"><a href="activities.php" class="text-white text-decoration-none">Activités</a></li>
                    <li><a href="contact.php" class="text-white text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Colonne 3 : Contact -->
            <div class="footer-col px-3">
                <h5 class="text-info">Contact</h5>
                <ul class="list-unstyled">
                    <li class="mb-1">Email : <a href="mailto:sportify@mail.com"
                            class="text-white text-decoration-none">sportify@mail.com</a></li>
                    <li>Téléphone : 01 23 45 67 89</li>
                </ul>
            </div>
        </div>

        <hr class="bg-light my-4">
        <p class="text-center mb-0">&copy; <?= date('Y') ?> Sportify - Tous droits réservés</p>
    </div>
</footer>