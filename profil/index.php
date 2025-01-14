<?php
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
$scoreRequest->bindParam(':pseudo', $pseudo);
$scoreRequest->execute();
$scoreResult = $scoreRequest->fetch(PDO::FETCH_OBJ);
$scoreRequest->closeCursor();

// Requête pour récupérer le top 3 des meilleurs scores (max), le pseudo n'apparaît qu'une seule fois
$top3Request = $cnx->prepare($top3ScoresProfil);
$top3Request->execute();
$top3Result = $top3Request->fetchAll(PDO::FETCH_OBJ);
$top3Request->closeCursor();

$photoRequest = $cnx->prepare("SELECT photo FROM sae_users WHERE pseudo = :pseudo");
$photoRequest->bindParam(":pseudo", $pseudo);
$photoRequest->execute();
$photoResult = $photoRequest->fetch(PDO::FETCH_OBJ);
$photoRequest->closeCursor();

// Messages d’erreurs possibles pour le changement d'email
$erreursEmail = [
    1 => ["L'adresse e-mail a bien été modifiée.", "alert-success"],
    2 => ["L'ancienne adresse e-mail est incorrecte.", "alert-danger"],
    3 => ["La nouvelle adresse e-mail est incorrecte.", "alert-danger"],
    4 => ["Les deux adresses e-mail sont identiques.", "alert-danger"],
    5 => ["La nouvelle adresse e-mail est déjà utilisée par un autre utilisateur.", "alert-danger"]
];

// Messages d'erreurs possibles pour le changement de mot de passe
$erreursMdp = [
    1 => ["Le code de confirmation est correct. Mot de passe modifié avec succès.", "alert-success"],
    2 => ["L'ancien mot de passe est incorrect.", "alert-danger"],
    3 => ["Les nouveaux mots de passe ne correspondent pas.", "alert-danger"],
    4 => ["Le nouveau mot de passe est identique à l'ancien.", "alert-danger"],
    5 => ["Le mot de passe doit faire minimum 12 caractères et doit contenir au minimum 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial.", "alert-danger"],
    6 => ["Code incorrect ou expiré !", "alert-danger"]
];

// Messages d'erreurs possibles pour le changement de photo de profil
$erreursPhoto = [
    1 => ["La photo de profil a bien été modifiée.", "alert-success"],
    2 => ["Le fichier n'est pas une image.", "alert-danger"],
    3 => ["Le fichier est trop volumineux.", "alert-danger"],
    4 => ["Erreur lors de l'envoi du fichier.", "alert-danger"]
];

// Récupérer les codes d'erreur depuis l'URL
$codeEmail = isset($_GET['emailErreur']) ? (int)$_GET['emailErreur'] : 0;
$codeMdp = isset($_GET["erreurMdp"]) ? (int)$_GET["erreurMdp"] : 0;
$codePhoto = isset($_GET["erreurPhoto"]) ? (int)$_GET["erreurPhoto"] : 0;

