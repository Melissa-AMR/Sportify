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
    
    <!--div-->
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
                        <strong>Sportify</strong>, c’est bien plus qu’une simple application de sport.
                        C’est un véritable coach numérique, conçu pour vous motiver, vous guider et vous permettre
                        de repousser vos limites. Débutant, amateur ou confirmé ? Peu importe.
                        Notre plateforme intelligente s’adapte à tous les niveaux, et vous accompagne dans chaque
                        étape de votre transformation physique.
                        <br><br>
                        Grâce à une interface fluide, des outils de suivi performants et une communauté active,
                        chaque séance devient un moment unique, motivant, et surtout, efficace.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fonctionnalites principales -->
    <section class="container" name="Fonctionnalites principales">
        <h2 class="section-title">Fonctionnalités principales</h2>

        <div class="row align-items-center mb-5">
            <div class="col-md-5 feature-img-container">
                <img src="images/coach2.jpg" class="feature-img" alt="Suivi des entraînements">
            </div>
            <div class="col-md-7">
                <h4 class="text-danger mb-3">
                    <i class="fas fa-chart-line me-2"></i>Suivi des Entraînements - Votre Coach Data
                </h4>

                <div class="nutrition-content">
                    <p class="fw-bold">Transformez chaque séance en progression mesurable !</p>

                    <div class="benefit-item">
                        <div>
                            <strong>Analyse approfondie</strong> de tous vos paramètres d'entraînement avec visualisation en temps réel
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Tableaux de bord clairs</strong> pour suivre vos progrès sur le court, moyen et long terme
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Recommandations intelligentes</strong> basées sur vos performances pour optimiser chaque mouvement
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Adapté à tous niveaux</strong>, du débutant à l'athlète confirmé, avec des métriques personnalisées
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center flex-md-row-reverse mb-5">
            <div class="col-md-5 feature-img-container">
                <img src="images/equipe1.jpg" class="feature-img" alt="Communauté sportive">
            </div>
            <div class="col-md-7">
                <h4 class="text-danger mb-3">
                    <i class="fas fa-users me-2"></i>Communauté Sportive - Votre Réseau de Passionnés
                </h4>

                <div class="nutrition-content">
                    <p class="fw-bold">Rejoignez une famille de sportifs motivés et bienveillants !</p>

                    <div class="benefit-item">
                        <div>
                            <strong>Échanges enrichissants</strong> avec partage de conseils et retours d'expérience entre membres
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Défis communautaires</strong> stimulants pour booster votre motivation et dépasser vos limites
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Réseau local</strong> pour trouver des partenaires d'entraînement ou créer des événements près de chez vous
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Accès privilégié</strong> à des experts et coachs pour des conseils personnalisés
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-md-5 feature-img-container">
                <img src="images/perso.jpg" class="feature-img" alt="Objectifs personnalisés">
            </div>
            <div class="col-md-7">
                <h4 class="text-danger mb-3">
                    <i class="fas fa-bullseye me-2"></i>Objectifs Personnalisés - Votre Feuille de Route Sur Mesure
                </h4>

                <div class="nutrition-content">
                    <p class="fw-bold">Transformez vos ambitions en résultats concrets !</p>

                    <div class="benefit-item">
                        <div>
                            <strong>Plan 100% adapté</strong> à votre niveau actuel, vos disponibilités et vos objectifs à long terme
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Défis intelligents</strong> ajustés automatiquement selon votre progression et vos performances
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Système de motivation</strong> avec rappels personnalisés et récompenses virtuelles pour célébrer chaque étape
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Visualisation de progression</strong> avec indicateurs clairs pour mesurer vos avancées vers la meilleure version de vous-même
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- NOS SERVICES -->
    <section class="container" name="nos services" >
        <h2 class="section-title">Nos services</h2>

        <div class="row align-items-center flex-md-row-reverse mb-5">
            <div class="col-md-5 feature-img-container">
                <img src="images/yoga1.jpg" class="feature-img" alt="Coaching personnalisé">
            </div>
            <div class="col-md-7">
                <h5 class="text-danger mb-3">
                    <i class="fas fa-user-tie me-2"></i>Coaching Personnalisé - Votre Entraîneur Privé Digital
                </h5>

                <div class="nutrition-content">
                    <p class="fw-bold">L'expertise d'un coach pro, disponible 24h/24 dans votre poche !</p>

                    <div class="benefit-item">
                        <div>
                            <strong>Programmes ultra-personnalisés</strong> conçus par nos experts et ajustés en temps réel selon votre progression
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Feedback instantané</strong> avec analyse technique et corrections vidéo pour chaque mouvement
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Suivi quotidien</strong> avec rappels motivationnels et ajustements automatiques de votre planning
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Flexibilité totale</strong> - Adapté à VOTRE emploi du temps et à VOS objectifs spécifiques
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-md-5 feature-img-container">
                <img src="images/pilate1.jpg" class="feature-img" alt="Programmes d'entraînement">
            </div>
            <div class="col-md-7">
                <h5 class="text-danger mb-3">
                    <i class="fas fa-dumbbell me-2"></i>Programmes d'Entraînement - Votre Coach Personnel
                </h5>

                <div class="nutrition-content"> <!-- Même classe que la section nutrition pour conserver le style -->
                    <p class="fw-bold">Votre transformation, votre succès !</p>

                    <div class="benefit-item">
                        <div>
                            <strong>50+ programmes scientifiquement validés</strong> adaptés à tous les niveaux et objectifs sportifs
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Brûle-graisses intensif</strong> avec suivi métabolique personnalisé pour des résultats optimaux
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Préparation marathon</strong> avec planification sur mesure et ajustements en temps réel
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Prise de masse intelligente</strong> combinant hypertrophie et préservation articulaire
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center flex-md-row-reverse  mb-5">
            <div class="col-md-5 feature-img-container">
                <img src="images/nutrition1.jpg" class="feature-img img-resposive-custom "
                    alt="Suivi des entraînements">
            </div>
            <div class="col-md-7">
                <h5 class="text-danger mb-3">
                    <i class="fas fa-utensils me-2"></i>Suivi Nutritionnel - Votre Partenaire Alimentaire Intelligent
                </h5>

                <div class="nutrition-content">
                    <p class="fw-bold">Votre allié gourmand pour des résultats maximaux !</p>

                    <div class="benefit-item">
                        <div>
                            <strong>Plans alimentaires sur-mesure</strong> parfaitement adaptés à vos goûts, votre rythme de vie et vos objectifs sportifs
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Liste de courses intelligente</strong> qui anticipe vos besoins et propose des alternatives healthy à vos produits habituels
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Analyse nutritionnelle complète</strong> : suivi précis de vos apports en macros/micro-nutriments avec recommandations personnalisées
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div>
                            <strong>Accès privilégié</strong> à nos diététiciens certifiés pour des conseils professionnels sans frustration
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!--LAISSER UN AVIS -->
    <section class="container mt-5" name=" les avis " >
        <h2 class="section-title">Laissez un avis</h2>

        <?php if (!$isLoggedIn): ?>
            <div class="alert alert-custom" role="alert">
                <p style="font-size: 1.3rem; font-weight: 600;">
                    Votre avis compte vraiment.
                </p>
                <p>
                    Chaque retour que nous recevons nous aide à améliorer continuellement notre service.
                    En partageant votre expérience, vous contribuez à rendre la plateforme plus utile et plus performante.
                </p>
                <p style="font-size: 1.15rem; font-weight: 600; color: #d10000;">
                    Que votre expérience ait été positive ou que vous ayez des suggestions, nous souhaitons vous entendre.
                </p>
                <p>
                    Votre opinion peut guider d'autres utilisateurs et nous permet de répondre encore mieux à vos attentes.
                </p>
                <p style="font-size: 1.2rem; font-weight: 600;">
                    <a href="Login.php" class="alert-link">Connectez-vous</a> pour laisser votre avis dès maintenant.
                </p>
                <p><strong>Merci de faire partie de notre communauté.</strong></p>
            </div>

        <?php else: ?>
            <form method="POST" action="accueil.php">
                <div class="mb-3">
                    <label for="activity_id" class="form-label">Choisissez une activité</label>
                    <select class="form-select" id="activity_id" name="activity_id" required>
                        <option value="">Sélectionnez une activité</option>
                        <?php foreach ($activities as $activity): ?>
                            <option value="<?= $activity['id'] ?>"><?= htmlspecialchars($activity['nom_activite']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="rating" class="form-label">Votre note</label>
                    <select class="form-select" id="rating" name="rating" required>
                        <!-- le systeme des niveau je les convertis en nombre pour faciliter la manipulation de la base de données -->
                        <option value="1">1 - Très insatisfait</option>
                        <option value="2">2 - Insatisfait</option>
                        <option value="3">3 - Moyen</option>
                        <option value="4">4 - Satisfait</option>
                        <option value="5">5 - Très satisfait</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="review_text" class="form-label">Votre avis</label>
                    <textarea class="form-control" id="review_text" name="review_text" rows="4" required></textarea>
                </div>
                <button type="submit" name="submit_review" class="btn btn-primary">Soumettre l'avis</button>
            </form>
        <?php endif; ?>
    </section>

    <!--LIRE LES AVIS LAISSES -->
    <section class="container mt-5">
        <h2 class="section-title">Avis des utilisateurs</h2>
        <?php
        try {
            // Récupération des avis avec note > 3 
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

            if (count($reviews) > 0) {
                foreach ($reviews as $row): ?>
                    <div class="testimonial">
                        <p><strong><?= htmlspecialchars($row['utilisateur_id']) ?> -
                                <?= date('d M Y', strtotime($row['date_avis'])) ?></strong></p>
                        <p><strong>Activité : </strong><?= htmlspecialchars($row['activity_name']) ?></p>
                        <p><strong>Note : </strong> <?= $row['note'] ?>/5</p>
                        <p><?= nl2br(htmlspecialchars($row['commentaire'])) ?></p>
                    </div>
                <?php endforeach;
            } else {
                echo '<div class="sport-notification">
                <div class="sport-icon">🏆</div>
                <div class="sport-message">
                    <h3>Zone d\'avis en construction !</h3>
                    <p>Aucun feedback sportif avec une note supérieure à 3 n\'a été enregistré.</p>
                    <p>Soyez le premier à partager votre expérience et motivez notre équipe !</p>
                    <p style="font-size: 1.2rem; font-weight: 600;">
                        <a href="login.php" class="alert-link">Connectez-vous</a> pour laisser votre avis dès maintenant.
                    </p>
                </div>
            </div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Erreur lors de la récupération des avis : ' . $e->getMessage() . '</div>';
        }
        ?>
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
