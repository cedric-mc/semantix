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

// Lien vers la page de confirmation d'inscription
$lienInscription = "";
?>