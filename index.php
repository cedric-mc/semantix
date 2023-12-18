<?php
include('connexion.php');
session_start();

if (isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
    header('Location: compte.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 50px;
            text-align: center;
        }
        .container {
            width: 400px;
        }
        .form-group {
            text-align: left;
        }
        .button {
            margin-top: 15px;
        }
        .error {
            color: red;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Se connecter</h1>

        <form action="" method="post">
            <div class="form-group">
                <label for="pseudo">Pseudo: </label>
                <input type="text" id="pseudo" name="pseudo" class="form-control" />
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe: </label>
                <input type="password" id="mdp" name="mdp" class="form-control" />
            </div>
            <div class="button">
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </div>
        </form>

        <p class="mt-3"><a href="creer.php"> Si vous n'avez pas de compte créez-en un</a></p>
        <p><a href="recup.php"> Si vous avez oublié votre mot de passe, cliquez sur ce lien</a></p>

        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Votre logique de connexion ici...
        }
        ?>
    </div>

    <!-- Bootstrap JS and dependencies (jQuery, Popper.js) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
