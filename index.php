<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header('Location: connexion/');
    exit;
}
$pseudo = $_SESSION['pseudo'];
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Home - Semantic Analogy Explorer</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <link href="css/style.css" rel="stylesheet">
        <link href="css/css_home.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <?php include("includes/head.php"); ?>
    </head>
    <body class="">
        <?php
            include("includes/menu.php");
        ?>
        <figure class="text-end">
            <a class="btn btn-light" href="classement.php">Classement&emsp;<i class="fa-solid fa-trophy"></i></a>
            <a class='btn btn-light' href="traces.php">Traces&emsp;<i class="fa-solid fa-clipboard-list"></i></a>
            <a class="btn btn-light" href="profil/">Mon Profil&emsp;<i class="fa-solid fa-user"></i></a>
        </figure>
        <main class="position-absolute top-50 start-50 translate-middle glassmorphism">
            <h1 class="title">Semantic Analogy Explorer</h1>
            <?php echo "<h2 class='subtitle'>Bienvenue, joueur $pseudo</h2>"; ?>
            <form method="post" action="game/start_game.php">
                <h3 class="subtitle2"><i class="fa-solid fa-gamepad"></i>&emsp;Choisissez un mode de jeu&emsp;<i class="fa-solid fa-dice"></i></h3>
                <button id="executeButton" class="btn btn-primary btn-lg" type="submit">Solo&emsp;<i class="fa-solid fa-user"></i></button>
                <button id="executeButton" class="btn btn-primary btn-lg disabled" type="reset">Multijoueur&emsp;<i class="fa-solid fa-users"></i></button>
            </form>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>