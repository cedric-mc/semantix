<?php
session_start(); // Démarre une nouvelle session ou reprend une session existante

// Vérifie si une certaine variable de session est définie et non nulle

if (!isset($_SESSION['pseudo'] && isset(($_SESSION['mdp']))) {
    
    // Si la session n'est pas reconnue, alors on est redirigé vers la page 'index.php'
    header("Location: index.php");
    exit(); 
}
?>

