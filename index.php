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
        <main class="glassmorphism acceuil-container">
            <section class="home">
                <header>
                    <h1 class="title">Semonkey</h1>
                    <img src="img/monkeyapp.png" alt="Semantic Analogy Explorer">
                </header>
                <h2 class="subtitle">Bienvenue, joueur <?php echo $user->getPseudo(); ?></h2>
                <button class="executeButton" onclick="window.location.href='game/start_game.php'">Solo&emsp;<i class="fa-solid fa-user"></i></button>
                <button class="executeButton disabled" disabled>Multijoueur&emsp;<i class="fa-solid fa-users"></i></button>
            </section>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>