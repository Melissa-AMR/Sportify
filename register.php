<?php
require 'config.php'; 

$message = ""; // Variable pour stocker les messages d'erreur ou de succès

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Vérification des champs 
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif ($password !== $confirm_password) {
        $message = "Les mots de passe ne correspondent pas.";

    } else {
        // Vérifier si l'email ou le nom d'utilisateur existe déjà
        $sql = "SELECT id FROM utilisateurs WHERE email = :email OR nom = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = "Cet email ou ce nom d'utilisateur est déjà utilisé.";
        } else {
            // cacher le mot de passe 
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Insérer l'utilisateur dans la bd
            $sql = "INSERT INTO utilisateurs (nom, email, password) VALUES (:username, :email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php?message=Inscription réussie! Vous pouvez maintenant vous connecter.");
                exit();
            } else {
                $message = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Sportify</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/register.css">
</head>

<body>

    <header class="page-header">
        <a href="accueil.php" title="Retour à l'accueil">
            <img src="images/logosf.png" alt="Sportify" class="header-logo">
        </a>
    </header>

    <div class="container mt-5">
        <h2 class="text-center">Je crée mon compte </h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-warning"><?= $message ?></div>
        <?php endif; ?>
            <!-- formulaire de inscription --> 
        <form action="register.php" method="POST" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control w-70" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" class="form-control w-70" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control w-70" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmez le mot de passe</label>
                <input type="password" class="form-control w-70" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>

        <p class="mt-3">Déjà inscrit ? <a href="Login.php">Connectez-vous ici</a></p>
    </div>
    <script src="JavaScript/register.js"></script>
</body>
</html>