<?php
include_once("../class/User.php");
include_once("../class/Game.php");
include_once("../includes/conf.php");
include_once("../includes/fonctions.php");
include_once("game_fonctions.php");
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user'])) {
    header("Location: ./");
    exit();
}
$user = User::createUserFromUser(unserialize($_SESSION['user']));
$game = unserialize($_SESSION['game']);
if (isset($_SESSION['output'])) {
    unset($_SESSION['output']);
}
$pseudo = $user->getPseudo();
$idUser = $user->getIdUser();

if ($game->getNumberOfWords() >= 2) {
    // Score final
    // Ajout du score final dans la base de données
    $calculateScore = calculateScore($user);
    $requestAddFinalScore = $cnx->prepare("INSERT INTO sae_scores (num_user, score) VALUES (:num_user, :score)");
    $requestAddFinalScore->bindParam(':num_user', $idUser);
    $requestAddFinalScore->bindParam(':score', $calculateScore);
    $requestAddFinalScore->execute();
    $requestAddFinalScore->closeCursor();
    $user->logging($cnx, 8);
    unset($_SESSION['words']);
}

// Supprimer tous les fichiers associés à l'utilisateur qui sont dans le dossier partie
unlink("partie/game_data_$pseudo.txt");
unlink("partie/mst_$pseudo.txt");
unlink("partie/best_path_$pseudo.txt");
// Supprimer l'instance de la classe Game 
unset($_SESSION['game']);
if (isset($_GET['again'])) {
    header("Location: start_game.php");
    exit();
}
header("Location: ../");
exit();
?>