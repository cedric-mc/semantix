<!DOCTYPE html>
<?php
include('connexion.php');
$score = 0;
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<html>
<head>
    <title>Partie solo</title>
    <link rel="stylesheet" href="style2.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/networkgraph.js"></script>
</head>
<body>
<main>

    <div class="wrapper" style="top:15%;">
        <p>Score actuel : <span id="scoreDisplay"><?php echo $score; ?></span></p>
        <form id="addNodeForm" method = "post">
            <div class="input-box">
            <input type="text" id="newNodeName" placeholder="Enter node">
            </div>
            <button class="btn" type="submit">Add Node</button>
        </form>
        <br>
        <form action ="jeu_solo.php" method = "post">
            <button class="btn" id="endGameButton" type="submit" name="submit" value="sub">Fin de la partie</button>
            <br>
            <div class="register-link">
            <a href="jeu.php"> Retour </a>
            </div>
        </form>
    </div>

    <div style="position: relative; left: 40vh; top: 32vh;">
        <div id="networkGraph" style="width: 1100px; height: 650px; "></div>
        </div>
</main>

<?php
if(isset($_POST['submit']) && $_POST['submit'] === 'sub'){
    $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $id = $ligne->id;

    date_default_timezone_set('Europe/Paris');
    $date = date('y-m-d');
    $stmt = $dbh->prepare("INSERT INTO score_game (score, user_id, date) VALUES (:score, :id, :date)");
    $stmt->bindParam(':score', $score);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':date', $date);
    $stmt->execute();
    //sleep(3);
    echo '<meta http-equiv="refresh" content="0;url=jeu.php">';
}

?>
<script>
    var chart;
    var animalNames = ['Lion', 'Tiger', 'Bear', 'Elephant', 'Giraffe', 'Zebra', 'Panda', 'Kangaroo', 'Wolf', 'Fox'];
    var isFirstAddition = true; // Global flag to check if it's the first node being added
    function getRandomAnimals(animalArray) {
        var randomAnimals = [];
        while(randomAnimals.length < 2){
            var r = Math.floor(Math.random() * animalArray.length);
            if(randomAnimals.indexOf(animalArray[r]) === -1) randomAnimals.push(animalArray[r]);
        }
        return randomAnimals;
    }

    var selectedAnimals = getRandomAnimals(animalNames);
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
                    id: selectedAnimals[0],
                    name: selectedAnimals[0]
                }, {
                    id: selectedAnimals[1],
                    name: selectedAnimals[1]
                }],
                data: [{
                    from: selectedAnimals[0],
                    to: selectedAnimals[1]
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
                    chart.series[0].addPoint({ from: newNodeName, to: selectedAnimals[0] });
                    chart.series[0].addPoint({ from: newNodeName, to: selectedAnimals[1] });
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
