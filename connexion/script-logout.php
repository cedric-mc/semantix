<?php
    global $cnx;
    include_once("../includes/conf.php");
    include_once("../includes/fonctions.php");
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: ../');
    }

    // Récupérer le num_user pour la journalisation
    $queryNum = "SELECT * FROM sae_users WHERE pseudo = :pseudo";
    $stmtNum = $cnx->prepare($queryNum);
    $stmtNum->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmtNum->execute();
    $resultat = $stmtNum->fetch(PDO::FETCH_ASSOC);
    $num_user = $resultat['num_user'];

    // Journalisation
    trace($num_user, "Déconnexion du Site", $cnx);

    session_start();
    session_unset();
    session_destroy();
    header('Location: ../index.php?erreur=6');
    exit();
?>