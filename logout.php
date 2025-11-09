<?php
require 'config.php';
include 'header.php'; 

// Supprimer les variables de la session
session_unset();

// Détruire la session
session_destroy();

// Rediriger vers la page d'accueil
header("Location: accueil.php");
exit();
?>