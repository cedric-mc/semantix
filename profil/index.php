<?php
    // Erreur php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    include_once("../class/User.php");
    include("../includes/conf.bkp.php");
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
    $profilRequest->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
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
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/css_profil.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <link rel="icon" href="../img/monkeyapp.png">
        <?php include("../includes/head.php"); ?>
        <style>
            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                padding-top: 100px;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0,0,0);
                background-color: rgba(0,0,0,0.4);
            }

            .modal-content {
                transition: opacity 0.5s ease;
                opacity: 0; /* Par défaut, le modal est transparent */
            }

            .modal.show .modal-content {
                opacity: 1; /* Quand le modal est affiché, il devient opaque */
            }

            .modal-content {
                background-color: #fefefe;
                margin: auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
            }

            .close {
                color: #aaaaaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: #000;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
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
                        <!-- <button id="btn-email" type="button" class="btn btn-warning text-nowrap">Changer l'email&emsp;<i class="fa-solid fa-envelope"></i></button> -->
                        <button id="btn-mdp" type="button" class="btn btn-warning text-nowrap">Changer le mot de passe&emsp;<i class="fa-solid fa-key"></i></button>
                        <!-- <button id="btn-photo" type="button" class="btn btn-warning text-nowrap disabled">Changer la photo de profil&emsp;<i class="fa-regular fa-pen-to-square"></i></button> -->
                        <button id="disconnect-btn" class="btn btn-danger g-col-6 text-nowrap">Se déconnecter&emsp;<i class="fa-solid fa-right-from-bracket"></i></button>
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
        <div id="modal-mdp" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form action="script-password.php" method="POST">
                    <div class="input-field">
                        <input type="password" id="password1" name="password1" required>
                        <label for="password1">Ancien mot de passe :</label>
                    </div>
                    <div class="input-field">
                        <input type="password" id="password2" name="password2" required>
                        <label for="password2">Nouveau mot de passe :</label>
                    </div>
                    <div class="input-field">
                        <input type="password" id="password3" name="password3" required>
                        <label for="password3">Confirmer le nouveau mot de passe :</label>
                    </div>
                    <button id="formButton" type="submit" class="btn fw-semibold">Valider</button>
                </form>
            </div>
        </div>
        <!-- Bouton pour déclencher le modal de la photo de profil -->
        <button id="btn-photo">Changer la photo de profil</button>

        <!-- Modal pour la photo de profil -->
        <div id="modal-photo" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Contenu de votre modal pour la photo de profil...</p>
            </div>
        </div>

        <!-- Bouton pour déclencher le modal du changement d'email -->
        <button id="btn-email">Changer l'email</button>

        <!-- Modal pour le changement d'email -->
        <div id="modal-email" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Contenu de votre modal pour le changement d'email...</p>
            </div>
        </div>
        <script>
            var modalMdp = document.getElementById("modal-mdp");
            var btnMdp = document.getElementById("btn-mdp");
            var modalPhoto = document.getElementById("modal-photo");
            var modalEmail = document.getElementById("modal-email");
            var btnPhoto = document.getElementById("btn-photo");
            var btnEmail = document.getElementById("btn-email");
            var spans = document.getElementsByClassName("close");

            btnMdp.onclick = function() {
                modalMdp.style.display = "block";
                modalMdp.classList.add('show'); // Ajoutez la classe 'show'
            }

            btnPhoto.onclick = function() {
                modalPhoto.style.display = "block";
                modalPhoto.classList.add('show'); // Ajoutez la classe 'show'
            }

            btnEmail.onclick = function() {
                modalEmail.style.display = "block";
                modalEmail.classList.add('show'); // Ajoutez la classe 'show'
            }

            for (var i = 0; i < spans.length; i++) {
                spans[i].onclick = function() {
                    this.parentElement.parentElement.style.display = "none";
                    this.parentElement.parentElement.classList.remove('show'); // Retirez la classe 'show'
                }
            }

            window.onclick = function(event) {
                if (event.target == modalMdp) {
                    modalMdp.style.display = "none";
                } else if (event.target == modalPhoto) {
                    modalPhoto.style.display = "none";
                } else if (event.target == modalEmail) {
                    modalEmail.style.display = "none";
                }
            }

            document.getElementById('disconnect-btn').addEventListener('click', function() {
                if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                    window.location.href = '../connexion/script-logout.php'; // Redirigez vers la page de déconnexion
                }
            });

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
