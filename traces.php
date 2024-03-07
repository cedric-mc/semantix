<?php
    include_once("class/User.php");
    include_once("includes/fonctions.php");
    include_once("includes/requetes.php");
    include_once("includes/conf.bkp.php");
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: ./');
        exit;
    }
    $user = unserialize($_SESSION['user']);

    // Récupération les traces de l'utilisateur
    $tracesOnlyMeRequest = $cnx->prepare($userTrace);
    $tracesOnlyMeRequest->bindValue(":num_user", $user->getIdUser(), PDO::PARAM_INT);
    $tracesOnlyMeRequest->execute();
    $tracesOnlyMeResult = $tracesOnlyMeRequest->fetchAll(PDO::FETCH_OBJ);
    $tracesOnlyMeRequest->closeCursor();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Traces - Semantic Analogy Explorer</title>
        <meta name="description" content="Venez Jouez à Semantic Analogy Explorer (SAE), un jeu en ligne à un ou plusieurs joueurs basé sur les similarités entre mots : « Semantic Analogy Explorer ». Chaque joueur reçoit un mot de départ et un mot cible et propose des mots proches afin de créer une chaîne de mots similaires pour relier le mot de départ au mot cible. ">
        <meta name="keywords" content="Semantic Analogy Explorer, SAE, jeu, jeu en ligne, jeu de mots, jeu de lettres, jeu de lettres en ligne, jeu de mots en ligne, jeu de lettres multijoueur, jeu de mots multijoueur, jeu de lettres multijoueur en ligne, jeu de mots multijoueur en ligne, jeu de lettres multijoueur gratuit, jeu de mots multijoueur gratuit, jeu de lettres multijoueur gratuit en ligne, jeu de mots multijoueur gratuit en ligne, jeu de lettres multijoueur gratuit sans inscription, jeu de mots multijoueur gratuit sans inscription, jeu de lettres multijoueur gratuit en ligne sans inscription, jeu de mots multijoueur gratuit en ligne sans inscription, jeu de lettres multijoueur gratuit en ligne sans inscription et sans téléchargement, jeu de mots multijoueur gratuit en ligne sans inscription et sans téléchargement, jeu de lettres multijoueur gratuit en ligne sans inscription et sans téléchargement, jeu de mots multijoueur gratuit en ligne sans inscription et sans téléchargement">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/3f3ecfc27b.js"></script>
        <link rel="shortcut icon" href="./img/monkeyapp.png">
        <link rel="stylesheet" href="style/style.css">
        <link rel="stylesheet" href="style/table.css">
        <link rel="stylesheet" href="style/css_traces.css">
    </head>

    <body>
        <?php include("includes/menu.php"); ?>
        <main class="glassmorphism">
            <h1 class="title">500 Dernières Traces de <?php echo $user->getPseudo(); ?></h1>
            <!-- Nombre de traces -->
            <div class="text-end">
                <p class="text-white">Nombre de traces : <?php echo count($tracesOnlyMeResult); ?></p>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="table-dark">
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Heure</th>
                            <th scope="col">IP</th>
                            <th scope="col">Action réalisée</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    foreach ($tracesOnlyMeResult as $trace) {
                        echo "<tr class='" . addStyleTableRow($trace->action) . "'>";
                        echo "<td>$i</td>";
                        echo "<td>" . makeDate($trace->timestamp) . "</td>";
                        echo "<td>" . makeHour($trace->timestamp) . "</td>";
                        echo "<td>$trace->ip_adress</td>";
                        echo "<td>$trace->action</td>";
                        echo "</tr>";
                        $i++;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>