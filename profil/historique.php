<?php
    include_once("../includes/conf.bkp.php");
    include_once("../includes/fonctions.php");
    include_once("../class/User.php");
    session_start();
    // Erreur PHP
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    if (!isset($_SESSION['user'])) {
        header('Location: ./');
        exit();
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    $pseudo= $user->getPseudo();
    // Requête pour récupérer l'historique
    $historicRequest = $cnx->prepare("
    SELECT score, dateHeure
    FROM SAE_USERS u, SAE_SCORES s
    WHERE pseudo = :pseudo AND u.num_user = s.num_user
    ORDER BY dateHeure DESC;");
    $historicRequest->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $historicRequest->execute();
    $historicResult = $historicRequest->fetchAll(PDO::FETCH_OBJ);
    $historicRequest->closeCursor();

    $menu = 1;
?>
<html lang="fr">
    <head>
        <title>Historique - Semonkey</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/table.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <?php include("../includes/head.php"); ?>
    </head>

    <body>
        <?php include("../includes/menu.php"); ?>
        <main class="glassmorphism">
            <h1 class="title">Historique du jeu Semonkey</h1>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="table-dark text-center">
                            <th scope="col">#</th>
                            <th scope="col">Date et Heure</th>
                            <th scope="col">Score</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php
                        $i = 1;
                        foreach ($historicResult as $historic) {
                            echo "<tr>";
                            echo "<td>$i</td>";
                            echo "<td>" . makeDateTime($historic->dateHeure) . "</td>";
                            echo "<td>$historic->score</td>";
                            echo "</tr>";
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </body>
</html>