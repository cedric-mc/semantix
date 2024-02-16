<?php
include("game_fonctions.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['pseudo'] = "cedric-mc";
$verif_mot1 = -1;
$verif_mot2 = -1;

while ($verif_mot1 == -1) {
    $mot1 = randomWord('Liste_mots.txt');
    $commande_verif_mot1 = './C/bin/dictionary_lookup C/arbre_lexicographique.lex ' . $mot1;
    $verif_mot1 = shell_exec($commande_verif_mot1);
}

while ($verif_mot2 == -1) {
    $mot2 = randomWord('Liste_mots.txt');
    while ($mot1 == $mot2) {
        $mot2 = randomWord('Liste_mots.txt');
    }
    $commande_verif_mot2 = './C/bin/dictionary_lookup C/arbre_lexicographique.lex ' . $mot2;
    $verif_mot2 = shell_exec($commande_verif_mot2);
}

$commande_start_game = './C/bin/new_game C/fasttext-fr.bin ' . $mot1 . ' ' . $mot2 . ' ' . $_SESSION['pseudo'];
exec($commande_start_game);
$commandeJava = "/home/3binf2/mariyaconsta02/jdk-21/bin/java -cp ChainMotor/target/classes fr.uge.main.Main partie/game_data_$_SESSION[pseudo].txt partie/mst_$_SESSION[pseudo].txt partie/best_path_$_SESSION[pseudo].txt 2>&1";
exec($commandeJava, $output);
echo "<pre>";
print_r($output);
echo "</pre>";
exit();

header('Location: game.php');
?>