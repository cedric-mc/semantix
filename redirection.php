<?php
session_start(); // Démarre une nouvelle session ou reprend une session existante

// Vérifie si certaines variables de session sont définies et non nulles
if (!isset($_SESSION['pseudo']) && !isset($_SESSION['mdp'])) {
    // Si une des sessions n'est pas reconnue, alors on est redirigé vers la page 'index.php'
    header("Location: index.php");
    exit();
}
?>