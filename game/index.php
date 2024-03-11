<?php
    include("../class/User.php");
    include("../class/Game.php");
    include("game_fonctions.php");
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    if (!isset($_SESSION['user'])) {
        header('Location: ../');
        exit();
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));

    $messagesErreur = [
        1 => "Le mot n'est pas dans le dictionnaire.",
        2 => "Le mot est déjà dans la chaîne.",
        3 => "Le mot n'est pas assez proche du dernier mot de la chaîne."
    ];
    $erreur = (isset($_GET['erreur'])) ? $_GET['erreur'] : 0;

    $game = unserialize($_SESSION['game']);
    $paires = array();
    $paires = fileToArray($user);
    $highchartsData = createDataForGraph($user, $paires);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Game - Semantic Analogy Explorer</title>
        <meta name="description" content="Venez Jouez à Semantic Analogy Explorer (SAE), un jeu en ligne à un ou plusieurs joueurs basé sur les similarités entre mots : « Semantic Analogy Explorer ». Chaque joueur reçoit un mot de départ et un mot cible et propose des mots proches afin de créer une chaîne de mots similaires pour relier le mot de départ au mot cible. ">
        <meta name="keywords" content="Semantic Analogy Explorer, SAE, jeu, jeu en ligne, jeu de mots, jeu de lettres, jeu de lettres en ligne, jeu de mots en ligne, jeu de lettres multijoueur, jeu de mots multijoueur, jeu de lettres multijoueur en ligne, jeu de mots multijoueur en ligne, jeu de lettres multijoueur gratuit, jeu de mots multijoueur gratuit, jeu de lettres multijoueur gratuit en ligne, jeu de mots multijoueur gratuit en ligne, jeu de lettres multijoueur gratuit sans inscription, jeu de mots multijoueur gratuit sans inscription, jeu de lettres multijoueur gratuit en ligne sans inscription, jeu de mots multijoueur gratuit en ligne sans inscription, jeu de lettres multijoueur gratuit en ligne sans inscription et sans téléchargement, jeu de mots multijoueur gratuit en ligne sans inscription et sans téléchargement, jeu de lettres multijoueur gratuit en ligne sans inscription et sans téléchargement, jeu de mots multijoueur gratuit en ligne sans inscription et sans téléchargement">
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/css_game.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/networkgraph.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <?php include("../includes/head.php"); ?>
        <style>
            * {
                color: white;
            }
            label {
                color: black;
            }
        </style>
    </head>

    <body>
        <div class="parent">
            <div class="div4">
                <h1 class="title">Semantic Analogy Explorer</h1>
            </div>
            <div class="div1" id="container"></div> <!-- Le graphique sera affiché ici -->
            <!-- Formulaire pour ajouter un nouveau mot -->
            <div class="div2">
                <form id="add" action="addWordGraph.php" method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="word" name="word" placeholder="Nouveau mot" required>
                        <label for="word">Nouveau mot</label>
                    </div>
                    <input type="submit" value="Insérer un nouveau mot" class="btn btn-success <?php if (count($game->wordsArray) >= 7) echo 'disabled'; ?>">
                </form>
                <div id="end">
                    <a href="#" class="btn btn-primary" id="endGameButton">Finir la partie</a>
                </div>
            </div>
                <div class="div3">
                <p>Score actuel : <?php echo calculateScore($user); ?></p>
                <p>Nombre de mots : <?php echo count($game->wordsArray); ?></p>
                <p>Dernier mot : <?php if (count($game->wordsArray) > 2) echo ucfirst($game->lastWord); else echo "Aucun mot entré"; ?>
                </p>
                <p>Nombre de mots restants : <?php echo 7 - count($game->wordsArray) ?></p>
            </div>
            <div class="modal fade" id="endGameModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Fin de la Partie</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bravo ! Votre score final est de <?php echo calculateScore($user); ?> point(s).<br> Relèverez-vous le défi à nouveau ? </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="closeModalButton" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="div5">
            <h1 class="title"><?php if ($erreur != 0) echo "Erreur : " . $messagesErreur[$erreur]; ?></h1>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            var error = <?php echo json_encode($erreur); ?>;
            var messagesErreur = [
                "",
                "Le mot n'est pas dans le dictionnaire.",
                "Le mot est déjà dans la chaîne.",
                "Le mot n'est pas assez proche des mots précédents."
            ];
            document.addEventListener('DOMContentLoaded', function() {
                if (error != 0) {
                    alert("Erreur : " + messagesErreur[error]);
                }
            });
        </script>
        <script>
            // Fonction pour charger les données depuis PHP
            function loadData() {
                // Charger les données depuis le fichier PHP
                var nodes = <?php echo json_encode($highchartsData["nodes"]); ?>;
                var links = <?php echo json_encode($highchartsData["links"]); ?>;
                var highchartsData = {
                    "nodes": nodes,
                    "links": links
                };
                let chart = createChart(highchartsData); // Créer ou mettre à jour le graphique
            }

            function createChart(highchartsData) {
                let chart = Highcharts.chart('container', {
                    chart: {
                        type: 'networkgraph',
                        plotBorderWidth: 0,
                        animation: true
                    },
                    title: {
                        text: ''
                    },
                    plotOptions: {
                        networkgraph: {
                            keys: ['from', 'to'],
                            layoutAlgorithm: {
                                enableSimulation: true,
                                linkLength: 150,
                                integration: 'verlet'
                            },
                            marker: {
                                radius: 10
                            },
                            link: {
                                width: 2,
                                color: '#C0C0C0' // Couleur des liens - Argenté
                            },
                            node: {
                                marker: {
                                    fillColor: '#000000', // Couleur de remplissage des nœuds - Noir
                                    lineWidth: 2,
                                    lineColor: '#C0C0C0' // Couleur de la bordure des nœuds - Argenté
                                },
                                events: {
                                    add: function(event) {
                                        event.point.graphic.animate({
                                            radius: 15
                                        }, {
                                            duration: 1000,
                                            easing: 'elastic'
                                        });
                                    }
                                }
                            }
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        name: 'K3',
                        dataLabels: {
                            enabled: true,
                            linkFormat: '{point.weight}',
                            style: {
                                fontSize: '0.8rem',
                                fontWeight: 'normal'
                            }
                        },
                        data: highchartsData.links.map(function(link) {
                            return {
                                from: link.source,
                                to: link.target,
                                weight: link.linkTextPath
                            };
                        }),
                        nodes: highchartsData.nodes
                    }]
                });

                return chart;
            }


            // Charger les données et créer le graphique
            loadData();

            const myModal = document.getElementById('endGameModal');
            const myInput = document.getElementById('myInput');

            myModal.addEventListener('shown.bs.modal', () => {
                if(myInput) {
                    myInput.focus();
                }
            });
            document.addEventListener('DOMContentLoaded', function() {
                // Récupérez le modal par son ID
                var endGameModal = new bootstrap.Modal(document.getElementById('endGameModal'));

                // Récupérez le bouton "Finir la partie" par son ID (Assurez-vous que cet ID existe dans votre HTML)
                var endGameButton = document.getElementById("endGameButton");

                // Attachez un gestionnaire d'événements au clic sur le bouton "Finir la partie"
                endGameButton.addEventListener('click', function () {
                    // Affichez le modal lorsque le bouton "Finir la partie" est cliqué
                    endGameModal.show();
                });

                // Récupérez le bouton "Fermer" du modal par son ID
                var closeModalButton = document.getElementById("closeModalButton");

                // Attachez un gestionnaire d'événements au clic sur le bouton "Fermer" du modal
                closeModalButton.addEventListener('click', function () {
                    // Effectuez la redirection vers end_game.php lorsque le bouton "Fermer" du modal est cliqué
                    window.location.href = "end_game.php";
                });
            });
        </script>

        <?php
//            if (isset($_SESSION['output'])) {
//                echo "<pre>";
//                print_r($_SESSION['output']);
//                echo "</pre>";
//            }
        ?>
    </body>
</html>