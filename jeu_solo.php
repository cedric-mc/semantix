<!DOCTYPE html>
<?php
session_start();
$motDepart = exec("./Fichiers_C/random_word");
<<<<<<< HEAD
$motArrivee = exec("./Fichiers_C/random_word");
$output = exec("./Fichiers_C/new_game Fichiers_C/words.bin $motDepart $motArrivee");
$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin $motArrivee");
$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin test");
$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin mot");
$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin oui");
$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin non");
$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin marre");
$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin malin");
$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin antithèse");
$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin malautru");
$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin matelot");
$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin vilain");

$chemin = "";
//$output = exec("./Fichiers_C/new_game Fichiers_C/words.bin $motDepart $motArrivee");
=======
$chemin = "";
//$output = exec("./Fichiers_C/new_game Fichiers_C/words.bin $motDepart $motArrivee");
sleep(1);
$motArrivee = exec("./Fichiers_C/random_word");
//$output1 = exec("./Fichiers_C/add_word Fichiers_C/words.bin test");
>>>>>>> bcfe8510e4c8a716b034e90ac9f7087f41d7b557
//$output2 = exec("./Fichiers_C/add_word Fichiers_C/words.bin pomme");


echo $motDepart;
echo"\n";
echo $motArrivee;
//echo $output;



include('include/connexion.php');

include('include/redirection.php');


if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0; // Valeur par défaut
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<html>
<head>
    <title>Partie solo</title>
    <link rel="stylesheet" href="style/style2.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/networkgraph.js"></script>
    <link rel="icon" href="img/monkeyapp.png">

</head>
<body>
<main>

    <div class="wrapper" style="top:15%;">
        <form id="addNodeForm" method = "post">
            <div class="input-box">
            <input type="text" id="newNodeName" placeholder="Enter node">
            </div>
            <button class="btn" type="submit">Add Node</button>
        </form>
        <br>
        <form action ="jeu_solo.php" method = "post">
            <input type="hidden" name="finalScore" id="finalScore" value="<?php echo $_SESSION['score']; ?>">
            <button class="btn" id="endGameButton" type="submit" name="submit" value="sub">Fin de la partie</button>
            <br>
            <div class="register-link">
            <a href="jeu.php"> Retour </a>
            </div>
        </form>
        <p>Score actuel : <span id="scoreDisplay"><?php echo $_SESSION['score']; ?></span></p>

    </div>

    <div style="position: relative; left: 40vh; top: 40vh;">
        <div id="networkGraph" style="width: 700px; height: 600px; "></div>
        </div>
</main>

<?php
if(isset($_POST['submit']) && $_POST['submit'] === 'sub'){
    $score = isset($_SESSION['score']) ? $_SESSION['score'] : 0;
    $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $id = $ligne->id;

    if ($score > 0){
    date_default_timezone_set('Europe/Paris');
    $date = date('y-m-d');
    $stmt = $dbh->prepare("INSERT INTO score_game (score, user_id, date) VALUES (:score, :id, :date)");
    $stmt->bindParam(':score', $score );
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':date', $date);
    $stmt->execute();
    sleep(3);
    $_SESSION['score'] = 0;
    echo '<meta http-equiv="refresh" content="0;url=jeu.php">';}
}
    ?>
<script>
    var chart;
    var isFirstAddition = true; // Global flag to check if it's the first node being added

    document.addEventListener('DOMContentLoaded', function () {
        chart = Highcharts.chart('networkGraph', {
            chart: {
                type: 'networkgraph',
                height: '100%',
                events: {
                    load: function () {
                        var chart = this;
                        // Vous pouvez essayer de déplacer les nœuds en ajustant leur position
                        // Notez que cela peut interférer avec la simulation physique
                        chart.series[0].nodes[0].plotX = chart.plotWidth / 2;
                        chart.series[0].nodes[0].plotY = chart.plotHeight / 4;
                        chart.series[0].nodes[1].plotX = chart.plotWidth / 2;
                        chart.series[0].nodes[1].plotY = chart.plotHeight / 4 + 100; // un exemple de décalage
                        chart.redraw();
                    }
                }
            },
            title: {
                text: ''
            },
            series: [{
                layoutAlgorithm: {
                    gravitationalConstant: 0.1,
                    centralGravity: 0.01,
                    springLength: 20, // Cette option ajuste la longueur des liens
                    springConstant: 0.1,
                    maxIterations: 500,
                    initialPositions: 'random', // Commence avec des positions aléatoires
                    enableSimulation: true // Active la simulation dynamique
                },
                dataLabels: {
                    enabled: true,
                    linkFormat: '',
                    linkTextPath: {
                        enabled: false // Ensure that text is not displayed on links
                    },
                    style: {
                        fontSize: '12px', // Définit la taille de la police des étiquettes de données
                        color: '#FFFFFF' // Vous pouvez aussi définir la couleur de la police si nécessaire
                    }
                },
                nodes: [{
                    id: '<?php echo $motDepart; ?>',
                    name:'<?php echo $motDepart; ?>'
                }, {
                    id: '<?php echo $motArrivee; ?>',
                    name: '<?php echo $motArrivee; ?>'
                }],
                data: [{
                    from: '<?php echo $motDepart; ?>',
                    to: '<?php echo $motArrivee; ?>'
                }],
                marker: {
                    radius: 30,
                    symbol: 'circle'
                }
            }]

        });

        document.getElementById('addNodeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var newNodeName = document.getElementById('newNodeName').value;

            if(newNodeName && !chart.get(newNodeName)) { // Check if node doesn't already exist
                chart.series[0].addPoint({ // Add the new node
                    id: newNodeName,
                    name: newNodeName
                });



                if (isFirstAddition) {
                    // For the first addition, link the new node between the initial nodes
                    chart.series[0].addPoint({ from: newNodeName, to: '<?php echo $motDepart; ?>'});
                    chart.series[0].addPoint({ from: newNodeName, to: '<?php echo $motArrivee; ?>' });
                    isFirstAddition = false; // Update the flag
                } else {
                    // For subsequent additions, link to a random existing node
                    var existingNodes = chart.series[0].nodes;
                    var randomNode = existingNodes[Math.floor(Math.random() * existingNodes.length)];
                    if (randomNode) {
                        chart.series[0].addPoint({ from: newNodeName, to: randomNode.id });
                    }
                }

                // Obtenez le score actuel côté client
                var currentScore = parseInt(document.getElementById('scoreDisplay').textContent);

                // Effectuez une requête AJAX pour mettre à jour le score
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_score.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Mettez à jour le score côté client
                        var newScore = parseInt(xhr.responseText);
                        document.getElementById('scoreDisplay').textContent = newScore;
                    }
                };
                xhr.send('newNodeName=' + encodeURIComponent(newNodeName) + '&currentScore=' + currentScore);
                document.getElementById('newNodeName').value = ''; // Clear the input field
            }
        });
    });





</script>




</body>
</html>
