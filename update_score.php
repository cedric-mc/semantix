<?php
session_start();
if (!isset($_SESSION['score'])) {
$_SESSION['score'] = 0;
}

$newNodeName = isset($_POST['newNodeName']) ? $_POST['newNodeName'] : '';

if ($newNodeName) {
$randomScore = rand(15, 50);
$_SESSION['score'] += $randomScore; // Ajoutez au score dans la session
}

echo $_SESSION['score']; // Renvoyez le score mis à jour
?>