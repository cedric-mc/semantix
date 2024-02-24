<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['pseudo'])) {
    header("Location: ./");
    exit();
}

// Ajout du score final dans la base de données
include("../conf.bkp.php");
include("../includes/fonctions.php");
include("game_fonctions.php");

// Score final
$finalScore = calculateScore();
$requestAddFinalScore = $cnx->prepare("INSERT INTO SAE_SCORES (num_user, score) VALUES (:num_user, :score)");
$requestAddFinalScore->bindParam(':num_user', $_SESSION['num_user']);
$requestAddFinalScore->bindParam(':score', $finalScore);
$requestAddFinalScore->execute();
$requestAddFinalScore->closeCursor();
trace($_SESSION['num_user'], "A Joué une partie", $cnx);
unset($_SESSION['words']);

// Supprimer tous les fichiers associés à l'utilisateur qui sont dans le dossier partie
unlink("partie/game_data_$_SESSION[pseudo].txt");
unlink("partie/mst_$_SESSION[pseudo].txt");
unlink("partie/best_path_$_SESSION[pseudo].txt");
// Supprimer l'instance de la classe Game 
unset($_SESSION['game']);

header("Location: ../");
exit();
?>