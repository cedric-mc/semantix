<?php
include_once("../class/User.php");
include_once("../includes/conf.php");
session_start();
// Erreur PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION["user"])) {
    header("Location: ./");
    exit();
}
$user = User::createUserFromUser(unserialize($_SESSION["user"]));
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["photo"])) {
    $image_data = file_get_contents($_FILES["photo"]["tmp_name"]);

    $stmt = $cnx->prepare("UPDATE sae_users SET photo = ? WHERE num_user = ?");
    $stmt->execute(array($image_data, $user->getIdUser()));
    $stmt->closeCursor();

    exit();
} else {
    header("Location: ./");
    exit();
}
?>