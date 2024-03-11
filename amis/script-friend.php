<?php
    include_once("../class/User.php");
    include_once("../includes/conf.php");
    include_once("../includes/requetes.php");
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: ./');
        exit;
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    $idUser = $user->getIdUser();
    if (!isset($_GET['friendId'])) {
        header('Location: ./');
        exit;
    }
    $friendId = $_GET['friendId'];

    if (isset($_GET['accept'])) {
        // Requête SQL pour accepter une demande d'ami
        $acceptFriendRequest = $cnx->prepare($acceptFriend);
        $acceptFriendRequest->bindParam(":num_user", $friendId, PDO::PARAM_INT);
        $acceptFriendRequest->bindParam(":friend_id", $idUser, PDO::PARAM_INT);
        $acceptFriendRequest->execute();
        $acceptFriendRequest->closeCursor();

        // Script JS pour afficher une alerte
        echo "<script>alert('Demande d\'ami acceptée !')</script>";
        echo "<script>window.location.replace('./');</script>";
        exit();
    } elseif (isset($_GET['refuse'])) {
        // Requête SQL pour refuser une demande d'ami
        $refuseFriendRequest = $cnx->prepare($refuseFriend);
        $refuseFriendRequest->bindParam(":num_user", $friendId, PDO::PARAM_INT);
        $refuseFriendRequest->bindParam(":friend_id", $idUser, PDO::PARAM_INT);
        $refuseFriendRequest->execute();
        $refuseFriendRequest->closeCursor();

        // Script JS pour afficher une alerte
        echo "<script>alert('Demande d\'ami refusée !')</script>";
        echo "<script>window.location.replace('./');</script>";
        exit();
    } elseif (isset($_GET['add'])) {
        // Requête SQL pour ajouter un ami
        $addFriendRequest = $cnx->prepare($addFriend);
        $addFriendRequest->bindParam(":num_user", $idUser, PDO::PARAM_INT);
        $addFriendRequest->bindParam(":friend_id", $friendId, PDO::PARAM_INT);
        $addFriendRequest->execute();
        $addFriendRequest->closeCursor();

        // Script JS pour afficher une alerte
        echo "<script>alert('Demande d\'ami envoyée !')</script>";
        echo "<script>window.location.replace('./');</script>";
        exit();
    }
?>