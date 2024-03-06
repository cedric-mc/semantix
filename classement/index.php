<?php
include_once("../class/User.php");
include_once("../includes/conf.bkp.php");
include_once("../includes/fonctions.php");
include_once("../includes/requetes.php");
session_start();
// Erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['user'])) {
    header('Location: ../');
    exit;
}
$user = unserialize($_SESSION['user']);
$menu = 1;
// Requête pour récupérer le score des 10 premiers utilisateurs
$top10ScoresRequest = $cnx->prepare($top10Scores);
$top10ScoresRequest->execute();
$top10ScoresResult = $top10ScoresRequest->fetchAll(PDO::FETCH_OBJ);
$top10ScoresRequest->closeCursor();

// Requête pour récupérer le score des 10 premiers utilisateurs
$top10UsersRequest = $cnx->prepare($top10Users);
$top10UsersRequest->execute();
$top10UsersResult = $top10UsersRequest->fetchAll(PDO::FETCH_OBJ);
$top10UsersRequest->closeCursor();
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Classement - Semantic Analogy Explorer</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <?php include("../includes/head.php"); ?>
        <link rel="stylesheet" href="../style/style.css">
		<link rel="stylesheet" href="../style/table.css">
        <link rel="stylesheet" href="../style/css_classement.css">
	</head>

	<body>
        <?php include("../includes/menu.php"); ?>
		<main class="glassmorphism no-glassmorphism">
            <div id="menuL" class="menuL">
                <div id="solo" class="tab-button active">
                    <i class="fa-solid fa-star"></i> Meilleurs Parties
                </div>
                <div id="multi" class="tab-button">
                    <i class="fa-solid fa-ranking-star"></i> Meilleurs Joueurs Solo
                </div>
            </div>
            <div class="tab-indicator"></div>
            <div id="leaderboard">
                <div id="solo-tab" class="tab active">
                    <?php include("solo.php"); ?>
                </div>
                <div id="multi-tab" class="tab">
                    <?php include("multi.php"); ?>
                </div>
            </div>
		</main>
        <script>
            var soloButton = document.getElementById('solo');
            var multiButton = document.getElementById('multi');
            var soloTab = document.getElementById('solo-tab');
            var multiTab = document.getElementById('multi-tab');

            soloButton.addEventListener('click', function() {
                soloButton.classList.add("active");
                multiButton.classList.remove("active");
                soloTab.classList.add("active");
                multiTab.classList.remove("active");
            });

            multiButton.addEventListener('click', function() {
                soloButton.classList.remove("active");
                multiButton.classList.add("active");
                soloTab.classList.remove("active");
                multiTab.classList.add("active");
            });
        </script>
	</body>
</html>
