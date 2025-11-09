<?php
require 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['reservation_id'])) {
    die("Aucune réservation trouvée. Veuillez compléter votre devis d'abord.");
}

// Récupération du devis 
try {
    $stmt = $conn->prepare("SELECT * FROM devis WHERE reservation_id = ? AND user_id = ?");
    $stmt->execute([$_SESSION['reservation_id'], $_SESSION['user_id']]);
    $devis = $stmt->fetch();

    if (!$devis) {
        die("Aucun devis trouvé pour cette réservation. Veuillez d'abord valider votre devis.");
    }
} catch (PDOException $e) {
    die("Erreur de base de données: " . $e->getMessage());
}

// Traitement du formulaire de paiement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['process_payment'])) {
    try {
        $conn->beginTransaction();

        // Mettre à jour le statut du paiement
        $update = $conn->prepare("UPDATE devis SET payment_status = 'paid', payment_date = NOW() WHERE reservation_id = ?");
        $update->execute([$_SESSION['reservation_id']]);
        $conn->commit();
        
        // pas le temps pour aller plus loin 😓 . 
        $_SESSION['payment_success'] = true;
        exit();
    } catch (PDOException $e) {
        $conn->rollBack();
        $errorMessage = "Erreur lors du paiement: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement - Sportify</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .payment-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .payment-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-container">
            <div class="payment-header">
                <h2>Finaliser votre paiement</h2>
                <p class="text-muted">Référence: <?= htmlspecialchars($_SESSION['reservation_id']) ?></p>
            </div>
            
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <h4>Récapitulatif</h4>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5>Total à payer</h5>
                            <p class="display-6"><?= number_format($devis['total_price'], 2) ?>€</p>
                            <hr>
                            <p>Prix de base: <?= number_format($devis['base_price'], 2) ?>€</p>
                            <p>Suppléments: <?= number_format($devis['supplements'], 2) ?>€</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h4>Méthode de paiement</h4>
                    <form method="POST">
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="credit-card" value="credit_card" checked>
                                <label class="form-check-label" for="credit-card">
                                    Carte de crédit
                                </label>
                            </div>
                        </div>
                        
                        <div id="credit-card-fields">
                            <div class="form-group mb-3">
                                <label for="card-number">Numéro de carte</label>
                                <input type="text" class="form-control" id="card-number" placeholder="4242 4242 4242 4242" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="card-expiry">Date d'expiration</label>
                                    <input type="text" class="form-control" id="card-expiry" placeholder="MM/AA" required>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="card-cvc">Code sécurité</label>
                                    <input type="text" class="form-control" id="card-cvc" placeholder="CVC" required>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" name="process_payment" class="btn btn-success btn-lg w-100 mt-3">
                            Payer maintenant
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="JavaScript/paiement.js"></script>
</body>
</html>