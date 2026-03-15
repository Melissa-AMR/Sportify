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
    $subject = "Votre devis Sportify";
    $message = "Merci pour votre demande de devis.\n\n";
    $message .= "Détail de vos activités :\n";
    foreach ($activities as $item) {
        $message .= "- " . $item['nom_activite'] . " (" . $item['prix'] . "€) - Durée: " . $item['duree'] . " min\n";
    }
    $message .= "\nTotal estimé : " . $total_price . "€\n\n";
    $message .= "Cordialement,\nL'équipe Sportify";
    $headers = "From: contact@sportify.com";
    file_put_contents('devis_emails.log', "To: $to\nSubject: $subject\n\n$message\n\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_devis'])) {
    $course_type = htmlspecialchars($_POST['course_type'] ?? '');
    $user_email = filter_input(INPUT_POST, 'user_email', FILTER_VALIDATE_EMAIL);
    $user_id = $_SESSION['user_id'];

    if (!$user_email) {
        $errorMessage = "Veuillez fournir une adresse email valide.";
    } else {
        $base_price = 0;
        $activities_details = [];
        if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $item) {
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

        $supplements = ($course_type === 'individual') ? 50 : 0;
        $total_price = $base_price + $supplements;

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

$basePrice = isset($_SESSION['panier']) ? array_sum(array_column($_SESSION['panier'], 'prix')) : 0;
$itemCount = isset($_SESSION['panier']) ? count($_SESSION['panier']) : 0;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de devis - Sportify</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style/devis.css">
</head>

<body>
    <!-- Banniere haut -->
    <div class="devis-banner">
        <div class="devis-banner-content">
            <div class="devis-banner-left">
                <h1>Devis Personnalise</h1>
                <p>Reservation #<?= isset($_SESSION['reservation_id']) ? htmlspecialchars($_SESSION['reservation_id']) : '---' ?></p>
            </div>
            <div class="devis-banner-right">
                <div class="devis-banner-stat">
                    <span class="stat-number"><?= $itemCount ?></span>
                    <span class="stat-label">Activite<?= $itemCount > 1 ? 's' : '' ?></span>
                </div>
                <div class="devis-banner-stat">
                    <span class="stat-number"><?= number_format($basePrice, 0) ?>&euro;</span>
                    <span class="stat-label">Estimation</span>
                </div>
            </div>
        </div>
    </div>

    <?php if ($successMessage): ?>
        <div class="devis-alert-bar devis-alert-success">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <div class="devis-alert-bar devis-alert-error">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="devis.php">
        <div class="devis-main">
            <!-- Colonne gauche -->
            <div class="devis-left">
                <div class="devis-panel">
                    <div class="panel-title">
                        <i class="fas fa-list"></i> Activites selectionnees
                    </div>
                    <?php if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])): ?>
                        <table class="devis-table">
                            <thead>
                                <tr>
                                    <th>Activite</th>
                                    <th>Duree</th>
                                    <th>Niveau</th>
                                    <th class="text-right">Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['panier'] as $item):
                                    $stmt = $conn->prepare("SELECT * FROM activites WHERE id = ?");
                                    $stmt->execute([$item['activity_id']]);
                                    $activity = $stmt->fetch();
                                ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($activity['nom_activite']) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($activity['duree']) ?> min</td>
                                        <td>
                                            <span class="badge-niveau">
                                                <?= in_array($activity['nom_activite'], ['Yoga', 'Pilates']) ?
                                                    htmlspecialchars($item['niveau']) : 'Unique' ?>
                                            </span>
                                        </td>
                                        <td class="text-right"><strong><?= number_format($activity['prix'], 2) ?>&euro;</strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="devis-empty">
                            <i class="fas fa-inbox"></i>
                            <p>Aucune activite dans votre panier</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Options -->
                <div class="devis-panel">
                    <div class="panel-title">
                        <i class="fas fa-cog"></i> Configuration
                    </div>
                    <div class="panel-body">
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="course_type">Type de cours</label>
                                <select id="course_type" name="course_type" required>
                                    <option value="group">Cours collectif</option>
                                    <option value="individual">Cours individuel (+50&euro;)</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="user_email">Adresse email</label>
                                <input type="email" id="user_email" name="user_email" required
                                    placeholder="votre@email.com"
                                    value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite : Recap -->
            <div class="devis-right">
                <div class="devis-recap">
                    <div class="recap-title">Recapitulatif</div>

                    <div class="recap-lines">
                        <div class="recap-line">
                            <span>Sous-total (<?= $itemCount ?> activite<?= $itemCount > 1 ? 's' : '' ?>)</span>
                            <span id="base-price"><?= number_format($basePrice, 2) ?>&euro;</span>
                        </div>
                        <div class="recap-line">
                            <span>Supplement cours</span>
                            <span id="supplements-display">0.00&euro;</span>
                        </div>
                    </div>

                    <div class="recap-total">
                        <span>Total TTC</span>
                        <span id="total-display"><?= number_format($basePrice, 2) ?>&euro;</span>
                    </div>

                    <button type="submit" name="submit_devis" class="btn-validate">
                        <i class="fas fa-paper-plane"></i> Valider mon devis
                    </button>

                    <?php if (isset($_SESSION['reservation_id'])): ?>
                        <a href="paiement.php" class="btn-pay">
                            <i class="fas fa-lock"></i> Paiement securise
                        </a>
                    <?php endif; ?>

                    <div class="recap-secure">
                        <i class="fas fa-shield-alt"></i> Paiement 100% securise
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php include 'footer.php' ?>
    <script src="JavaScript/devis.js"></script>
</body>

</html>
