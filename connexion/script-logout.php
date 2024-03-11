<?php
    global $cnx;
    include_once("../includes/conf.php");
    include_once("../includes/fonctions.php");
    include_once("../class/User.php");
    session_start();
    // Erreurs PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (!isset($_SESSION['user'])) {
        header('Location: ../');
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    $pseudo = $user->getPseudo();

    // Récupérer le num_user pour la journalisation
    $queryNum = "SELECT * FROM sae_users WHERE pseudo = :pseudo";
    $stmtNum = $cnx->prepare($queryNum);
    $stmtNum->bindParam(':pseudo', $pseudo);
    $stmtNum->execute();
    $resultat = $stmtNum->fetch(PDO::FETCH_ASSOC);
    $num_user = $resultat['num_user'];

    // Journalisation
    $user->logging($cnx, 2);

    session_unset();
    session_destroy();
    header('Location: ../index.php?erreur=6');
    exit();
?>