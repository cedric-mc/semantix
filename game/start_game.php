<?php
    include_once("game_fonctions.php");
    include_once("../class/Game.php");
    include_once("../class/User.php");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user'])) {
        header("Location: ../");
        exit();
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    // Erreurs PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    while (true) {
        $verif_mot = -1;
        while ($verif_mot == -1) {
            $mot1 = randomWord("Liste_mots.txt");
            $commande_verif = "./C/bin/dictionary_lookup C/arbre_lexicographique.lex $mot1";
            $verif_mot = shell_exec($commande_verif);
        }
        $verif_mot = -1;

        while ($verif_mot == -1) {
            $mot2 = randomWord("Liste_mots.txt");
            while ($mot1 == $mot2) {
                $mot2 = randomWord("Liste_mots.txt");
            }
            $commande_verif = "./C/bin/dictionary_lookup C/arbre_lexicographique.lex $mot2";
            $verif_mot = shell_exec($commande_verif);
        }

        $commande_start_game = "./C/bin/new_game C/fasttext-fr.bin $mot1 $mot2 " . $user->getPseudo();
        exec($commande_start_game);

        $distance = 100;
        $fichier = fopen("partie/game_data_" . $user->getPseudo() . ".txt", "r");
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

    $commandeJava = "../../../jdk-21/bin/java -cp ChainMotor/target/classes fr.uge.main.Main partie/game_data_" . $user->getPseudo() . ".txt 2>&1";
    exec($commandeJava, $output);
    $_SESSION['output'] = $output;

    $game = new Game($user->getPseudo(), 1, array(), "");
    $game->addWordsFromArray([$mot1, $mot2]);

    // Vérifier si les trois fichiers existent
    if (!file_exists("partie/game_data_" . $user->getPseudo() . ".txt") || !file_exists("partie/mst_" . $user->getPseudo() . ".txt") || !file_exists("partie/best_path_" . $user->getPseudo() . ".txt")) {
        header('Location: ../');
        exit();
    }
    $_SESSION['game'] = serialize($game);
    header('Location: ./');
    exit();
?>