<?php
    include_once("../class/User.php");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    function calculateScore(User $user) {
        $fichier = fopen("partie/best_path_" . $user->getPseudo() . ".txt", 'r');
        // Lire jusqu'à la ligne MinimumSimilarity : 39.21 et stocker la valeur dans $score
        $score = 0;
        while (($ligne = fgets($fichier)) !== false) {
            if (strpos($ligne, "MinimumSimilarity") !== false) {
                $score = floatval(trim(str_replace("MinimumSimilarity : ", "", $ligne)));
                break;
            }
        }
        fclose($fichier);
        return $score;
    }

    function fileToArray(User $user) {
        $paires = [];
        $fichier = fopen("partie/best_path_" . $user->getPseudo() . ".txt", "r");
        // Ignorer les 4 premières lignes
        for ($i = 0; $i < 4; $i++) {
            fgets($fichier);
        }
        $format = "/^(\w+) -> (\w+) : (\d+(\.\d+)?)$/";
        // Lire le fichier jusqu'à la fin
        while (($ligne = fgets($fichier)) !== false) {
            if (strpos($ligne, "EOF") !== false) {
                continue;
            }
            // Si la ligne est sous la forme "mot1 -> mot2 : nombre"
            if (preg_match($format, $ligne, $matches)) {
                $mot1 = $matches[1]; // Récupérer le premier mot
                $mot2 = $matches[2]; // Récupérer le deuxième mot
                $nombre = floatval($matches[3]); // Récupérer le nombre et le convertir en double
                $paires[] = ["mot1" => $mot1, "mot2" => $mot2, "nombre" => $nombre];
            }
        }
        fclose($fichier);
        foreach (file("partie/best_path_" . $user->getPseudo() . ".txt") as $ligne) {
            // Ignorer les lignes vides et les lignes commençant par "BestPath", "startWord", "endWord" ou "bestPathEdges"
            if (!empty($ligne) && !preg_match('/^(BestPath|startWord|endWord|bestPathEdges)/', $ligne)) {
                if (strpos("EOF", $ligne) === true) {
                    break;
                }
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

    // Fonction pour générer un Array à partir du fichier de partie de l'arbre MST
    function fileToArrayTree(User $user) {
        $paires = [];
        $fichier = fopen("partie/mst_" . $user->getPseudo() . ".txt", "r");
        // Ignorer les 4 premières lignes
        for ($i = 0; $i < 4; $i++) {
            fgets($fichier);
        }
        $format = "/^(\w+)_(\w+),(\d+(\.\d+)?)$/";
        // Lire le fichier jusqu'à la fin
        while (($ligne = fgets($fichier)) !== false) {
            if (strpos($ligne, "EOF") !== false) {
                continue;
            }
            // Si la ligne est sous la forme "mot1_mot2, nombre"
            if (preg_match($format, $ligne, $matches)) {
                $mot1 = $matches[1]; // Récupérer le premier mot
                $mot2 = $matches[2]; // Récupérer le deuxième mot
                $nombre = floatval($matches[3]); // Récupérer le nombre et le convertir en double
                $paires[] = ["mot1" => $mot1, "mot2" => $mot2, "nombre" => $nombre];
            }
        }
        fclose($fichier);
        return $paires;
    }

    function randomWord($filename) {
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
    
    function createDataForGraph(User $user, $paires) {
        foreach (file("partie/best_path_" . $user->getPseudo() . ".txt") as $line) {
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

    // Fonction pour savoir si le mot a été ajouté dans l'arbre
    function isWordInTree(User $user, $word) {
        $fichier = fopen("partie/mst_" . $user->getPseudo() . ".txt", "r");
        $isInGraph = false;
        while (($ligne = fgets($fichier)) !== false) {
            if (strpos($ligne, $word) !== false) {
                $isInGraph = true;
                break;
            }
        }
        fclose($fichier);
        return $isInGraph;
    }

    // Fonction pour savoir si le mot a été ajouté dans le graphe
    function isWordInGraph(User $user, $word) {
        $fichier = fopen("partie/best_path_" . $user->getPseudo() . ".txt", "r");
        $isInGraph = false;
        while (($ligne = fgets($fichier)) !== false) {
            if (strpos($ligne, $word) !== false) {
                $isInGraph = true;
                break;
            }
        }
        fclose($fichier);
        return $isInGraph;
    }
?>