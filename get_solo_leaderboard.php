<link rel="stylesheet" href="leaderboard.css">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('connexion.php');

?>

<div class="leaderboard">
    <header>
        <h1>Leader Board</h1>
        <img src="" alt="">
        <nav class="ldb">
            <a href="" class="active">Solo</a>
        </nav>
    </header>
    <table>
        <thead>
        <tr>
            <th class="rank">Place</th>
            <th class="nick">Pseudo</th>
            <th class="sp">Score</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <?php
            $place = 1;
            echo "<tr>";
            $stmt = $dbh->prepare("SELECT pseudo, score FROM user u, score_game s WHERE s.user_id = u.id ORDER BY score desc LIMIT 5");
            $stmt->execute();
            while ($ligne = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($place) . "</td>";
                echo "<td>" . htmlspecialchars($ligne['pseudo']) . "</td>";
                echo "<td>" . htmlspecialchars($ligne['score']) . "</td>";
                echo "</tr>";
                $place++;
            }
            echo "</tr>";
            ?>
        </tr>

        </tbody>
    </table>
</div>