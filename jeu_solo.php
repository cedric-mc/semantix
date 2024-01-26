<!DOCTYPE html>
<?php
session_start();
include('include/connexion.php');
include('include/redirection.php');
// Function new game
function new_game()
{
    $motDepart = exec("./Fichiers_C/random_word");
    sleep(1);
    $motArrivee = exec("./Fichiers_C/random_word");
    exec("./Fichiers_C/new_game Fichiers_C/words.bin $motDepart $motArrivee", $output);

    $result = [
        'motDepart' => $motDepart,
        'motArrivee' => $motArrivee
    ];

    return $result;
}

// Function add words
function add_word($mot)
{
    return exec("./Fichiers_C/add_word Fichiers_C/words.bin $mot");
}

$game = new_game();
$motDepart = $game['motDepart'];
$motArrivee = $game['motArrivee'];


//echo $output;
function tree_branch()
{
    exec("./jdk-21/bin/java -jar ./Fichiers_Java/SAE/out/artifacts/SAE_jar/SAE.jar optimize fichier_du_jeu.txt 2>&1", $output);
    $branch = json_encode($output);
    return $branch;
}


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
        <form action="jeu_solo.php" id="addNodeForm" method="post">

            <div class="input-box">
                <input type="text" id="newNodeName" name="newNodeName" placeholder="Enter node">
            </div>
            <button class="btn" type="submit">Add Node</button>
        </form>
        <br>
        <form action="jeu_solo.php" method="post">
            <input type="hidden" name="finalScore" id="finalScore" value="<?php echo $_SESSION['score']; ?>">
            <button class="btn" id="endGameButton" type="submit" name="submit" value="sub">Fin de la partie</button>
            <br>
            <div class="register-link">
                <a href="jeu.php"> Retour </a>
            </div>
        </form>
        <p>Score actuel : <span id="scoreDisplay"><?php echo $_SESSION['score']; ?></span></p>
        <?php
        $branch = tree_branch();
        echo $branch;
        ?>
    </div>

    <div style="position: relative; left: 40vh; top: 40vh;">
        <div id="networkGraph" style="width: 700px; height: 600px; "></div>
    </div>
</main>

<?php
if (isset($_POST['submit']) && $_POST['submit'] === 'sub') {
    $score = isset($_SESSION['score']) ? $_SESSION['score'] : 0;
    $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $id = $ligne->id;

    if ($score > 0) {
        date_default_timezone_set('Europe/Paris');
        $date = date('y-m-d');
        $stmt = $dbh->prepare("INSERT INTO score_game (score, user_id, date) VALUES (:score, :id, :date)");
        $stmt->bindParam(':score', $score);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        sleep(3);
        $_SESSION['score'] = 0;
        echo '<meta http-equiv="refresh" content="0;url=jeu.php">';
    }
}
?>


<script>
    function fetch_tree() {
        return fetch(`exec_java_tree.php?timestamp=${new Date().getTime()}`)
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('La requête a échoué.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la requête:', error);
                throw error;
            });
    }

    function transformerDonneesEnListes(data) {
        let listes = [];

        data.forEach(element => {
            // Vérifier si l'élément commence par "Branch:"
            if (element.startsWith("Branch: ")) {
                // Extraire la partie après "Branch: "
                let contenuBranch = element.substring("Branch: ".length);

                // Séparer les mots du score
                let [mots, scoreStr] = contenuBranch.split(':');
                if (mots && scoreStr) {
                    let score = parseFloat(scoreStr.trim());
                    let motsArray = mots.split(',').map(mot => mot.trim());

                    // Ajouter [mot1, mot2, score] à la liste
                    if (motsArray.length === 2) {
                        listes.push([...motsArray, score]);
                    }
                }
            }
        });

        return listes;
    }

    function addNewData(chart, newData) {
        newData.forEach(dataPoint => {
            if (dataPoint.from && dataPoint.to) {
                // Ajouter un lien
                chart.series[0].addPoint({
                    from: dataPoint.from,
                    to: dataPoint.to
                }, false);
            } else if (dataPoint.id) {
                // Ajouter un nœud
                chart.series[0].addPoint({
                    id: dataPoint.id,
                    name: dataPoint.name
                }, false);
            }
        });

        chart.redraw();
    }



    function removeData(chart) {
        // Supprimer tous les points (nœuds et liens) existants
        while (chart.series[0].data.length > 0) {
            chart.series[0].data[0].remove(false);
        }

        // Redessiner le graphique
        chart.redraw();
    }

    var chart;
    var isFirstAddition = true; // Global flag to check if it's the first node being added
    var mots = null;

    document.addEventListener('DOMContentLoaded', function () {
        chart = Highcharts.chart('networkGraph', {
            chart: {
                type: 'networkgraph'
            },
            plotOptions: {
                networkgraph: {
                    layoutAlgorithm: {
                        enableSimulation: true
                    }
                }
            },

            series: [{
                dataLabels: {
                    enabled: true,
                    linkTextPath: {
                        attributes: {
                            dy: 12
                        }
                    },
                    linkFormat: '{point.fromNode.name} \u2192 {point.toNode.name}',
                    textPath: {
                        enabled: true,
                        attributes: {
                            dy: 14,
                            startOffset: '45%',
                            textLength: 80
                        }
                    },
                    format: 'Node: {point.name}'
                },
                marker: {
                    radius: 35
                },
                data: [{
                    from: '<?php echo $motDepart?>',
                    to:  '<?php echo $motArrivee?>'
                },]
            }]
        });

        document.getElementById('addNodeForm').addEventListener('submit', function (e) {
            e.preventDefault();
            removeData(chart);
            var newNodeName = document.getElementById('newNodeName').value;
            fetch("exec.php?mot=" + newNodeName)
                .then(response => {
                    if (response.ok) {
                        // La requête s'est terminée avec succès
                        return response.text(); // Vous pouvez utiliser response.json() si vous attendez une réponse JSON
                    } else {
                        // La requête a échoué
                        throw new Error('La requête a échoué.');
                    }
                })
                .then(data => {
                    // Vous pouvez traiter la réponse ici, data contient la réponse du serveur
                    return fetch_tree();
                })
                .then(treeData => {
                    mots = transformerDonneesEnListes(treeData);
                    console.log("Données de l'arbre : ", mots)

                    let ensembleDeMots = new Set();
                    let newData = [];

                    mots.forEach(function (element) {
                        if (!ensembleDeMots.has(element[0])) {
                            newData.push({ id: element[0], name: element[0] });
                            ensembleDeMots.add(element[0]);
                        }
                        if (!ensembleDeMots.has(element[1])) {
                            newData.push({ id: element[1], name: element[1] });
                            ensembleDeMots.add(element[1]);
                        }
                        newData.push({ from: element[0], to: element[1] });
                    });

                    addNewData(chart, newData); // Ajouter les nouvelles données au graphique






                        // Obtenez le score actuel côté client
                        var currentScore = parseInt(document.getElementById('scoreDisplay').textContent);

                        // Effectuez une requête AJAX pour mettre à jour le score
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'update_score.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                // Mettez à jour le score côté client
                                var newScore = parseInt(xhr.responseText);
                                document.getElementById('scoreDisplay').textContent = newScore;
                            }
                        }
                        xhr.send('newNodeName=' + encodeURIComponent(newNodeName) + '&currentScore=' + currentScore);
                        document.getElementById('newNodeName').value = ''; // Clear the input field

                })
                .catch(error => {
                    // Gérer les erreurs ici, par exemple afficher un message d'erreur
                    console.error('Erreur lors de la requête:', error);
                });

        });
    });


</script>


</body>
</html>