<link rel="stylesheet" href="style2.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<?php
include('menu.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('connexion.php');

if (isset($_POST['solo'])) {
    echo '<meta http-equiv="refresh" content="0;url=jeu_solo.php">';
    exit();
}

if (isset($_POST['multijoueur'])) {
    echo '<meta http-equiv="refresh" content="0;url=jeu_multijoueur.php">';
    exit();
}
?>

<main>
    <br><br>
    <form action="jeu.php" method="post">
        <div class="wrapper" style="text-align: center;">
            <button class="btn" type="submit" name="solo">Solo</button>
            <br><br>
            <button class="btn" type="submit" name="multijoueur">Multijoueur</button>
        </div>
    </form>
</main>
