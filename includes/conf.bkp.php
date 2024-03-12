<?php
// Base de données
$user = "";
$pass = "";
$dbname = "";
$server = "";

try {
    $cnx = new PDO("mysql:host=$server;dbname=$dbname;charset=utf8mb4", $user, $pass);
}
catch (PDOException $e) {
    echo "ERREUR : La connexion a échouée";
}

// PHPMailer
$username = "";
$password = "";
$host = "";
$name = "";

// Lien général
$lienGeneral = "";
// Lien vers la page de confirmation d'inscription
$lienInscription = $lienGeneral . "inscription/script-confirmermail.php";
// Lien vers la page de réinitialisation du mot de passe
$lienReinitialisation = $lienGeneral . "forgotpassword/reset_password.php";
?>