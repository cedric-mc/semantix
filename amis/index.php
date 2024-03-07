<?php
    include_once("class/User.php");
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: ./');
        exit;
    }
    $user = unserialize($_SESSION['user']);
    $menu = 1;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Amis - Semonkey</title>
        <meta name="description" content="Venez Jouez à Semantic Analogy Explorer (SAE), un jeu en ligne à un ou plusieurs joueurs basé sur les similarités entre mots : « Semantic Analogy Explorer ». Chaque joueur reçoit un mot de départ et un mot cible et propose des mots proches afin de créer une chaîne de mots similaires pour relier le mot de départ au mot cible. ">
        <meta name="keywords" content="Semantic Analogy Explorer, SAE, jeu, jeu en ligne, jeu de mots, jeu de lettres, jeu de lettres en ligne, jeu de mots en ligne, jeu de lettres multijoueur, jeu de mots multijoueur, jeu de lettres multijoueur en ligne, jeu de mots multijoueur en ligne, jeu de lettres multijoueur gratuit, jeu de mots multijoueur gratuit, jeu de lettres multijoueur gratuit en ligne, jeu de mots multijoueur gratuit en ligne, jeu de lettres multijoueur gratuit sans inscription, jeu de mots multijoueur gratuit sans inscription, jeu de lettres multijoueur gratuit en ligne sans inscription, jeu de mots multijoueur gratuit en ligne sans inscription, jeu de lettres multijoueur gratuit en ligne sans inscription et sans téléchargement, jeu de mots multijoueur gratuit en ligne sans inscription et sans téléchargement, jeu de lettres multijoueur gratuit en ligne sans inscription et sans téléchargement, jeu de mots multijoueur gratuit en ligne sans inscription et sans téléchargement">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/3f3ecfc27b.js"></script>
        <link rel="shortcut icon" href="../img/monkeyapp.png">
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/css_friends.css">
    </head>

    <body>
        <?php include("../includes/menu.php"); ?>
        <main class="glassmorphism friends">
            <h1 class="title">
                Amis</h1>
            <br>
            <!-- Afficher la liste des amis (avec le statut : en attente, accepté, refusé ; statut : en ligne, hors ligne) -->
            <!-- Je propose d'afficher la liste avec des carrés pour chaque ami avec le nom, le statut (en ligne, hors ligne) et le bouton pour supprimer l'ami, un utilisateur = un carré -->

            <h2>Ajouter un Ami</h2>
            <!-- Afficher la liste des utilisateurs pour ajouter un ami (sauf soi-même et les amis déjà ajoutés) -->
        </main>
        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>