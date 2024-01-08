<head><title> Acceuil </title></head>
<link href='menu.css' rel='stylesheet'>
<link href='style2.css' rel='stylesheet'>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('connexion.php');
include('menu.php');
if (isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])) {
    $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $id = $ligne->id;
    echo "<main> <body>";
    echo "Bonjour " . htmlspecialchars($_SESSION['pseudo']);
    echo '<br><br><form action="acceuil.php" method="post">
    <li>
    <label for="un">1 Joueur</label>
    <input type="radio" id="un" name="nb" value="un" required>
    </li>
    <li>
    <label for="multi">Multijoueur</label>
    <input type="radio" id="multi" name="nb" value="multi" required>
    </li>
    <input type="submit" value="Lancer la Partie">
    </form>';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $score = random_int(50, 100);
        $nb = isset($_POST['nb']) ? $_POST['nb'] : '';
        date_default_timezone_set('Europe/Paris');
        $date = date('y-m-d');

        if ($nb === 'un') {
            echo "<h1>Partie lancée avec 1 joueur</h1>";
        } else {
            echo "<h1>Partie lancée en mode multijoueur</h1>";
        }

        $stmt = $dbh->prepare("INSERT INTO score_game (score, user_id, date) VALUES (:score, :id, :date)");
        $stmt->bindParam(':score', $score);
        $stmt->bindParam(':id', $id);  // Supposons que $id contient l'ID de l'utilisateur
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        echo "<br> Votre score : " . $score;
    }



}
?>
<br>
<a href="deco.php"> Se déconnecter</a>
</main>
</body>
