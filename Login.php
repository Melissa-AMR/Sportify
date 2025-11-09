<?php
require 'config.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // verification si le mot de passe et l'email sont corretcts 
        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["nom"] = $user["nom"];
            header("Location: accueil.php");
            exit();
        } else {
            $error = "Email ou mot de passe incorrect";
        }
    } catch (PDOException $e) {
        $error = "Erreur lors de la connexion : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Sportify</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-header text-center mb-4">
            <a href="accueil.php">
                <img src="images/logosf.png" alt="Sportify" class="login-logo">
            </a>
        </div>

        <div class="login-card card p-4 shadow-lg mx-auto">
            <h2 class="text-center mb-4">Connexion</h2>
            <?php if ($error): ?>
                <div class='alert alert-danger text-center'><?= $error ?></div>
            <?php endif; ?>
        
                <!-- Carte de connexion centrée -->
            <form action="Login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Se connecter</button>
            </form>

            <p class="text-center mt-3">Pas encore inscrit ?
                <a href="register.php" class="text-decoration-none">Créer un compte</a>
            </p>
        </div>
    </div>
    <script src="JavaScript/login.js"></script>
</body>
</html>