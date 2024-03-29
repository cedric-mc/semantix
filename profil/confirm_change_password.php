<?php
    include_once("../class/User.php");
    session_start();
    // Utilisateur connecté ?
    if (!isset($_SESSION['user'])) {
        header('Location: ./');
        exit;
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));

    if (!isset($_SESSION['verification_code'])) {
        header('Location: ./');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Confirmer - Semonkey</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/form.css">
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <?php include_once("../includes/head.php"); ?>
    </head>

    <body>
        <main class="glassmorphism">
            <h1 class="title">Code de confirmation</h1>
            <form action="script-password.php" method="post">
                <div class="input-field">
                        <input type="text" id="verification_code" name="verification_code" required>
                    <label for="verification_code">Code de Vérification :</label>
                </div>
                <button id="formButton" type="submit" class="btn fw-semibold">Valider</button>
            </form>
        </main>
    </body>
</html>