$historicRequest = $cnx->prepare("
    SELECT score, dateHeure
    FROM sae_users u, sae_scores s
    WHERE pseudo = :pseudo AND u.num_user = s.num_user
    ORDER BY dateHeure DESC;");
$historicRequest->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
$historicRequest->execute();
$historicResult = $historicRequest->fetchAll(PDO::FETCH_OBJ);
$historicRequest->closeCursor();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Profil - Semonkey</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/css_profil.css">
    <link rel="stylesheet" href="../style/table.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link rel="icon" href="../img/monkey.png">
    <?php include("../includes/head.php"); ?>
</head>

<body class="overflow">
    <?php $menu = 1;
    include("../includes/menu.php"); ?>
    <main class="glassmorphism profil-section">
        <h1 class="title">Mon Profil</h1>
        <div class="parent">
            <div class="photo-pseudo-buttons glassmorphism-section">
                <div class="photo-pseudo">
                    <img src="<?php echo $user->getImageSrc(); ?>" alt="Photo de profil" class="photo-profil">
                    <h1 class="title-section h1"><?php echo $pseudo; ?></h1>
                </div>
                <div class="buttons">
                    <button id="btn-historique" type="button" class="btn btn-primary g-col-6 " data-bs-target="#historiqueModal" data-bs-toggle="modal">Voir mon historique&emsp;<i class="fa-solid fa-clock-rotate-left"></i></button>
                    <button id="btn-email" type="button" class="btn btn-warning " data-bs-target="#emailModal" data-bs-toggle="modal">Changer l'email&emsp;<i class="fa-solid fa-envelope"></i></button>
                    <button id="btn-mdp" type="button" class="btn btn-warning " data-bs-target="#mdpModal" data-bs-toggle="modal">Changer le mot de passe&emsp;<i class="fa-solid fa-key"></i></button>
                    <button id="btn-photo" type="button" class="btn btn-warning " data-bs-target="#photoModal" data-bs-toggle="modal">Changer la photo de profil&emsp;<i class="fa-regular fa-pen-to-square"></i></button>
                </div>
            </div>
            <div class="parent2">
                <div class="stats glassmorphism-section">
                    <h2 class="title-section h2">Mes Statistiques</h2>
                    <ul class="score-responsive">
                        <li>
                            <ul>
                                <li>Score Minimum</li>
                                <li><button class="btn btn-warning"><?php echo $scoreResult->minS == null ? 0 : $scoreResult->minS; ?> <i class="fa-solid fa-arrow-down"></i></button></li>
                            </ul>
                        </li>
                        <li>
                            <ul>
                                <li>Score Maximum</li>
                                <li><button class="btn btn-warning"><?php echo $scoreResult->maxS == null ? 0 : $scoreResult->maxS; ?> <i class="fa-solid fa-arrow-up"></i></button></li>
                            </ul>
                        </li>
                        <li>
                            <ul>
                                <li>Score Moyen</li>
                                <li><button class="btn btn-warning"><?php echo round($scoreResult->avgS) == null ? 0 : round($scoreResult->avgS); ?> <i class="fa-solid fa-arrows-left-right"></i></button></li>
                            </ul>
                        </li>
                        <li>
                            <ul>
                                <li>Nombre de Parties</li>
                                <li><button class="btn btn-warning"><?php echo $scoreResult->nbParties == null ? "<span style='color: red'>Essayez de jouer !</span>" : $scoreResult->nbParties; ?> <i class="fa-solid fa-hashtag"></i></button></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="scores glassmorphism-section">
                    <h2 class="title-section">Meilleurs Scores</h2>
                    <div class="score-list">
                        <div id="first" class="btn gold">
                            <span class="pseudo"><?php echo $top3Result[0]->pseudo; ?></span>
                            <span class="score"><?php echo $top3Result[0]->score; ?></span>
                        </div>
                        <div id="second" class="btn silver">
                            <span class="pseudo"><?php echo $top3Result[1]->pseudo; ?></span>
                            <span class="score"><?php echo $top3Result[1]->score; ?></span>
                        </div>
                        <div id="third" class="btn bronze">
                            <span class="pseudo"><?php echo $top3Result[2]->pseudo; ?></span>
                            <span class="score"><?php echo $top3Result[2]->score; ?></span>
                        </div>
                        <?php if (!in_array($pseudo, array($top3Result[0]->pseudo, $top3Result[1]->pseudo, $top3Result[2]->pseudo))) { ?>
                            <div id='myScore' class='btn btn-dark'>
                        <?php } else { ?>
                            <div id="myScore" class="btn <?php echo ($top3Result[0]->pseudo == $pseudo ? "gold" : ($top3Result[1]->pseudo == $pseudo ? "silver" : "bronze")) ?>">
                        <?php } ?>
                        <span class='pseudo'><?php echo $pseudo; ?></span>
                        <span class='score'><?php echo ($scoreResult->maxS == null ? 0 : $scoreResult->maxS); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mesinformations glassmorphism-section">
                <h2 class="title-section">Mes Informations</h2>
                <div>
                    <button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Id de joueur : <?php echo $user->getIdUser(); ?>">
                        ID de joueur : <?php echo $user->getIdUser(); ?>
                    </button>
                    <button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Email : <?php echo $user->getEmail(); ?>">
                        Email : <?php echo $user->getEmail(); ?>
                    </button>
                    <button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Année de Naissance : <?php echo $user->getYear(); ?>">
                        Année de Naissance : <?php echo $user->getYear(); ?>
                    </button>
                    <button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Pseudo : <?php echo $user->getPseudo(); ?>">
                        Pseudo : <?php echo $user->getPseudo(); ?>
                    </button>
                </div>
            </div>
        </div>
    </main>
    <?php include("modals.php"); ?>
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

            // Vérifiez si le paramètre 'emailErreur' existe dans l'URL
            const urlParams = new URLSearchParams(window.location.pseudo);
            if (urlParams.has('emailErreur')) {
                $('#emailModal').modal('show'); // Ouverture de la modal email
            }

            // Vérifiez si le paramètre 'erreurMdp' existe dans l'URL
            if (urlParams.has('erreurMdp')) {
                $('#mdpModal').modal('show'); // Ouverture de la modal mot de passe
            }

            // Vérifiez si le paramètre 'erreurPhoto' existe dans l'URL
            if (urlParams.has('erreurPhoto')) {
                $('#photoModal').modal('show'); // Ouverture de la modal photo
            }
        });

        // Faire disparaître le message d'erreur après 10 secondes
        setTimeout(function() {
            $('#msgEmail').fadeOut('slow');
            $('#msgMdp').fadeOut('slow');
            $('#msgPhoto').fadeOut('slow');

            // Après les 10 secondes, on actualise la page pour supprimer le code d'erreur de l'URL et le message d'erreur mais on ouvre la modal correspondante
            setTimeout(function() {
                // Supprimer la variable GET de l'URL
                let url = window.location.href.split('?')[0];
                history.replaceState(null, null, url);

                // Récupérer le modal qui était ouvert avant le rechargement de la page
                let modal = localStorage.getItem('modal');

                // Réafficher le modal
                if (modal === 'email') {
                    $('#emailModal').modal('show');
                } else if (modal === 'mdp') {
                    $('#mdpModal').modal('show');
                } else if (modal === 'photo') {
                    $('#photoModal').modal('show');
                }

                // Supprimer le modal du localStorage
                localStorage.removeItem('modal');
            }, 1000);
        }, 10000);
    </script>
</body>

</html>