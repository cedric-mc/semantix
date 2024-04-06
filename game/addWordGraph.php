<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include_once("../class/User.php");
        include_once("../class/Game.php");
        include_once("game_fonctions.php");
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Erreur PHP
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        if (!isset($_SESSION['user'])) {
            header('Location: ../');
            exit();
        }
        if (isset($_SESSION['output'])) {
            unset($_SESSION['output']);
        }
        $user = User::createUserFromUser(unserialize($_SESSION['user']));
        $game = Game::createGameFromGame(unserialize($_SESSION['game']));
        // Mettre en minuscule le mot
        if (7 - count($game->getWordsArray()) == 0 || $game->getTour() == 0 || 7 - count($game->getWordsArray()) < 0) {
            $_SESSION['game'] = serialize($game);
            // Script JS pour afficher une alerte
            echo "<script>alert('La partie est terminée.')</script>";
            echo "<script>window.location.replace('./');</script>";
            exit();
        }
        $newWord = strtolower($_POST['word']);
        // Vérifier que le mot n'est pas dans le fichier de partie
        if ($game->isWordInFile($newWord)) {
            $_SESSION['game'] = serialize($game);
            // Script JS pour afficher une alerte
            echo "<script>alert('Le mot a déjà été entré.')</script>";
            echo "<script>window.location.replace('./');</script>";
            exit();
        }
        $game->setLastWord($newWord);
        // Vérifier que le mot n'est pas déjà dans la chaîne
        if ($game->isWordInArray($newWord)) {
            $_SESSION['game'] = serialize($game);
            // Script JS pour afficher une alerte
            echo "<script>alert('Le mot est déjà dans la chaîne.')</script>";
            echo "<script>window.location.replace('./');</script>";
            exit();
        }
        $game->addWord($newWord); // Ajout du mot dans le tableau
        unset($_POST['word']);

        $commande_verif_mot = "./C/bin/dictionary_lookup C/arbre_lexicographique.lex $newWord";
        $verif_mot = shell_exec($commande_verif_mot);
        if ($verif_mot == -1) { // Si le mot n'existe pas dans le dictionnaire
            $_SESSION['game'] = serialize($game);
            // Script JS pour afficher une alerte
            echo "<script>alert('Le mot n\'existe pas dans le dictionnaire.')</script>";
            echo "<script>window.location.replace('./');</script>";
            exit();
        }
        exec("./C/bin/add_word C/fasttext-fr.bin $newWord " . $user->getPseudo());
        // Java : trier les paires
        $commandeJar = "../../../jdk-21/bin/java -cp ChainMotor/target/classes fr.uge.main.Main partie/game_data_" . $user->getPseudo() . ".txt partie/mst_" . $user->getPseudo() . ".txt 2>&1";
        exec($commandeJar, $output);
        // Vérifier si le mot est dans l'arbre
        if (!isWordInTree($user, $newWord)) {
            $_SESSION['game'] = serialize($game);
            // Script JS pour afficher une alerte
            echo "<script>alert('Le mot n\'a pas été ajouté dans l\'arbre.')</script>";
            echo "<script>window.location.replace('./');</script>";
            exit();
        }
        // Vérifier si le mot est dans le graphe
        if (!isWordInGraph($user, $newWord)) {
            $_SESSION['game'] = serialize($game);
            // Script JS pour afficher une alerte
            echo "<script>alert('Le mot n\'a pas été ajouté dans le graphe.')</script>";
            echo "<script>window.location.replace('./');</script>";
            exit();
        }
        $game->addTour();
        $_SESSION['game'] = serialize($game);
        $_SESSION['output'] = $output;
        header('Location: ./');
        exit();
    }
?>