<?php
    session_start();
    // Utilisateur connecté ?
    if (isset($_SESSION['user'])) {
        header("Location: ../");
        exit;
    }
    // Messages possibles
    $messagesErreur = [
        1 => ["Si le pseudo et l'adresse e-mail que vous avez saisis correspondent, vous recevrez alors un e-mail pour changer votre mot de passe.", "alert-warning"],
        2 => ["Mot de passe réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.", "alert-success"]
    ];
    // Récupérer le code d'erreur depuis l'URL
    $codeErreur = isset($_GET['erreur']) ? (int)$_GET['erreur'] : 0;
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Mot de passe Oublié ? - Semonkey</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/form.css">
        <?php include("../includes/head.php"); ?>
    </head>

    <body>
        <main class="glassmorphism">
            <form method="POST" action="script-forgot_password.php">
                <h1 class="title">Récupérer votre mot de passe</h1>
                <div class="input-field">
                    <input name="pseudo" type="text" id="pseudo" required>
                    <label for="pseudo">Pseudo</label>
                </div>
                <div class="input-field">
                    <input name="email" type="email" id="email" required>
                    <label for="email">Email</label>
                </div>
                <button id="formButton" type="submit" class="btn fw-semibold">Récuperer votre mot de passe</button>
            </form>
            <?php
                // Si le message d'erreur est différent de 0
                if ($codeErreur > 0 && $codeErreur < 5) {
                    echo "<br><div id='msg-error' class='alert' role='alert'></div>";
                }
            ?>
        </main>
        <script>
            // Je récupère le message d’erreur
            let msgCode = <?php echo json_encode($codeErreur); ?>;
            let msgError = <?php echo json_encode($messagesErreur[$codeErreur][0]); ?>;
            // Si le message d’erreur est différent de 0
            if (msgCode > 0 && msgCode < 3) {
                // J'affiche le message d'erreur
                document.getElementById('msg-error').innerHTML = msgError;
                // Je change la couleur du message d'erreur
                document.getElementById('msg-error').classList.add(<?php echo json_encode($messagesErreur[$codeErreur][1]); ?>);
                document.getElementById('msg-error').classList.add('visible');
                // Après l'expiration du cookie, on actualise la page pour le supprimer
                setTimeout(function () {
                    window.location.href = 'index.php';
                }, 10000);
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>