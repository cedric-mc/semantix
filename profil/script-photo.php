<?php
include_once("../class/User.php");
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ./");
    exit();
}
$user = User::createUserFromUser(unserialize($_SESSION["user"]));
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $image_data = file_get_contents($_FILES["photo"]["tmp_name"]);
} else {
    header("Location: ./");
    exit();
}
?>