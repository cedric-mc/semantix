<div class="classement">
    <header class="header">
        <h1 class="title">Meillleurs<br>Scores</h1>
        <img src="../img/monkey.png" alt="Image de Semonkey">
    </header>
    <div class="classement-container">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr class="text-center">
                        <th>Position</th>
                        <th>Pseudo</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $top10Pseudos = array();
                        $position = 1;
                        foreach ($top10ScoresResult as $ligne) {
                            echo "<tr>";
                            echo "<td class='text-center'>$position</td>";
                            echo "<td class='text-center'>$ligne->pseudo</td>";
                            echo "<td class='text-center'>$ligne->score</td>";
                            echo "</tr>";
                            $position++;
                            $top10Pseudos[] = $ligne->pseudo;
                        }
                        // Si le joueur n'est pas dans le top 10, on affiche son score
                        if (!in_array($user->getPseudo(), $top10Pseudos)) {
                            $scoreRequest = $cnx->prepare("SELECT MAX(score) AS maxS FROM sae_scores s, sae_users u WHERE u.num_user = s.num_user AND u.pseudo = :pseudo");
                            $scoreRequest->bindParam(':pseudo', $pseudo);
                            $scoreRequest->execute();
                            $scoreResult = $scoreRequest->fetch(PDO::FETCH_OBJ);
                            $scoreRequest->closeCursor();
                            echo "<tr class='table-secondary'>";
                            echo "<td colspan='3' class='text-center'>Votre score : $scoreResult->maxS</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>