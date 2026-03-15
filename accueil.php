<?php
require 'config.php';
include 'header.php'; 

$isLoggedIn = isset($_SESSION['user_id']);

// Récupération des activités 
$activities = [];
try {
    $sql = "SELECT id, nom_activite FROM activites";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur lors de la récupération des activités : " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review']) && $isLoggedIn) {
    $user_id = $_SESSION['user_id'];

    // Vérifie que l'utilisateur existe 
    $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // on insére l'avis
        $rating = (int) $_POST['rating'];
        $review_text = htmlspecialchars($_POST['review_text']);
        $activity_id = (int) $_POST['activity_id'];

        try {
            $sql = "INSERT INTO avis (utilisateur_id, activite_id, note, commentaire) 
                    VALUES (:user_id, :activity_id, :rating, :review_text)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':activity_id', $activity_id, PDO::PARAM_INT);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':review_text', $review_text, PDO::PARAM_STR);
            $stmt->execute();

            $_SESSION['success_message'] = "Avis ajouté avec succès!";
            // Rediriger pour eviter que l'avis ce re insère
            header("Location: accueil.php");
            exit();

        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erreur lors de l'insertion de l'avis : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Sportify</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="accueil.css">
</head>

<body>
    <section class="hero" id="section1">
        <div class="container text-center">
            <h1 class="hero-title">Fais-le pour ton corps</h1>
            <p class="hero-lead">
                <span id="dynamic-text">"Le plus difficile, c'est de commencer. Le plus beau, c'est de ne plus vouloir s'arrêter."</span>
            </p>
            <a href="<?= $isLoggedIn ? 'activities.php' : 'register.php' ?>"
                class="btn btn-primary btn-lg hero-btn">Commencer maintenant</a>
        </div>
    </section>

    <section class="hero" id="section2">
        <div class="container text-center">
            <h1 class="hero-title">Sportify, ton partenaire fitness</h1>
            <p class="hero-lead">
                <span id="dynamic-text">"Avec Sportify, chaque objectif devient atteignable. Ton corps, ton esprit, ta réussite."</span>
            </p>
            <a href="<?= $isLoggedIn ? 'activities.php' : 'register.php' ?>"
                class="btn btn-primary btn-lg hero-btn">Commencer maintenant</a>
        </div>
    </section>

    <section class="container my-5" name="presentation de site">
        <h2 class="section-title ">
            Présentation de <span>Sportify</span>
        </h2>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-4 p-5 bg-light">
                    <div class="text-center mb-4">
                        <i class=" display-4 text-danger"></i>
                    </div>
                    <p class="lead text-center text-muted">
                        <strong>Sportify</strong>, c'est bien plus qu'une simple application de sport.
                        C'est un véritable coach numérique, conçu pour vous motiver, vous guider et vous permettre
                        de repousser vos limites. Débutant, amateur ou confirmé ? Peu importe.
                        Notre plateforme intelligente s'adapte à tous les niveaux, et vous accompagne dans chaque
                        étape de votre transformation physique.
                        <br><br>
                        Grâce à une interface fluide, des outils de suivi performants et une communauté active,
                        chaque séance devient un moment unique, motivant, et surtout, efficace.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FONCTIONNALITES - grille de cartes -->
    <section class="section-features" id="features">
        <div class="container">
            <h2 class="section-heading text-center">Fonctionnalités principales</h2>
            <p class="section-subtitle">Tout ce dont vous avez besoin pour atteindre vos objectifs</p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-card-img">
                        <img src="images/coach2.jpg" alt="Suivi des entraînements">
                    </div>
                    <div class="feature-card-body">
                        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                        <h3>Suivi des Entraînements</h3>
                        <p>Analyse approfondie de vos paramètres, tableaux de bord clairs et recommandations intelligentes basées sur vos performances.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-card-img">
                        <img src="images/perso.jpg" alt="Objectifs personnalisés">
                    </div>
                    <div class="feature-card-body">
                        <div class="feature-icon"><i class="fas fa-bullseye"></i></div>
                        <h3>Objectifs Personnalisés</h3>
                        <p>Plan 100% adapté à votre niveau, défis intelligents ajustés automatiquement et système de motivation avec récompenses.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-card-img">
                        <img src="images/coach3.jpg" alt="Communauté sportive">
                    </div>
                    <div class="feature-card-body">
                        <div class="feature-icon"><i class="fas fa-users"></i></div>
                        <h3>Communauté Sportive</h3>
                        <p>Échanges enrichissants, défis communautaires stimulants et accès privilégié à des experts et coachs.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- NOS SERVICES - cartes avec image -->
    <section class="section-services">
        <div class="container">
            <h2 class="section-heading text-center">Nos services</h2>
            <p class="section-subtitle">Des programmes adaptés à chaque objectif</p>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-card-img">
                        <img src="images/yoga1.jpg" alt="Coaching personnalisé">
                    </div>
                    <div class="service-card-content">
                        <div class="service-icon"><i class="fas fa-user-tie"></i></div>
                        <h3>Coaching Personnalisé</h3>
                        <ul>
                            <li>Programmes ultra-personnalisés ajustés en temps réel</li>
                            <li>Feedback instantané et corrections vidéo</li>
                            <li>Suivi quotidien et rappels motivationnels</li>
                            <li>Adapté à votre emploi du temps</li>
                        </ul>
                        <a href="activities.php" class="service-link">Découvrir <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-card-img">
                        <img src="images/pilate1.jpg" alt="Programmes d'entraînement">
                    </div>
                    <div class="service-card-content">
                        <div class="service-icon"><i class="fas fa-dumbbell"></i></div>
                        <h3>Programmes d'Entraînement</h3>
                        <ul>
                            <li>50+ programmes scientifiquement validés</li>
                            <li>Brûle-graisses avec suivi métabolique</li>
                            <li>Préparation marathon sur mesure</li>
                            <li>Prise de masse intelligente</li>
                        </ul>
                        <a href="activities.php" class="service-link">Découvrir <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-card-img">
                        <img src="images/nutrition1.jpg" alt="Suivi nutritionnel">
                    </div>
                    <div class="service-card-content">
                        <div class="service-icon"><i class="fas fa-utensils"></i></div>
                        <h3>Suivi Nutritionnel</h3>
                        <ul>
                            <li>Plans alimentaires sur-mesure</li>
                            <li>Liste de courses intelligente</li>
                            <li>Analyse nutritionnelle complète</li>
                            <li>Accès à nos diététiciens certifiés</li>
                        </ul>
                        <a href="activities.php" class="service-link">Découvrir <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BANDEAU CTA -->
    <section class="cta-banner">
        <div class="container text-center">
            <h2>Prêt à transformer votre corps ?</h2>
            <p>Rejoignez Sportify et commencez votre parcours fitness dès aujourd'hui.</p>
            <a href="<?= $isLoggedIn ? 'activities.php' : 'register.php' ?>" class="btn-hero-primary">S'inscrire gratuitement</a>
        </div>
    </section>



    <!--LAISSER UN AVIS -->
    <section class="section-reviews" id="avis">
        <div class="container">
            <h2 class="section-heading text-center">Votre avis compte</h2>
            <p class="section-subtitle">Partagez votre expérience et aidez notre communauté</p>

            <?php if (!$isLoggedIn): ?>
                <div class="review-login-prompt">
                    <div class="review-login-icon">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <h3>Rejoignez la conversation</h3>
                    <p>Votre retour nous aide a ameliorer nos services. Chaque avis guide les futurs sportifs dans leur choix.</p>
                    <a href="Login.php" class="btn-review-login"><i class="fas fa-sign-in-alt"></i> Se connecter pour donner un avis</a>
                </div>

            <?php else: ?>
                <form method="POST" action="accueil.php" class="review-form">
                    <div class="review-form-row">
                        <div class="review-form-group">
                            <label for="activity_id" class="form-label"><i class="fas fa-running"></i> Activite</label>
                            <select class="form-select" id="activity_id" name="activity_id" required>
                                <option value="">Selectionnez une activite</option>
                                <?php foreach ($activities as $activity): ?>
                                    <option value="<?= $activity['id'] ?>"><?= htmlspecialchars($activity['nom_activite']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="review-form-group">
                            <label class="form-label"><i class="fas fa-star"></i> Votre note</label>
                            <div class="star-rating-input">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                                    <label for="star<?= $i ?>" title="<?= $i ?> etoile<?= $i > 1 ? 's' : '' ?>"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <div class="review-form-group">
                        <label for="review_text" class="form-label"><i class="fas fa-pen"></i> Votre commentaire</label>
                        <textarea class="form-control" id="review_text" name="review_text" rows="4" placeholder="Decrivez votre experience..." required></textarea>
                    </div>
                    <button type="submit" name="submit_review" class="btn-submit-review">
                        <i class="fas fa-paper-plane"></i> Publier mon avis
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <!--LIRE LES AVIS LAISSES -->
    <section class="section-testimonials">
        <div class="container">
            <h2 class="section-heading text-center">Ce que disent nos membres</h2>
            <p class="section-subtitle">Des retours authentiques de notre communaute sportive</p>

            <?php
            try {
                $sql = "SELECT DISTINCT av.id, a.nom_activite as activity_name, u.id as utilisateur_id,
                           av.note, av.commentaire, av.date_avis
                    FROM avis av
                    JOIN activites a ON av.activite_id = a.id
                    JOIN utilisateurs u ON av.utilisateur_id = u.id
                    WHERE av.note > 3
                    ORDER BY av.date_avis DESC";

                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($reviews) > 0): ?>
                    <div class="testimonials-grid">
                        <?php foreach ($reviews as $row): ?>
                            <div class="testimonial-card">
                                <div class="testimonial-header">
                                    <div class="testimonial-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="testimonial-info">
                                        <span class="testimonial-user">Membre #<?= htmlspecialchars($row['utilisateur_id']) ?></span>
                                        <span class="testimonial-date"><?= date('d M Y', strtotime($row['date_avis'])) ?></span>
                                    </div>
                                </div>
                                <div class="testimonial-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $row['note'] ? 'star-filled' : 'star-empty' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <div class="testimonial-activity">
                                    <i class="fas fa-dumbbell"></i> <?= htmlspecialchars($row['activity_name']) ?>
                                </div>
                                <p class="testimonial-text"><?= nl2br(htmlspecialchars($row['commentaire'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-reviews">
                        <i class="fas fa-comments"></i>
                        <h3>Aucun avis pour le moment</h3>
                        <p>Soyez le premier a partager votre experience sportive !</p>
                    </div>
                <?php endif;
            } catch (PDOException $e) {
                echo '<div class="alert alert-danger">Erreur : ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div>
    </section>

    <!-- pour gerer les message d'erreur de la session -->
        <div id="notificationModal" class="modal-overlay" style="<?= (isset($_SESSION['success_message'])) || (isset($_SESSION['error_message'])) ? 'display: flex;' : 'display: none;' ?>">
            <div class="modal-content">
                <div class="modal-message" id="modalMessage">
                    <?= $_SESSION['success_message'] ?? $_SESSION['error_message'] ?? '' ?>
                </div>
                <button class="modal-close-btn" id="modalCloseBtn">Fermer</button>
            </div>
        </div>

        <?php 
        // Nettoyer les messages après affichage
        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']); 
       ?>
       
       <script src="JavaScript/accueil.js" ></script>
       <?php include 'footer.php' ?>
</body>
</html>
