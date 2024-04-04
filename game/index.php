<?php
    include("../class/User.php");
    include("../class/Game.php");
    include("game_fonctions.php");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // Erreur PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (!isset($_SESSION['user'])) {
        header('Location: ../');
        exit();
    }
    if (!isset($_SESSION['game'])) {
        header('Location: start_game.php');
        exit();
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    $game = Game::createGameFromGame(unserialize($_SESSION['game']));
    $paires = array();
    $paires = fileToArray($user);
    $highchartsData = createDataForGraph($user, $paires);

    $pairesTree = array();
    $pairesTree = fileToArrayTree($user);
    $highchartsDataTree = createDataForGraph($user, $pairesTree);
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Game - Semonkey</title>
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/css_game.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/networkgraph.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="shortcut icon" href="../img/monkey.png">
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
            <div class="player-info">
                <img src="<?php echo $user->getImageSrc();?>" alt="Profile Picture">
                <div class="username me-3"><?php echo $user->getPseudo(); ?></div>
                <button class="btn btn-dark me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasInfogame" aria-controls="offcanvasInfogame">Informations de la partie</button>
                <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTree" aria-controls="offcanvasTree">Arbre des paires</button>
            </div>
            <!-- Formulaire pour ajouter un nouveau mot -->
            <div class="addWord">
                <form id="add" action="addWordGraph.php" method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="word" name="word" placeholder="Nouveau mot" required>
                        <label for="word">Nouveau mot</label>
                    </div>
                    <input type="submit" value="Insérer un nouveau mot" class="btn btn-success border border-white <?php if (count($game->getWordsArray()) >= 7) echo 'disabled'; ?>">
                </form>
            </div>
            <div class="graph" id="container"></div><!-- Le graphique sera affiché ici -->
            <button type="button" class="endGameBtn" data-bs-toggle="modal" data-bs-target="#endGameModal" id="endGameButton">
                Finir la partie
            </button>
            <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvasInfogame" aria-labelledby="offcanvasInfogameLabel">
                <div class="offcanvas-header game-info-title">
                    <h5 class="offcanvas-title" id="offcanvasInfogameLabel">Informations de la partie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body game-info">
                    <p>Score actuel : <?php echo calculateScore($user); ?></p>
                    <p>Nombre de mots : <?php echo count($game->getWordsArray()); ?></p>
                    <p>Dernier mot : <?php if (count($game->getWordsArray()) > 2) echo ucfirst($game->getLastWord()); else echo "Aucun mot entré"; ?></p>
                    <p>Nombre de mots restants : <?php echo 7 - count($game->getWordsArray()) ?></p>
                </div>
            </div>
            <!-- Affiche le graphe de l'arbre des paires dans un offcanvas left -->
            <div class="offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="offcanvasTree" aria-labelledby="offcanvasTreeLabel">
                <div class="offcanvas-header game-info-title">
                    <h5 class="offcanvas-title" id="offcanvasTreeLabel">Arbre des paires</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body game-info">
                    <div class="graph" id="containerTree"></div><!-- Le graphique sera affiché ici -->
                </div>
            </div>
            <div class="modal fade" id="endGameModal" tabindex="-1" aria-labelledby="endGameModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="endGameModalLabel">Fin de la Partie</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bravo ! Votre score final est de <?php echo calculateScore($user); ?> point(s).<br> Relèverez-vous le défi à nouveau ? </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" id="replayButton">Rejouer</button>
                            <button type="button" class="btn btn-secondary" id="closeModalButton" data-bs-dismiss="modal">Terminer la partie</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Fonction pour charger les données depuis PHP
            function loadData() {
                // Charger les données depuis le fichier PHP
                var nodes = <?php echo json_encode($highchartsData["nodes"]); ?>;
                var links = <?php echo json_encode($highchartsData["links"]); ?>;
                var nodesTree = <?php echo json_encode($highchartsDataTree["nodes"]); ?>;
                var linksTree = <?php echo json_encode($highchartsDataTree["links"]); ?>;
                var highchartsData = {
                    "nodes": nodes,
                    "links": links
                };
                var highchartsDataTree = {
                    "nodes": nodesTree,
                    "links": linksTree
                };
                let chart = createChart(highchartsData); // Créer ou mettre à jour le graphique
                let chartTree = createChartTree(highchartsDataTree); // Créer ou mettre à jour le graphique
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

            function createChartTree(highchartsData) {
                let chart = Highcharts.chart('containerTree', {
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

                // Récupérez le bouton "Fermer" du modal par son ID et le bouton "Rejouer"
                var closeModalButton = document.getElementById("closeModalButton");
                var replayButton = document.getElementById("replayButton");

                // Attachez un gestionnaire d'événements au clic sur le bouton "Fermer" du modal
                closeModalButton.addEventListener('click', function () {
                    // Effectuez la redirection vers end_game.php lorsque le bouton "Fermer" du modal est cliqué
                    window.location.href = "end_game.php";
                });
                // Attachez un gestionnaire d'événements au clic sur le bouton "Rejouer"
                replayButton.addEventListener('click', function () {
                    window.location.href = "end_game.php?again=true";
                });
            });
        </script>

        <?php
            // Afficher les messages de débogage
//            if (isset($_SESSION['output'])) {
//                echo "<pre>";
//                print_r($_SESSION['output']);
//                echo "</pre>";
//            }
        ?>
    </body>
</html>