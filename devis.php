<?php
require 'config.php';
include 'header.php';

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if (!isset($_SESSION['user_id'])) {
    exit();
}

$successMessage = '';
$errorMessage = '';

//fonction pour envoyer le devis c'est plus organisé 
function sendDevisEmail($to, $total_price, $activities)
{
    //composition de l'email 
    $subject = "Votre devis Sportify";
    $message = "Merci pour votre demande de devis.\n\n";
    $message .= "Détail de vos activités :\n";
    foreach ($activities as $item) {
        $message .= "- " . $item['nom_activite'] . " (" . $item['prix'] . "€) - Durée: " . $item['duree'] . " min\n";
    }
    $message .= "\nTotal estimé : " . $total_price . "€\n\n";
    $message .= "Cordialement,\nL'équipe Sportify";
    $headers = "From: contact@sportify.com";
    // on a mis le mail dans un fichier pour le visualiser car son serveur smtp on peut pas envoyer 
    file_put_contents('devis_emails.log', "To: $to\nSubject: $subject\n\n$message\n\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_devis'])) {
    $course_type = htmlspecialchars($_POST['course_type'] ?? '');
    $user_email = filter_input(INPUT_POST, 'user_email', FILTER_VALIDATE_EMAIL);
    $user_id = $_SESSION['user_id'];

    if (!$user_email) {
        $errorMessage = "Veuillez fournir une adresse email valide.";
    } else {
        // calculer le prix a partir des elements du panier 
        $base_price = 0;
        $activities_details = [];
        if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $item) {
                // Récupérer les détails de devis 
                $stmt = $conn->prepare("SELECT * FROM activites WHERE id = ?");
                $stmt->execute([$item['activity_id']]);
                $activity = $stmt->fetch();

                if ($activity) {
                    $base_price += (float) $activity['prix'];
                    $activities_details[] = [
                        'nom_activite' => $activity['nom_activite'],
                        'prix' => $activity['prix'],
                        'duree' => $activity['duree'] 
                    ];
                }
            }
        }

        // Un supplement pour les cour individuel 
        $supplements = ($course_type === 'individual') ? 50 : 0;
        $total_price = $base_price + $supplements;

        // inserer le devis 
        try {
            $conn->beginTransaction();
            if (!isset($_SESSION['reservation_id'])) {
                throw new Exception("Aucune réservation trouvée");
            }
            $sql = "INSERT INTO devis 
                   (reservation_id, user_id, course_type, user_email, base_price, supplements, total_price) 
                   VALUES (:reservation_id, :user_id, :course_type, :user_email, :base_price, :supplements, :total_price)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':reservation_id' => $_SESSION['reservation_id'],
                ':user_id' => $user_id,
                ':course_type' => $course_type,
                ':user_email' => $user_email,
                ':base_price' => $base_price,
                ':supplements' => $supplements,
                ':total_price' => $total_price
            ]);

            // envoyer le mail 
            sendDevisEmail($user_email, $total_price, $activities_details);
            $conn->commit();
            $successMessage = "Votre demande de devis a été enregistrée avec succès! Un email avec l'estimation vous a été envoyé.";
            unset($_SESSION['panier']);
        } catch (PDOException $e) {
            $conn->rollBack();
            $errorMessage = "Une erreur est survenue : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de devis - Sportify</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/devis.css">
</head>

<body>
    <section style="padding: 2rem; max-width: 1000px; margin: auto;">

        <h2>Votre devis personnalisé</h2>

        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <div class="activity-list">
            <h4>Activités sélectionnées</h4>
            <?php
            if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Activité</th>
                            <th>Durée</th>
                            <th>Niveau</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['panier'] as $item):
                            // Récupérer les détails complets depuis la base
                            $stmt = $conn->prepare("SELECT * FROM activites WHERE id = ?");
                            $stmt->execute([$item['activity_id']]);
                            $activity = $stmt->fetch();
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($activity['nom_activite']) ?></td>
                                <td><?= htmlspecialchars($activity['duree']) ?> min</td>
                                <td>
                                    <!-- specification des niveau -->
                                    <?= in_array($activity['nom_activite'], ['Yoga', 'Pilates']) ?
                                        htmlspecialchars($item['niveau']) : 'Unique' ?>
                                </td>
                                <td><?= number_format($activity['prix'], 2) ?>€</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucune activité sélectionnée</p>
            <?php endif; ?>
        </div>
                <!-- formulaire devis --> 
        <form method="POST" action="devis.php">
            <div class="mb-3">
                <label for="course_type" class="form-label">Type de cours</label>
                <select class="form-select" id="course_type" name="course_type" required>
                    <option value="group">Collectif</option>
                    <option value="individual">Individuel (+50€)</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="user_email" class="form-label">Email pour recevoir le devis</label>
                <input type="email" class="form-control" id="user_email" name="user_email" required
                    value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?>">
            </div>

            <div class="price-summary">
                <h5>Estimation</h5>
                <p>Prix de base (activités): <strong id="base-price">
                        <?= isset($_SESSION['panier']) ? array_sum(array_column($_SESSION['panier'], 'prix')) : 0 ?>€
                    </strong></p>
                <p>Suppléments: <strong id="supplements-display">0€</strong></p>
                <p>Total estimé: <strong id="total-display">
                        <?= isset($_SESSION['panier']) ? array_sum(array_column($_SESSION['panier'], 'prix')) : 0 ?>€
                    </strong></p>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" name="submit_devis" class="btn btn-primary">Valider le devis</button>
                <?php if (isset($_SESSION['reservation_id'])): ?>
                    <a href="paiement.php" class="btn btn-success">Procéder au paiement</a>
                <?php endif; ?>
            </div>
            </div>
        </form>
    </section>
    <?php include 'footer.php' ?>
    <script src="JavaScript/devis.js"></script>
</body>

</html>
