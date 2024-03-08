<?php
// Base de données
$user = "";
$pass = "";
$dbname = "";
$server = "";

try {
    $cnx = new PDO("mysql:host=$server;dbname=$dbname;charset=utf8mb4", $user, $pass);
    if ($cnx->getAttribute(PDO::ATTR_CONNECTION_STATUS)) {
        echo "Connexion réussie";
    }
}
catch (PDOException $e) {
    echo "ERREUR : La connexion a échouée";
}

// PHPMailer
$username = "";
$password = "";
$host = "";
$name = "";
?>