<?php
    include_once("class/User.php");
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: connexion/');
        exit;
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Semonkey</title>
        <link href="style/style.css" rel="stylesheet">
        <link href="style/css_home.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="shortcut icon" href="./img/monkeyapp.png">
        <?php include("includes/head.php"); ?>
        <style>
            label {
                color: #212529;
            }
        </style>
    </head>

    <body>
        <?php include("includes/menu.php"); ?>
        <main class="glassmorphism">
            <h1 class="title">Contact</h1>
            <h2 class="subtitle">Équipe</h2>
            <ul class="text-nowrap">
                <li><a href="https://github.com/mamadou186" target="_blank">BA Mamadou</a></li>
                <li><a href="https://github.com/cedric-mc" target="_blank">MARIYA CONSTANTINE Cédric</a></li>
                <li><a href="https://github.com/Abdelrahim-Riche" target="_blank">RICHE Abdelrahim</a></li>
                <li><a href="https://github.com/VincentSousa" target="_blank">SOUSA Vincent</a></li>
                <li><a href="https://github.com/Yacine771" target="_blank">ZEMOUCHE Yacine</a></li>
            </ul>
            <h2 class="subtitle">Contact</h2>
            <form action="contact.php" method="post">
                <div class="form-group row mb-2">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $user->getPseudo(); ?>" required>
                            <label for="name">Nom</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo $user->getEmail(); ?>" required>
                            <label for="email">Adresse email</label>
                        </div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Sujet" required>
                    <label for="subject">Sujet</label>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="message" name="message" placeholder="Message" required></textarea>
                    <label for="message">Message</label>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>