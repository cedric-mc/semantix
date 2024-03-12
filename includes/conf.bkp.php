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

    // Lien général vers le site
    $lienGeneral = "";
    // Vers la page de confirmation d'inscription
    $lienInscription = $lienGeneral . "inscription/script-confirmermail.php";
    // Vers la page de réinitialisation du mot de passe
    $lienReinitialisation = $lienGeneral . "forgotpassword/reset_password.php";
?>