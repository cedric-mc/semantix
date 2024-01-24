<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style2.css">
    <link rel="icon" href="img/monkeyapp.png">
    <title>Leaderboards</title>
</head>
<body>
<style>
    body { text-align: center; }
    #menuL { margin-top: 20px; }
    .button { display: inline-block; margin: 5px; padding: 10px; background: #ddd; cursor: pointer; }
    .active { background: #bbb; }
    #leaderboard { margin-top: 20px; }
</style>
<?php
session_start();
include('include/menu.php');
include('include/connexion.php');
?>

<div id="menuL">
    <div id="solo" class="button active">Solo</div>
    <div id="multi" class="button">Multijoueur</div>
</div>

<div id="leaderboard">
    <!-- Le leaderboard sera chargé ici -->
</div>

<script>
    var soloButton = document.getElementById('solo');
    var multiButton = document.getElementById('multi');
    var leaderboardDiv = document.getElementById('leaderboard');

    function fetchLeaderboard(url) {
        fetch(url)
            .then(response => response.text())
            .then(data => {
                leaderboardDiv.innerHTML = data;
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données:', error);
            });
    }

    soloButton.onclick = function() {
        fetchLeaderboard('get_solo_leaderboard.php');
        soloButton.classList.add('active');
        multiButton.classList.remove('active');
    };

    multiButton.onclick = function() {
        fetchLeaderboard('get_multi_leaderboard.php');
        multiButton.classList.add('active');
        soloButton.classList.remove('active');
    };

    // Affichez par défaut le leaderboard solo
    fetchLeaderboard('get_solo_leaderboard.php');
</script>

</body>
</html>
