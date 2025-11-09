<?php
require 'config.php';
include 'header.php';

$isLoggedIn = isset($_SESSION['user_id']);

//vider le panier si l'utilisateur n'est pas connecter 
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

//rediriger l'utilisateur si il n'est pas connecter 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!$isLoggedIn) {
        $_SESSION['redirect_url'] = 'activities.php';
        $_SESSION['alert_message'] = "Veuillez vous connecter pour vous inscrire aux activités";
        $_SESSION['alert_type'] = 'error';
        header("Location: login.php");
        exit();
    }

    $activity_id = $_POST['activity_id'];
    $niveau = $_POST['niveau'];

    // prevenir l'utilisateur de s'inscrire 2 fois a la meme activité
    foreach ($_SESSION['panier'] as $item) {
        if ($item['activity_id'] == $activity_id) {
            $_SESSION['alert_message'] = " Vous êtes déjà inscrit à cette activité";
            $_SESSION['alert_type'] = 'error';
            header("Location: activities.php");
            exit();
        }
    }

    $stmt = $conn->prepare("SELECT * FROM activites WHERE id = ?");
    $stmt->execute([$activity_id]);
    $activity = $stmt->fetch();

    if ($activity) {
        $_SESSION['panier'][] = [
            'activity_id' => $activity['id'],
            'nom_activite' => $activity['nom_activite'],
            'prix' => (float) $activity['prix'],
            'niveau' => $niveau
        ];
        $_SESSION['alert_message'] = " Inscription confirmée! Prêt pour l'action!";
        $_SESSION['alert_type'] = 'success';
    }

    header("Location: activities.php");
    exit();
}
$activities = $conn->query("SELECT * FROM activites")->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Activités Sportives</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/activities.css">
</head>

<body>
    <div id="sportModal" class="sport-modal">
        <div class="sport-modal-content" id="modalContent">
            <div class="sport-modal-icon" id="modalIcon"></div>
            <div id="modalMessage"></div>
            <button class="sport-modal-close" onclick="closeModal()">Fermer</button>
        </div>
    </div>

    <!-- cart -->
    <a href="cart.php" class="cart-icon">
        Panier (<?= count($_SESSION['panier']) ?>)
    </a>

    <!--video -->
    <div class="video-background">
        <video autoplay muted loop id="background-video">
            <source src="images/video.mp4" type="video/mp4">
            Votre navigateur ne supporte pas la vidéo.
        </video>
        <div class="video-overlay"></div>
    </div>

    <h1 class="text-center my-5">Nos Activités Sportives</h1>

    <!-- Activitées  -->
    <div class="container">
        <div class="row">
            <?php foreach ($activities as $activity): ?>
                <div class="col-12 col-md-6 d-flex mb-5">
                    <div class="activity card shadow-sm">
                        <div class="card-body">
                            <h2 class="card-title text-danger"><?= htmlspecialchars($activity['nom_activite']) ?></h2>
                            <p class="card-text"><?= htmlspecialchars($activity['description']) ?></p>
                            <p><strong>Prix:</strong> <?= $activity['prix'] ?>€</p>
                            <p><strong>Coach:</strong> <?= htmlspecialchars($activity['nom_moniteur']) ?></p>

                            <form method="post">
                                <input type="hidden" name="activity_id" value="<?= $activity['id'] ?>">

                                <div class="mb-3">
                                    <label for="niveau" class="form-label">Niveau</label>
                                    <select name="niveau" id="niveau" class="form-select" required>
                                        <?php if (in_array($activity['nom_activite'], ['Yoga', 'Pilates'])): ?>
                                            <option value="débutant">Débutant</option>
                                            <option value="intermédiaire">Intermédiaire</option>
                                            <option value="avancé">Avancé</option>
                                        <?php else: ?>
                                            <option value="unique">Unique</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <?php if ($isLoggedIn): ?> <!-- le button change selon si l'utilisateur est connecter -->
                                    <button type="submit" name="add_to_cart" class="btn btn-danger w-100">S'inscrire</button>
                                <?php else: ?>
                                    <button type="submit" name="add_to_cart" class="btn btn-danger w-100">Connectez-vous pour vous inscrire</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Passage des variables PHP à JavaScript -->
    <script>
        <?php if (isset($_SESSION['alert_message'])): ?>
            const alertMessage = '<?= addslashes($_SESSION['alert_message']) ?>';
            const alertType = '<?= $_SESSION['alert_type'] ?? 'success' ?>';
            <?php unset($_SESSION['alert_message']);
            unset($_SESSION['alert_type']); ?>
        <?php endif; ?>
    </script>
    <?php include 'footer.php' ?>
    <script src="JavaScript/activites.js" ></script>
</body>
</html>

