<?php
    include_once("../class/User.php");
    include_once("../includes/conf.php");
    include_once("../includes/requetes.php");
    // Erreur PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: ./');
        exit;
    }
    $user = unserialize($_SESSION['user']);
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    $idUser = $user->getIdUser();

    // Requête SQL pour obtenir la liste des amis à ajouter
    $listUsersRequest = $cnx->prepare($listUsers);
    $listUsersRequest->bindParam(":num_user", $idUser, PDO::PARAM_INT);
    $listUsersRequest->bindParam(":idUser", $idUser, PDO::PARAM_INT);
    $listUsersRequest->execute();
    $listUsersResult = $listUsersRequest->fetchAll(PDO::FETCH_OBJ);
    $listUsersRequest->closeCursor();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Amis - Semonkey</title>
        <link rel="shortcut icon" href="../img/monkeyapp.png">
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/css_amis.css">
        <?php include("../includes/head.php"); ?>
        <style>
            .friends h2 {
                text-align: center;
            }

            .recherche {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-top: 1.25rem;
                margin-bottom: 1.25rem;
            }

            .recherche input {
                width: 100%;
                height: auto;
                border: none;
                border-radius: 0.625rem;
                padding: 0.313rem 0.625rem;
                margin-right: 0.625rem;
            }

            .recherche button {
                width: auto;
                height: auto;
                border: none;
                border-radius: 5px;
                background-color: #4CAF50;
                color: white;
                cursor: pointer;
                padding: 0.313rem 0.625rem;
            }

            .users {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            .user {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                margin: 0.625rem;
                padding: 0.625rem;
                border: 1px solid #4CAF50;
                border-radius: 5px;
                width: 10rem;
                height: 10rem;
            }

            .user img {
                width: 6.25rem;
                height: auto;
                border-radius: 50%;
                margin-bottom: 0.625rem;
            }

            .user p {
                margin-bottom: 0.625rem;
            }

            .user button {
                width: 100px;
                height: 30px;
                border: none;
                border-radius: 5px;
                background-color: #4CAF50;
                color: white;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <?php $menu = 1; include("../includes/menu.php"); ?>
        <main class="glassmorphism friends">
            <h1 class="title">Amis</h1>
            <br>
            <h2 class="select">Liste des Amis</h2>
            <!-- Afficher la liste des amis (avec le statut : en attente, accepté, refusé ; statut : en ligne, hors ligne) -->
            <!-- Je propose d'afficher la liste avec des carrés pour chaque ami avec le nom, le statut (en ligne, hors ligne) et le bouton pour supprimer l'ami, un utilisateur = un carré
            Lorsque l'on clique sur un carré, on affiche un modal avec les informations de l'ami (pseudo, date de naissance, statut, date de dernière connexion, (discuté avec Fressin pour l'email) -->
            <div class="container">
                <div class="recherche">
                    <input type="text" id="search" placeholder="Rechercher un ami">
                    <button id="searchButton">Rechercher</button>
                </div>
                <div class="users row row-cols-auto">
                    <div class="user">
                        <img src="../img/profil.webp" alt="Photo de profil">
                        <p>Nom de l'ami</p>
                        <p>Statut : En ligne</p>
                        <button>Supprimer</button>
                    </div>
                    <div class="user">
                        <img src="../img/profil.webp" alt="Photo de profil">
                        <p>Nom de l'ami</p>
                        <p>Statut : Hors ligne</p>
                        <button>Supprimer</button>
                    </div>
                </div>
            </div>
            <br>
            <h2 class="add">Ajouter un Ami</h2>
            <!-- Afficher la liste des utilisateurs pour ajouter un ami (sauf soi-même et les amis déjà ajoutés) -->
            <!-- Je propose d'afficher la liste avec des carrés pour chaque utilisateur avec le nom et le bouton pour ajouter l'ami, un utilisateur = un carré -->
            <div class="container">
                <div class="recherche">
                    <input type="text" id="search" placeholder="Rechercher un ami">
                    <button id="searchButton">Rechercher</button>
                </div>
                <div class="users row row-cols-auto">
                    <!--<div class="user">
                        <img src="../img/profil.webp" alt="Photo de profil">
                        <p>Nom de l'utilisateur</p>
                        <button>Ajouter</button>
                    </div>
                    <div class="user">
                        <img src="../img/profil.webp" alt="Photo de profil">
                        <p>Nom de l'utilisateur</p>
                        <button>Ajouter</button>
                    </div>-->
                    <?php
                        foreach ($listUsersResult as $ligne) {
                            echo "<div class='user'>";
                            echo "<img src='../img/profil.webp' alt='Photo de profil'>";
                            echo "<p>$ligne->pseudo</p>";
                            echo "<button>Ajouter</button>";
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>