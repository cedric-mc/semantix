<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    // Erreur PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (!isset($_SESSION['pseudo'])) {
        header('Location: ../home.php');
        exit();
    }
    $pseudo = $_SESSION['pseudo'];
    if (!isset($_POST['paires']) && in_array($_POST['word'], $_SESSION['words'])) {
        header('Location: game.php?erreur=2');
        exit();
    }
    include("game_fonctions.php");
    $newWord = strtolower($_POST['word']);

    if (in_array($newWord, $_SESSION['words'])) {
        header('Location: game.php');
        exit();
    }

    $commande_verif_mot = './C/bin/dictionary_lookup C/arbre_lexicographique.lex ' . $newWord;
    $verif_mot = shell_exec($commande_verif_mot);

    if ($verif_mot == -1) {
        header('Location: game.php?erreur=1');
        exit();
    }
    $_SESSION['words'][] = $newWord;
    unset($_POST['word']);

    $commande_add_word = './C/bin/add_word C/fasttext-fr.bin ' . $newWord . ' ' . $_SESSION['pseudo'];
    exec($commande_add_word);
    // Java : trier les paires
    $commandeJar = "/home/3binf2/mariyaconsta02/jdk-21/bin/java -jar ./java/out/artifacts/java_jar/java.jar partie/game_data_$pseudo.txt partie/resultjava_$pseudo.txt 2>&1";
    exec($commandeJar);
    if (!ifLastWordAdd()) {
        header("Location: game.php?erreur=3");
        exit();
    }

    $_SESSION['paires'] = [];
    $cheminFichier = "partie/resultjava_$pseudo.txt";
    $fichier = fopen($cheminFichier, "r"); // Ouvre le fichier en lecture
    // Vérifie si le fichier est ouvert avec succès
    if ($fichier) {
        // Lit la première ligne de type "Score minimal: 0.5" et récupère le score minimal
        $ligne = fgets($fichier);
        $ligne = explode("Score minimal: ", $ligne);
        if ($_SESSION['scores'] < trim($ligne[1])) {
            $_SESSION['scores'] = trim($ligne[1]);
        }

        fgets($fichier);
        // Lit et traite chaque ligne à partir de la troisième ligne
        while (($ligne = fgets($fichier)) !== false) {
            // Traitement de la ligne, par exemple, l'affichage
            // Exemple ligne : menthe-mentir, 55
            $ligne = explode(",", $ligne);
            $addingWords = trim($ligne[0]);
            $addingWords = explode("-", $addingWords);
            $_SESSION['paires'][] = ["mot1" => trim($addingWords[0]), "mot2" => trim($addingWords[1]), "nombre" => trim($ligne[1])];
        }

        // Ferme le fichier
        fclose($fichier);
    } else {
        // Gestion d'erreur si le fichier ne peut pas être ouvert
        echo "Impossible d'ouvrir le fichier.";
    }
    header('Location: game.php');
    exit();
}
?>