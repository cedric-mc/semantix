<?php
    include_once("class/User.php");
    include_once("includes/fonctions.php");
    include_once("includes/requetes.php");
    include_once("includes/conf.php");
    // Erreur PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
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
        <title>Traces - Semonkey</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/3f3ecfc27b.js"></script>
        <link rel="shortcut icon" href="./img/monkeyapp.png">
        <link rel="stylesheet" href="style/style.css">
        <link rel="stylesheet" href="style/table.css">
        <link rel="stylesheet" href="style/css_traces.css">
        <?php include("includes/head.php"); ?>
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