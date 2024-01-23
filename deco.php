<?php
session_start();
include('include/connexion.php');
$stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
$stmt->bindParam(':pseudo', $_SESSION['pseudo']);
$stmt->execute();
$ligne = $stmt->fetch(PDO::FETCH_OBJ);
$id = $ligne->id;

// TRACE
$action = "Deconnexion";
$ip = $_SERVER['REMOTE_ADDR'];
date_default_timezone_set('Europe/Paris');
$date = date('y-m-d H:i:s');
$stmt = $dbh->prepare("INSERT INTO trace (action, ip, date, user_id) VALUES (:action, :ip, :date, :id)");
$stmt->bindParam(':action', $action);
$stmt->bindParam(':ip', $ip);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':id', $id);
$stmt->execute();
header('Location: acceuil.php');
session_unset();
session_destroy();
header ('location: index.php'); 

?>