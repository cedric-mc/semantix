<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('menu.php');

include('connexion.php');?>

<link rel="stylesheet" href="style2.css">
<div class="box">
    <h1> Mon compte </h1>
    <br>
    <br>
    <h2> Mes Informations </h2>
    <br>
        Pseudo : <?php echo htmlspecialchars($_SESSION['pseudo']); ?>
    <br>
    <br>
    <?php
    $stmt = $dbh->prepare("SELECT email FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $mail = $ligne->email;
    ?>
        Email : <?php echo $mail;?>
    <br>
    <br>
    <?php
    $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $id = $ligne->id;

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
    <table align='center'>
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
    </table>";?>

    <br>
    <br>
    <h2> Historique des parties : </h2>
    <table>
        <tr>
            <th> Score </th>
            <th> Date </th>
        </tr>
        <tbody>
    <?php
    $stmt = $dbh->prepare("SELECT * FROM score_game WHERE user_id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    while ($ligne = $stmt->fetch(PDO::FETCH_OBJ)) {
        echo "<tr>";
        echo "<td> $ligne->score</td>";
        echo "<td>$ligne->date </td>";
        echo "</tr>";

    }
    ?>
        </tbody>
    </table>
</div>
