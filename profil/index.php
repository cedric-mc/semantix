<?php
    global $lastConnexionProfil, $cnx, $scoreProfil, $top3ScoresProfil;
    // Erreur php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    include_once("../class/User.php");
    include("../includes/conf.php");
    include("../includes/fonctions.php");
    include("../includes/requetes.php");
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: ../");
        exit;
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    $pseudo = $user->getPseudo();

    // Requête pour récupérer les informations de l'utilisateur
    $profilRequest = $cnx->prepare($lastConnexionProfil);
    $profilRequest->bindParam(":pseudo", $pseudo);
    $profilRequest->execute();
    $profilResult = $profilRequest->fetch(PDO::FETCH_OBJ);
    $profilRequest->closeCursor();

    // Requête pour récupérer les statistiques de l'utilisateur
    $scoreRequest = $cnx->prepare($scoreProfil);
    $scoreRequest->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $scoreRequest->execute();
    $scoreResult = $scoreRequest->fetch(PDO::FETCH_OBJ);
    $scoreRequest->closeCursor();

    // Requête pour récupérer le top 3 des meilleurs scores (max), le pseudo n'apparaît qu'une seule fois
    $top3Request = $cnx->prepare($top3ScoresProfil);
    $top3Request->execute();
    $top3Result = $top3Request->fetchAll(PDO::FETCH_OBJ);
    $top3Request->closeCursor();
    $menu = 1;

    // Messages d'erreurs possibles
    $erreursMdp = [
        1 => ["Le mot de passe a bien été changé.", "alert-success"],
        2 => ["L'ancien mot de passe est incorrect.", "alert-danger"],
        3 => ["Les nouveaux mots de passe ne correspondent pas.", "alert-danger"],
        4 => ["Le nouveau mot de passe est identique à l'ancien.", "alert-danger"],
        5 => ["Le mot de passe doit faire minimum 12 caractères et doit contenir au minimum 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial.", "alert-danger"],
        6 => ["Code incorrect ou expiré !", "alert-danger"]
    ];

    // Récupérer le code d’erreur depuis l'URL
    $codeMdp = isset($_GET["erreurMdp"]) ? (int)$_GET["erreurMdp"] : 0;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Profil - Semonkey</title>
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/css_profil.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <link rel="icon" href="../img/monkeyapp.png">
        <?php include("../includes/head.php"); ?>
    </head>
    <body>
        <?php include("../includes/menu.php"); ?>
        <main class="glassmorphism">
            <h1 class="title">Mon Profil</h1>
            <div class="parent">
                <div class="photo-pseudo-buttons glassmorphism-section">
                    <div class="photo-pseudo">
                        <img src="../img/profil.webp" alt="Photo de Profil" title="Photo <?php echo $pseudo; ?>">
                        <h1 class="title-section h1"><?php echo $pseudo; ?></h1>
                    </div>
                    <div class="buttons">
                        <a id="historique" href="historique.php" class="btn btn-primary g-col-6 text-nowrap">Voir mon historique&emsp;<i class="fa-solid fa-clock-rotate-left"></i></a>
                        <button id="btn-email" type="button" class="btn btn-warning text-nowrap" data-bs-target="#emailModal" data-bs-toggle="modal">Changer l'email&emsp;<i class="fa-solid fa-envelope"></i></button>
                        <button id="btn-mdp" type="button" class="btn btn-warning text-nowrap" data-bs-target="#mdpModal" data-bs-toggle="modal">Changer le mot de passe&emsp;<i class="fa-solid fa-key"></i></button>
                        <button id="btn-photo" type="button" class="btn btn-warning text-nowrap" data-bs-target="#photoModal" data-bs-toggle="modal">Changer la photo de profil&emsp;<i class="fa-regular fa-pen-to-square"></i></button>
                    </div>
                </div>
                <div class="stats glassmorphism-section">
                    <h2 class="title-section h2">Mes Statistiques</h2>
                    <ul>
                        <li>
                            <ul>
                                <li>Score Minimum</li>
                                <li><button class="btn btn-dark"><?php echo $scoreResult->minS == null ? 0 : $scoreResult->minS; ?> <i class="fa-solid fa-arrow-down"></i></button></li>
                            </ul>
                        </li>
                        <li>
                            <ul>
                                <li>Score Maximum</li>
                                <li><button class="btn btn-dark"><?php echo $scoreResult->maxS == null ? 0 : $scoreResult->maxS; ?> <i class="fa-solid fa-arrow-up"></i></button></li>
                            </ul>
                        </li>
                        <li>
                            <ul>
                                <li>Score Moyen</li>
                                <li><button class="btn btn-dark"><?php echo round($scoreResult->avgS) == null ? 0 : round($scoreResult->avgS); ?> <i class="fa-solid fa-arrows-left-right"></i></button></li>
                            </ul>
                        </li>
                        <li>
                            <ul>
                                <li>Nombre de Parties</li>
                                <li><button class="btn btn-dark"><?php echo $scoreResult->nbParties == null ? "<span style='color: red'>Essayez de jouer !</span>" : $scoreResult->nbParties; ?> <i class="fa-solid fa-hashtag"></i></button></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="scores glassmorphism-section">
                    <h2 class="title-section">Meilleurs Scores</h2>
                    <div class="score-list">
                        <div id="first" class="btn gold">
                            <span class="pseudo"><?php echo $top3Result[0]->pseudo;?></span>
                            <span class="score"><?php echo $top3Result[0]->score;?></span>
                        </div>
                        <div id="second" class="btn silver">
                            <span class="pseudo"><?php echo $top3Result[1]->pseudo;?></span>
                            <span class="score"><?php echo $top3Result[1]->score;?></span>
                        </div>
                        <div id="third" class="btn bronze">
                            <span class="pseudo"><?php echo $top3Result[2]->pseudo;?></span>
                            <span class="score"><?php echo $top3Result[2]->score;?></span>
                        </div>
                        <?php
                        if (!in_array($pseudo, array($top3Result[0]->pseudo, $top3Result[1]->pseudo, $top3Result[2]->pseudo))) {
                            echo "<div id='myScore' class='btn btn-dark'>";
                        } else {
                            echo "<div id='myScore' class='btn " . ($top3Result[0]->pseudo == $pseudo ? "gold" : ($top3Result[1]->pseudo == $pseudo ? "silver" : "bronze")) . "'>";
                        }
                        echo "<span class='pseudo'>" . $pseudo . "</span>";
                        echo "<span class='score'>" . ($scoreResult->maxS == null ? 0 : $scoreResult->maxS) . "</span>";
                        echo "</div>";
                        ?>
                    </div>
                </div>
                <div class="mesinformations glassmorphism-section">
                    <h2 class="title-section">Mes Informations</h2>
                    <div>
                        <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Email : <?php echo $user->getEmail();?>">
                            Email : <?php echo $user->getEmail();?>
                        </button>
                        <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Année de Naissance : <?php echo $user->getYear();?>">
                            Année de Naissance : <?php echo $user->getYear();?>
                        </button>
                        <button id="tempsEcoule" type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Dernière Connexion : <?php echo makeDateTime($profilResult->lastConnexion);?>"></button>
                    </div>
                </div>
            </div>
        </main>
        <?php include("changes.php"); ?>
        <script>
            $(document).ready(function() {
                function updateElapsedTime() {
                    const now = Math.floor(new Date().getTime() / 1000); // Temps actuel en secondes
                    const lastConnexionTimestamp = <?php echo strtotime($profilResult->lastConnexion); ?>;
                    const elapsedSeconds = now - lastConnexionTimestamp;

                    const hours = Math.floor(elapsedSeconds / 3600);
                    const minutes = Math.floor((elapsedSeconds % 3600) / 60);
                    const seconds = elapsedSeconds % 60;

                    // Créez une chaîne de caractères pour afficher le temps écoulé
                    let elapsedTimeString = "";
                    if (hours > 0) {
                        elapsedTimeString += hours + "h ";
                    }
                    if (minutes > 0) {
                        elapsedTimeString += minutes + "m ";
                    }
                    elapsedTimeString += seconds + "s";
                    $("#tempsEcoule").text("Dernière connexion : " + elapsedTimeString);
                }

                // Mettez à jour le temps écoulé toutes les secondes
                setInterval(updateElapsedTime, 1000);

                // Appelez la fonction une fois au chargement de la page
                updateElapsedTime();
            });
        </script>
    </body>
</html>
