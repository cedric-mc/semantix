<?php
    global $cnx;
    include_once("../includes/conf.php");
    include_once("../includes/fonctions.php");
    session_start();
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

    session_start();
    session_unset();
    session_destroy();
    header('Location: ../index.php?erreur=6');
    exit();
?>