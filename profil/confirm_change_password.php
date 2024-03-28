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

    $erreursConfirmMdp = [
        1 => ["Le code de confirmation est correct. Mot de passe modifié avec succès.", "alert-success"],
        2 => []
    ];

    $codeConfirmMdp = isset($_GET['confirmMdpError']) ? (int)$_GET['confirmMdpError'] : 0;
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
        <?php include("../includes/menu.php"); ?>
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
            <?php
                // Si le message d'erreur est différent de 0
                if ($codeConfirmMdp > 0 && $codeConfirmMdp < 6) {
                    echo "<br><div id='msg-error' class='alert' role='alert'></div>";
                }
            ?>
        </main>
        <script>
            // Je récupère le message d’erreur
            let msgCode = <?php echo json_encode($codeConfirmMdp); ?>;
            let msgError = <?php echo json_encode($erreursConfirmMdp[$codeConfirmMdp][0]); ?>;
            // Si le message d’erreur est différent de 0
            if (msgCode > 0 && msgCode < 6) {
                // J'affiche le message d'erreur
                document.getElementById('msg-error').innerHTML = msgError;
                // Je change la couleur du message d'erreur
                document.getElementById('msg-error').classList.add(<?php echo json_encode($erreursConfirmMdp[$codeConfirmMdp][1]); ?>);
                document.getElementById('msg-error').classList.add('visible');
                // Après l'expiration du cookie, on actualise la page pour le supprimer
                setTimeout(function () {
                    window.location.href = 'change_password.php';
                }, 10000);
            }
        </script>
    </body>
</html>