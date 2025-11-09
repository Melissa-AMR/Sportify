<?php
require 'config.php';
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Sportify' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/header.css">
</head>

<body>
        <!-- Barre de nav -->
        <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
            <div class="container">
                <a class="navbar-brand" href="accueil.php"><img src="images/logo.jpeg" alt="Sportify"
                        class="d-inline-block align-top me-2" height="30">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="accueil.php">Accueil</a></li>
                        <li class="nav-item"><a class="nav-link" href="activities.php">Activités</a></li>

                        <!-- si user est connecte la bar de nav change -->
                        <?php if ($isLoggedIn): ?>
                            <li class="nav-item"><a class="nav-link " href="logout.php">Déconnexion</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="Login.php">Connexion</a></li>
                            <li class="nav-item"><a class="nav-link" href="register.php">Inscription</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        <script src="JavaScript/header.js" ></script>
    </body>
</html>