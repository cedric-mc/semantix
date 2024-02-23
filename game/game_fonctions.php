<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    function fileToArray($array) {
        $paires = [];
        // Parcourir chaque ligne du fichier
        foreach (file("partie/best_path_$_SESSION[pseudo].txt") as $ligne) {
            // Ignorer les lignes vides et les lignes commençant par "BestPath", "startWord", "endWord" ou "bestPathEdges"
            if (!empty($ligne) && !preg_match('/^(BestPath|startWord|endWord|bestPathEdges)/', $ligne)) {
                // Diviser la ligne en utilisant la virgule comme délimiteur
                $informations = explode(",", $ligne);

                // Si le tableau a exactement deux éléments
                if (count($informations) === 2) {
                    // Diviser la première information (la paire de mots) en utilisant le caractère underscore comme délimiteur
                    $mots = explode("_", trim($informations[0]));

                    // Récupérer les mots individuels
                    $mot1 = $mots[0];
                    $mot2 = $mots[1];
                    $distance = floatval(trim($informations[1])); // Convertir la quantité en nombre

                    // Ajouter les informations au tableau
                    $paires[] = ["mot1" => $mot1, "mot2" => $mot2, "nombre" => $distance];
                }
            }
        }
        return $paires;
    }

    function randomWord($filename)
    {
        // Lire le contenu du fichier dans une chaîne
        $content = file_get_contents($filename);
    
        // Supprimer les espaces éventuels et diviser la chaîne en un tableau de mots
        $listeDeMots = explode(';', trim($content));
    
        // Obtenir la longueur du tableau
        $nbMots = count($listeDeMots);
    
        // Générer un indice aléatoire pour sélectionner un mot au hasard
        $indiceMot = rand(0, $nbMots - 1);
    
        // Récupérer le mot correspondant à l'indice généré
        $mot = $listeDeMots[$indiceMot];
    
        return $mot;
    }
    
    function startGame()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['paires'] = [];
        $_SESSION['words'] = [];
        $_SESSION['scores'] = 0;
    
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
    
        $_SESSION['words'][0] = $mot1;
        $_SESSION['words'][1] = $mot2;
        $_SESSION['paires'] = ajouterPaire($_SESSION['paires'], $_SESSION['words'][0], $_SESSION['words'][1]);
    
        // Création du fichier de résultat java le 'resultjava_pseudo.txt' avec les droits rw-rw-rw-
        $fichier_resultat = fopen("partie/resultjava_$_SESSION[pseudo].txt", 'w');
        // On écrit les 3 premières lignes du fichier
        fwrite($fichier_resultat, "Score minimal: " . $_SESSION['paires'][0]['nombre'] . "\n");
        fwrite($fichier_resultat, "Dernier mot ajouté: true\n");
        fwrite($fichier_resultat, $_SESSION['paires'][0]['mot1'] . "-" . $_SESSION['paires'][0]['mot2'] . ", " . $_SESSION['paires'][0]['nombre'] . "\n");
        fclose($fichier_resultat);
    }
    
    function ajouterPaire($tableau, $mot1, $mot2)
    { // Ajoute une paire de mots au tableau
        $distance = null;
        $recherche = $mot1 . "-" . $mot2;
        $recherche2 = $mot2 . "-" . $mot1;
        $fichier_partie = fopen("partie/game_data_$_SESSION[pseudo].txt", 'r');
        while (($ligne = fgets($fichier_partie)) !== false) {
            if (((strpos($ligne, $recherche) !== false) || (strpos($ligne, $recherche2) !== false)) && strpos($ligne, "distance: ") !== false) {
                // Extrait la valeur de la distance de la ligne
                if ((strpos($ligne, $recherche) !== false)) {
                    $distanceTexte = trim(str_replace("$mot1-$mot2, distance: ", "", $ligne));
                }
                if (strpos($ligne, $recherche2) !== false) {
                    $distanceTexte = trim(str_replace("$mot2-$mot1, distance: ", "", $ligne));
                }
                $distance = floatval($distanceTexte);
                break;
            }
        }
        fclose($fichier_partie);
    
        $nouvellePaire = ["mot1" => $mot1, "mot2" => $mot2, "nombre" => $distance];
        $tableau[] = $nouvellePaire;
        return $tableau;
    }
    
    function calculateScore()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['scores'];
    }
    
    function createDataForGraph($paires) {
        // Utilisation d’un tableau associatif pour stocker les relations entre les mots
        /*
        BestPath :
        startWord : mot1
        endWord : mot2
        bestPathEdges : 
        mot1_mot2,15.63
        mot2_mot3,12.34
        */
        foreach (file("partie/best_path_$_SESSION[pseudo].txt") as $line) {
            if (strpos($line, "bestPathEdges") !== false) {
                $ligne = $line;
            }
        }
        $relations = [];
    
        foreach ($paires as $paire) {
            $mot1 = $paire["mot1"];
            $mot2 = $paire["mot2"];
            $nombre = $paire["nombre"];
    
            // Si la relation entre mot1 et mot2 n'existe pas encore
            if (!isset($relations[$mot1][$mot2])) {
                $relations[$mot1][$mot2] = $nombre;
            } else {
                // Si la relation existe déjà, additionner le nombre
                $relations[$mot1][$mot2] += $nombre;
            }
        }
    
        $nodes = [];
        $links = [];
    
        // Création des nodes et des links à partir des relations
        foreach ($relations as $mot1 => $rel) {
            $mot1 = ucfirst($mot1);
            $nodes[] = ["id" => $mot1];
    
            foreach ($rel as $mot2 => $nombre) {
                $mot2 = ucfirst($mot2);
                $nodes[] = ["id" => $mot2];
                $links[] = [
                    "source" => $mot1,
                    "target" => $mot2,
                    "linkTextPath" => $nombre
                ];
            }
        }
    
        return [
            "nodes" => $nodes,
            "links" => $links
        ];
    }
?>