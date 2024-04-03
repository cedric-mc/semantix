<?php
session_start();
// Erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['user'])) {
    header('Location: ./');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Règles - Semonkey</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style/style.css">
        <link rel="stylesheet" href="style/css_rules.css">
        <link rel="shortcut icon" href="./img/monkey.png">
        <?php include("includes/head.php"); ?>
    </head>

    <body>
        <?php include("includes/menu.php"); ?> <!-- Inclusion du menu -->
        <main class="glassmorphism">
            <h1 class="title">Règles du Jeu Semonkey - Mode Solo</h1>
            <section class="rules">
                <h2>Objectif :</h2>
                <p>L'objectif du jeu est de former une chaîne de mots connectés entre eux par leur similarités sémantiques et orthographiques. En partant de deux mots donnés au début, vous devez créer une suite de mots où chaque nouveau mot est similaire, soit dans son sens, soit dans sa forme, au mot précédent. L'objectif est d'obtenir le score de ressemblance le plus élevé possible entre les mots.</p>

                <h2>Déroulement du jeu :</h2>
                <h3>Démarrage :</h3>
                <p>Le jeu commence avec deux mots initiaux. Ces mots sont les premiers maillons de votre chaîne de mots.</p>

                <h3>Entrée du joueur :</h3>
                <p>Vous devez entrer cinq mots supplémentaires, un après l'autre. Chaque nouveau mot doit être choisi de manière à ressembler, par son sens ou sa forme, au mot précédent dans la chaîne.</p>

                <h3>Vérification du dictionnaire :</h3>
                <p>Si vous proposez un mot qui n'est pas reconnu par le jeu, vous pourrez réessayer sans pénalité. Le jeu vous indiquera simplement que le mot n'est pas reconnu et vous invitera à essayer un autre mot.</p>

                <h3>Perte de tentative :</h3>
                <p>Si à un moment donné, vous proposez un mot qui a une similarité plus faible avec le mot précédent par rapport aux similarités existantes dans la chaîne, votre tentative sera considérée comme infructueuse. Cela signifie que vous perdrez cette tentative spécifique, et le jeu vous invitera à essayer un nouveau mot pour continuer la chaîne. Veillez donc à choisir des mots qui améliorent ou maintiennent la similarité tout au long de la chaîne pour obtenir le meilleur score possible.</p>

                <h3>Fin de la partie :</h3>
                <p>La partie se termine après que vous ayez entré vos cinq mots. Le score final est basé sur le maillon le plus faible de votre chaîne, c'est-à-dire la paire de mots consécutifs qui se ressemblent le moins.</p>

                <h2>Calcul du score :</h2>
                <h3>Ressemblance visuelle :</h3>
                <p>La ressemblance visuelle entre deux mots est évaluée en regardant combien de lettres doivent être changées pour passer d'un mot à l'autre. Plus il y a de lettres en commun, plus le score est élevé.</p>

                <h3>Ressemblance de sens :</h3>
                <p>La ressemblance de sens est évaluée en examinant à quel point les mots sont généralement utilisés dans des contextes similaires. Des mots qui ont souvent un sens ou une utilisation proches auront un score élevé.</p>

                <h3>Score final :</h3>
                <p>Le score final est déterminé en prenant en compte à la fois la ressemblance visuelle et la ressemblance de sens. La paire de mots consécutifs qui se ressemble le moins détermine votre score final. Le but est donc de choisir des mots qui non seulement se suivent bien, mais qui maintiennent également une forte ressemblance tout au long de la chaîne.</p>
            </section>
            <h1 class="title">Règles du Jeu Semonkey - Mode Multijoueur</h1>
            <section class="rules">
                <h2>Objectif :</h2>
                <p>L'objectif du jeu en mode multijoueur reste le même que pour le mode solo : former une chaîne de mots connectés entre eux par leur similarités sémantiques et orthographiques. Cependant, dans ce mode, les joueurs s'affrontent pour obtenir le score le plus élevé en construisant la chaîne la plus cohérente et la plus résistante.</p>

                <h2>Déroulement du jeu :</h2>
                <h3>Démarrage :</h3>
                <p>Le jeu commence avec deux mots initiaux, comme dans le mode solo. Ces mots sont les premiers maillons de la chaîne de mots. Les joueurs sont alternativement désignés pour proposer un mot supplémentaire à ajouter à la chaîne.</p>

                <h3>Entrée des joueurs :</h3>
                <p>À tour de rôle, chaque joueur propose un mot qui doit être choisi de manière à ressembler, par son sens ou sa forme, au mot précédent dans la chaîne. Les joueurs doivent tenir compte des mots déjà utilisés dans la chaîne afin de choisir judicieusement leur prochain mot pour maintenir une cohérence et maximiser le score.</p>

                <h3>Vérification du dictionnaire :</h3>
                <p>Si un joueur propose un mot qui n'est pas reconnu par le jeu, il pourra réessayer sans pénalité. Le jeu indiquera simplement que le mot n'est pas reconnu et invitera le joueur à essayer un autre mot.</p>

                <h3>Perte de tentative :</h3>
                <p>Si à un moment donné, un joueur propose un mot qui a une similarité plus faible avec le mot précédent par rapport aux similarités existantes dans la chaîne, sa tentative sera considérée comme infructueuse. Cela signifie que le joueur perd cette tentative spécifique, et le jeu invite le joueur suivant à proposer un nouveau mot pour continuer la chaîne.</p>

                <h3>Fin de la partie :</h3>
                <p>La partie se termine lorsque tous les joueurs ont eu l'opportunité de proposer un mot et que la chaîne compte donc un total de mots égal au nombre de joueurs multiplié par six (deux mots initiaux plus cinq mots supplémentaires pour chaque joueur). Le score final est calculé en prenant en compte la paire de mots consécutifs qui se ressemble le moins dans la chaîne complétée.</p>

                <h2>Calcul du score :</h2>
                <p>Le calcul du score final reste le même que pour le mode solo, en prenant en compte à la fois la ressemblance visuelle et la ressemblance de sens entre les mots consécutifs dans la chaîne.</p>

                <h2>Classement :</h2>
                <p>Le joueur ayant obtenu le score le plus élevé remporte la partie.</p>

                <h2>Stratégie :</h2>
                <p>En mode multijoueur, les joueurs doivent non seulement choisir des mots qui s'enchaînent bien pour maintenir une forte ressemblance tout au long de la chaîne, mais ils doivent également anticiper les choix des autres joueurs et adapter leur stratégie en conséquence pour gagner.</p>

                <h2>Remarque :</h2>
                <p>Le mode multijoueur est actuellement en développement et sera bientôt disponible sur Semonkey. Restez à l'écoute pour les mises à jour !</p>
                <p>Un chat de partie est également en cours de développement pour permettre aux joueurs de communiquer entre eux pendant la partie.</p>
            </section>
        </main>
    </body>
</html>
