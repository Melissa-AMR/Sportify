<?php
require 'config.php';
include 'header.php';
//
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['niveaux'] as $index => $niveau) {
        if (isset($_SESSION['panier'][$index])) {
            $_SESSION['panier'][$index]['niveau'] = $niveau;
        }
    }
    $_SESSION['message'] = "Panier mis à jour";
    header("Location: cart.php");
    exit();
}

// Suppression d'un élément
if (isset($_GET['remove'])) {
    $index = (int) $_GET['remove'];
    if (isset($_SESSION['panier'][$index])) {
        unset($_SESSION['panier'][$index]);
        // Réindexez le tableau
        $_SESSION['panier'] = array_values($_SESSION['panier']);
        $_SESSION['message'] = "Activité retirée du panier";
    }
    header("Location: cart.php");
    exit();
}

// Validation du panier
if (isset($_GET['validate'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    try {
        $conn->beginTransaction();
        // generer un nouveau ID
        $reservation_id = 'RES_' . uniqid();
        // Stockez l'ID de réservation
        $_SESSION['reservation_id'] = $reservation_id;

        foreach ($_SESSION['panier'] as $item) {
            $stmt = $conn->prepare("INSERT INTO reservations  (reservation_id, utilisateur_id, activite_id, niveau, prix) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $reservation_id,
                $_SESSION['user_id'],
                $item['activity_id'],
                $item['niveau'],
                $item['prix']
            ]);
        }

        $conn->commit();
        header("Location: devis.php");
        exit();
    } catch (PDOException $e) {
        $conn->rollBack();
        $_SESSION['message'] = "Erreur: " . $e->getMessage();
        header("Location: cart.php");
        exit();
    }
}

$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Panier - Sportify</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style/cart.css">
</head>

<body>
    <section class="cart-section">
        <div class="cart-container">
            <div class="cart-header">
                <h1><i class="fas fa-shopping-bag"></i> Votre Panier</h1>
                <p class="cart-count"><?= count($_SESSION['panier']) ?> activite<?= count($_SESSION['panier']) > 1 ? 's' : '' ?></p>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="cart-alert">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (empty($_SESSION['panier'])): ?>
                <div class="cart-empty">
                    <i class="fas fa-shopping-bag"></i>
                    <h3>Votre panier est vide</h3>
                    <p>Explorez nos activites et commencez votre transformation</p>
                    <a href="activities.php" class="btn-cart-primary"><i class="fas fa-arrow-left"></i> Decouvrir les activites</a>
                </div>
            <?php else: ?>
                <form method="post">
                    <div class="cart-items">
                        <?php foreach ($_SESSION['panier'] as $index => $item): ?>
                            <div class="cart-item">
                                <div class="cart-item-info">
                                    <div class="cart-item-icon">
                                        <i class="fas fa-dumbbell"></i>
                                    </div>
                                    <div class="cart-item-details">
                                        <h3><?= htmlspecialchars($item['nom_activite']) ?></h3>
                                        <div class="cart-item-niveau">
                                            <?php if (in_array($item['nom_activite'], ['Yoga', 'Pilates'])): ?>
                                                <select name="niveaux[<?= $index ?>]" class="niveau-select">
                                                    <option value="débutant" <?= $item['niveau'] == 'débutant' ? 'selected' : '' ?>>Debutant</option>
                                                    <option value="intermédiaire" <?= $item['niveau'] == 'intermédiaire' ? 'selected' : '' ?>>Intermediaire</option>
                                                    <option value="avancé" <?= $item['niveau'] == 'avancé' ? 'selected' : '' ?>>Avance</option>
                                                </select>
                                            <?php else: ?>
                                                <input type="hidden" name="niveaux[<?= $index ?>]" value="unique">
                                                <span class="niveau-badge">Niveau unique</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="cart-item-actions">
                                    <span class="cart-item-price"><?= number_format($item['prix'], 2) ?>&euro;</span>
                                    <a href="cart.php?remove=<?= $index ?>" class="btn-remove" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="cart-summary">
                        <div class="cart-total">
                            <span>Total</span>
                            <span class="total-price"><?= number_format($total, 2) ?>&euro;</span>
                        </div>
                    </div>

                    <div class="cart-buttons">
                        <a href="activities.php" class="btn-cart-secondary">
                            <i class="fas fa-arrow-left"></i> Continuer
                        </a>
                        <button type="submit" name="validate" formaction="cart.php?validate=1" class="btn-cart-primary">
                            Confirmer la reservation <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </section>
    <?php include 'footer.php' ?>
</body>
</html>
