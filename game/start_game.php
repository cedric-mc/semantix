<?php
include("game_fonctions.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['pseudo'] = "cedric-mc";
// Erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
do {
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

    $distance = 100;
    $fichier = fopen("partie/game_data_$_SESSION[pseudo].txt", "r");
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
    $verif_mot = -1;
} while (1 == 0);

$commande_start_game = "./C/bin/new_game C/fasttext-fr.bin $mot1 $mot2 $_SESSION[pseudo]";
exec($commande_start_game);

$commandeJava = "/home/3binf2/mariyaconsta02/jdk-21/bin/java -cp ChainMotor/target/classes fr.uge.main.Main partie/game_data_$_SESSION[pseudo].txt partie/mst_$_SESSION[pseudo].txt partie/best_path_$_SESSION[pseudo].txt 2>&1";
exec($commandeJava);

header('Location: game.php');
?>