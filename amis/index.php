<?php
    include_once("../class/User.php");
    include_once("../includes/conf.php");
    include_once("../includes/requetes.php");
    include_once("../includes/fonctions.php");
    // Erreur PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: ../");
        exit;
    }
    $user = unserialize($_SESSION['user']);
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    $idUser = $user->getIdUser();

    // Requête SQL pour obtenr la liste des amis
    $myFriendsRequest = $cnx->prepare($allFriends);
    $myFriendsRequest->bindParam(":num_user", $idUser, PDO::PARAM_INT);
    $myFriendsRequest->execute();
    $myFriendsResult = $myFriendsRequest->fetchAll(PDO::FETCH_OBJ);
    $myFriendsRequest->closeCursor();

    // Requête SQL pour obtenir la liste des amis à ajouter (file d'attente)
    $canAddFriendRequest = $cnx->prepare($canAddFriend);
    $canAddFriendRequest->bindParam(":num_user", $idUser, PDO::PARAM_INT);
    $canAddFriendRequest->execute();
    $canAddFriendResult = $canAddFriendRequest->fetchAll(PDO::FETCH_OBJ);
    $canAddFriendRequest->closeCursor();

    // Requête SQL pour obtenir la liste des pseudos qui peuvent être ajoutés en amis
    $wantToAddFriendsRequest = $cnx->prepare($wantToAddFriends);
    $wantToAddFriendsRequest->bindParam(":num_user", $idUser, PDO::PARAM_INT);
    $wantToAddFriendsRequest->bindParam(":idUser", $idUser, PDO::PARAM_INT);
    $wantToAddFriendsRequest->execute();
    $wantToAddFriendsResult = $wantToAddFriendsRequest->fetchAll(PDO::FETCH_OBJ);
    $wantToAddFriendsRequest->closeCursor();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Amis - Semonkey</title>
        <link rel="shortcut icon" href="../img/monkeyapp.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/css_amis.css">
        <?php include("../includes/head.php"); ?>
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
                    <?php $nbAmis = count($myFriendsResult); foreach ($myFriendsResult as $ligne) { ?>
                        <div class="user">
                            <img src="<?php echo getProfilePicture($ligne->photo); ?>" alt="Photo de profil">
                            <p><?php echo $ligne->pseudo; ?></p>
                            <p>Statut : <?php echo friendStatus($ligne->statut) ?></p>
                            <?php if ($ligne->statut == 0) { ?>
                                <a class="btn btn-warning" href="script-friend.php?accept&friendId=<?php echo $ligne->num_user; ?>" role="button">Accepter&emsp;<i class="fa-solid fa-check"></i></a>
                                <a class="btn btn-danger" href="script-friend.php?refuse&friendId=<?php echo $ligne->num_user; ?>" role="button">Refuser&emsp;<i class="fa-solid fa-xmark"></i></a>
                            <?php } else { ?>
                                <a class="btn btn-danger" href="script-friend.php?delete&friendId=<?php echo $ligne->num_user; ?>" role="button">Supprimer&emsp;<i class="fa-solid fa-trash"></i></a>
                            <?php } ?>
                        </div>
                    <?php } if ($nbAmis == 0) { ?>
                        <div class="user text-center">
                            <p>Aucun ami pour le moment, tu es seul(e) au monde !</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <br>
            <h2 class="add">Ajouter un Ami</h2>
            <!-- Afficher la liste des utilisateurs pour ajouter un ami (sauf soi-même et les amis déjà ajoutés) -->
            <!-- Je propose d'afficher la liste avec des carrés pour chaque utilisateur avec le nom et le bouton pour ajouter l'ami, un utilisateur = un carré -->
            <div class="container">
                <!--<div class="recherche">
                    <input type="text" id="search" name="search" placeholder="Rechercher un ami">
                    <label for="search">Choisir un ami</label>
                    <button id="addFriendButton">Ajouter</button>
                </div>-->
                <div class="users row row-cols-auto">
                    <?php $nbCanAdd = count($wantToAddFriendsResult); foreach ($wantToAddFriendsResult as $ligne) { ?>
                        <div class="user">
                            <img src="<?php echo getProfilePicture($ligne->photo); ?>" alt="Photo de profil">
                            <p><?php echo $ligne->pseudo; ?></p>
                            <a class="btn btn-success" href="script-friend.php?add&friendId=<?php echo $ligne->num_user; ?>" role="button">Ajouter</a>
                        </div>
                    <?php } if ($nbCanAdd == 0) { ?>
                        <div class="user text-center">
                            <p>Tu as déjà ajouté tous les utilisateurs !</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>