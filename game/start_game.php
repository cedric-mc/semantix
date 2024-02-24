<?php
include("game_fonctions.php");
include("../class/Game.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}
$user = unserialize($_SESSION['user']);
// Erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
while (true) {
    $verif_mot = -1;
    while ($verif_mot == -1) {
        $mot1 = randomWord('Liste_mots.txt');
        $commande_verif = "./C/bin/dictionary_lookup C/arbre_lexicographique.lex $mot1";
        $verif_mot = shell_exec($commande_verif);
    }
    $verif_mot = -1;

    while ($verif_mot == -1) {
        $mot2 = randomWord('Liste_mots.txt');
        while ($mot1 == $mot2) {
            $mot2 = randomWord('Liste_mots.txt');
        }
        $commande_verif = "./C/bin/dictionary_lookup C/arbre_lexicographique.lex $mot2";
        $verif_mot = shell_exec($commande_verif);
    }

    $commande_start_game = "./C/bin/new_game C/fasttext-fr.bin $mot1 $mot2 $user->pseudo";
    exec($commande_start_game);

    $commandeJava = "/home/3binf2/mariyaconsta02/jdk-21/bin/java -cp ChainMotor/target/classes fr.uge.main.Main $user->pseudo 0 2>&1";
    exec($commandeJava);

    $distance = 100;
    $fichier = fopen("partie/game_data_$user->pseudo.txt", "r");
    // Lire le fichier jusqu’à la 8ème ligne et stocker ce qui se trouve après "distance: " dans $distance
    for ($i = 0; $i < 8; $i++) {
        $ligne = fgets($fichier);
        if ($i == 7) {
            $chaine = $ligne;
            $parties = explode("distance:", $chaine);
            if (count($parties) > 1) {
                $distance = floatval(trim($parties[1]));
            }
            $distance = substr($ligne, strpos($ligne, ", distance: ") + 12);
        }
    }
    fclose($fichier);

    if ($distance <= 40) {
        break;
    }
}

$game = new Game($user, 1);

$_SESSION['game'] = serialize($game);

header('Location: game.php');
?>