<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('connexion.php');

if (isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])) {
    $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $id = $ligne->id;

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

        if ($nb === 'un') {
            echo "<h1>Partie lancée avec 1 joueur</h1>";
        } else {
            echo "<h1>Partie lancée en mode multijoueur</h1>";
        }

        $stmt = $dbh->prepare("INSERT INTO score_game (score, user_id) VALUES (:score, :id)");
        $stmt->bindParam(':score', $score);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo "<br> Votre score : " . $score;
    }

    $stmt = $dbh->prepare("SELECT COUNT(score) as nb FROM score_game WHERE user_id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $nb_partie = $ligne->nb;

    $stmt = $dbh->prepare("SELECT MAX(score) as max FROM score_game WHERE user_id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $max = $ligne->max;

    $stmt = $dbh->prepare("SELECT MIN(score) as min FROM score_game WHERE user_id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $min = $ligne->min;

    $stmt = $dbh->prepare("SELECT SUM(score) as sum FROM score_game WHERE user_id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $sum = $ligne->sum;

    $moyenne = ($nb_partie > 0) ? ($sum / $nb_partie) : 0;

    echo "<h2>Statistiques de Parties</h2>
        <table border='1'>
            <tr>
                <th>Moyenne</th>
                <th>Minimum</th>
                <th>Maximum</th>
                <th>Nombre de Parties</th>
            </tr>
            <tr>
                <td>" . $moyenne . "</td>
                <td>" . $min . "</td>
                <td>" . $max . "</td>
                <td>" . $nb_partie . "</td>
            </tr>
        </table>";
}
?>
<br>
<a href="deco.php"> Se déconnecter</a>
