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
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Votre Panier</title>
    <link rel="stylesheet" href="style/cart.css">
</head>

<body>
    <h1>Votre Panier</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message success"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (empty($_SESSION['panier'])): ?>
        <p>Votre panier est vide</p>
    <?php else: ?>
        <form method="post">
            <table>
                <tr>
                    <th>Activité</th>
                    <th>Prix</th>
                    <th>Niveau</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($_SESSION['panier'] as $index => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nom_activite']) ?></td>
                        <td><?= $item['prix'] ?>€</td>
                        <td>
                            <?php if (in_array($item['nom_activite'], ['Yoga', 'Pilates'])): ?>
                                <select name="niveaux[<?= $index ?>]">
                                    <option value="débutant" <?= $item['niveau'] == 'débutant' ? 'selected' : '' ?>>Débutant</option>
                                    <option value="intermédiaire" <?= $item['niveau'] == 'intermédiaire' ? 'selected' : '' ?>>
                                        Intermédiaire</option>
                                    <option value="avancé" <?= $item['niveau'] == 'avancé' ? 'selected' : '' ?>>Avancé</option>
                                </select>
                            <?php else: ?>
                                <input type="hidden" name="niveaux[<?= $index ?>]" value="unique">
                                Unique
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="cart.php?remove=<?= $index ?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div style="display: flex; justify-content: center; gap: 20px; margin: 40px 0; flex-wrap: wrap;">
                <a href="activities.php" class="btn" style="margin: 0;">Retour aux activités</a>
                <button type="submit" name="validate" formaction="cart.php?validate=1">
                    Confirmer ma réservation
                </button>
            </div>
        </form>
    <?php endif; ?>
</body>
</html>