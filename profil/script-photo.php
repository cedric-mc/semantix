<?php
include_once("../class/User.php");
include_once("../includes/conf.php");
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ./");
    exit();
}
$user = User::createUserFromUser(unserialize($_SESSION["user"]));
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $image_data = file_get_contents($_FILES["photo"]["tmp_name"]);

    $stmt = $cnx->prepare("UPDATE sae_users SET photo = ? WHERE num_user = ?");
    $stmt->execute(array($image_data, $user->getIdUser()));
    $stmt->closeCursor();
} else {
    header("Location: ./");
    exit();
}
?>