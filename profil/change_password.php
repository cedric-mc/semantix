<?php
    include_once("../class/User.php");
    session_start();
    // Utilisateur connecté ?
    if (!isset($_SESSION['user'])) {
        header('Location: ./');
        exit;
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));

    // Messages d'erreurs possibles
    $erreursMdp = [
        1 => ["Le mot de passe a bien été changé.", "alert-success"],
        2 => ["L'ancien mot de passe est incorrect.", "alert-danger"],
        3 => ["Les nouveaux mots de passe ne correspondent pas.", "alert-danger"],
        4 => ["Le nouveau mot de passe est identique à l'ancien.", "alert-danger"],
        5 => ["Le mot de passe doit faire minimum 12 caractères et doit contenir au minimum 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial.", "alert-danger"],
        6 => ["Code incorrect ou expiré !", "alert-danger"]
    ];

    // Récupérer le code d’erreur depuis l'URL
    $codeMdp = isset($_GET['erreur']) ? (int)$_GET['erreur'] : 0;

    $menu = 1;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Changer Mot de passe - Semonkey</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/form.css">
        <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <?php include("../includes/head.php"); ?>
    </head>

    <body>
        <?php include("../includes/menu.php"); ?>
        <main class="glassmorphism">
            <h1 class="title">Changer le mot de passe</h1>
            <form action="verification_code_password.php" method="POST">
                <div class="input-field">
                    <input type="password" id="password1" name="password1" required>
                    <label for="password1">Ancien mot de passe :</label>
                </div>
                <div class="input-field">
                    <input type="password" id="password2" name="password2" required>
                    <label for="password2">Nouveau mot de passe :</label>
                </div>
                <div class="input-field">
                    <input type="password" id="password3" name="password3" required>
                    <label for="password3">Confirmer le nouveau mot de passe :</label>
                </div>
                <button id="formButton" type="submit" class="btn fw-semibold">Valider</button>
            </form>
            <?php
                // Si le message d'erreur est différent de 0
                if ($codeMdp > 0 && $codeMdp < 7) {
                    echo "<br><div id='msg-error' class='alert' role='alert'></div>";
                }
            ?>
        </main>
        <script>
            // Je récupère le message d’erreur
            let msgCode = <?php echo json_encode($codeMdp); ?>;
            let msgError = <?php echo json_encode($erreursMdp[$codeMdp][0]); ?>;
            // Si le message d’erreur est différent de 0
            if (msgCode > 0 && msgCode < 7) {
                // J'affiche le message d'erreur
                document.getElementById('msg-error').innerHTML = msgError;
                // Je change la couleur du message d'erreur
                document.getElementById('msg-error').classList.add(<?php echo json_encode($erreursMdp[$codeMdp][1]); ?>);
                document.getElementById('msg-error').classList.add('visible');
                // Après l'expiration du cookie, on actualise la page pour le supprimer
                setTimeout(function () {
                    window.location.href = 'change_password.php';
                }, 10000);
            }
        </script>
    </body>
</html>