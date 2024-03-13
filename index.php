<?php
    include_once("class/User.php");
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: connexion/');
        exit;
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Semonkey</title>
	    <link href="style/style.css" rel="stylesheet">
        <link href="style/css_home.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="shortcut icon" href="./img/monkeyapp.png">
        <?php include("includes/head.php"); ?>
    </head>
    <body>
        <?php include("includes/menu.php"); ?>
        <main class="glassmorphism">
            <section class="home">
                <header>
                    <h1 class="title">Contact</h1>
                    <img src="img/monkeyapp.png" alt="Semantic Analogy Explorer">
                </header>
                <h2 class="subtitle">Équipe</h2>
                <p>Le projet Semonkey a été réalisé par une équipe de 5 étudiants en informatique de l'Université de Gustave Eiffel.</p>
            </section>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>