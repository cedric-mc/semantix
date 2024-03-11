<?php
if (!isset($_GET['idUser'])) {
    header('Location: ./');
    exit;
}
$idUser = $_GET['idUser'];
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

// Requête SQL pour ajouter un ami
$addFriendRequest = $cnx->prepare($addFriend);
$addFriendRequest->bindParam(":num_user", $idUser, PDO::PARAM_INT);
$addFriendRequest->bindParam(":friend_id", $idUser, PDO::PARAM_INT);
$addFriendRequest->execute();
$addFriendRequest->closeCursor();
?>