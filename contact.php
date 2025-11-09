<?php
require 'config.php';
include 'header.php';

$success = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Validation
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Envoi d'email (il faut plutot un serveur SMTP mais)
        $to = "sportify@mail.com";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        $email_content = "Nom: $name\n";
        $email_content .= "Email: $email\n";
        $email_content .= "Sujet: $subject\n\n";
        $email_content .= "Message:\n$message";

        //la fonction mail envoie le mail mais necessite un serveur SMTP
        mail($to, "Nouveau message de $name", $email_content, $headers);
        $success = true;
        $_SESSION['success_message'] = "Votre message a bien été envoyé !";
    } else {
        $_SESSION['error_message'] = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Sportify</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/contact.css">
</head>

<body>
    <div class="container py-5">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success text-center"><?= $_SESSION['success_message'] ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger text-center"><?= $_SESSION['error_message'] ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <div class="contact-container">
            <h1 class="text-center mb-4">Contactez-nous</h1>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="contact-info h-100">
                        <h3><i class="bi bi-info-circle-fill me-2"></i>Nos coordonnées</h3>
                        <hr>
                        <p class="mb-3">
                            Vous avez une question, besoin d’un conseil ou envie de nous faire un retour ?
                            Notre équipe vous répond rapidement et avec le sourire 💪
                        </p>

                        <p><strong><i class="bi bi-envelope-fill me-2 text-danger"></i>Email :</strong>
                            sportify@mail.com</p>
                        <p><strong><i class="bi bi-telephone-fill me-2 text-danger"></i>Téléphone :</strong> 01 23 45 67 89</p>

                        <p class="mt-4"><strong><i class="bi bi-clock-fill me-2 text-danger"></i>Horaires d'ouverture
                                :</strong></p>
                        <ul class="ps-4 mb-0">
                            <li>Lundi à Vendredi : 09h00 - 18h00</li>
                            <li>Samedi : 10h00 - 15h00</li>
                            <li>Dimanche : fermé 💤</li>
                        </ul>
                    </div>
                </div>

                <!-- formulaire de contact -->
                <div class="col-md-6">
                    <form method="POST" action="contact.php">
                        <div class="mb-3">
                            <label for="name" class="form-label required">Nom complet</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label required">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Sujet</label>
                            <input type="text" class="form-control" id="subject" name="subject">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label required">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn-custom btn-lg">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="JavaScript/contact.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>