<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    // Erreur PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (!isset($_SESSION['user'])) {
        header('Location: ../index.php');
        exit();
    }
    if (isset($_SESSION['output'])) {
        unset($_SESSION['output']);
    }
    require_once("../class/User.php");
    require_once("../class/Game.php");
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    $game = unserialize($_SESSION['game']);
    $newWord = $_POST['word'];
    $game->setLastWord($newWord);
    include("game_fonctions.php");

    if ($game->isWordInArray($newWord)) {
        $_SESSION['game'] = serialize($game);
        header('Location: ./?erreur=2');
        exit();
    }
    $game->addWord($newWord); // Ajout du mot dans le tableau
    unset($_POST['word']);

    $commande_verif_mot = "./C/bin/dictionary_lookup C/arbre_lexicographique.lex $newWord";
    $verif_mot = shell_exec($commande_verif_mot);
    if ($verif_mot == -1) {
        $_SESSION['game'] = serialize($game);
        header('Location: ./?erreur=1');
        exit();
    }
    exec("./C/bin/add_word C/fasttext-fr.bin $newWord " . $user->getPseudo());
    // Java : trier les paires
    $commandeJar = "/home/3binf2/mariyaconsta02/jdk-21/bin/java -cp ChainMotor/target/classes fr.uge.main.Main partie/game_data_" . $user->getPseudo() . ".txt partie/mst_" . $user->getPseudo() . ".txt 2>&1";
    exec($commandeJar, $output);
    $_SESSION['output'] = $output;
    $_SESSION['game'] = serialize($game);
    header('Location: ./');
    exit();
}
?>