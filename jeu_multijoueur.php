<link rel="stylesheet" href="style2.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<?php
include('menu.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('connexion.php');
?>

<main>
    <br><br>
    <form action="jeu.php" method="post">
        <div class="wrapper" style="text-align: center;">
            <h2> Le mode multijoueur n'est pas encore implémenté revenez plus tard !</h2>
            <div class="register-link">
                <a href="jeu.php"> Retour </a>
            </div>
        </div>
    </form>
</main>

<script>
    setTimeout(function() {
        window.location.href = 'jeu.php'; // Redirige vers jeu.php après 5 secondes
    }, 5000); // 5000 millisecondes équivalent à 5 secondes
</script>