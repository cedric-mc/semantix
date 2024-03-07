<?php
    include_once("../class/User.php");
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: ./');
        exit;
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));

    // Messages d’erreurs possibles
    $erreursEmail = [
        1 => ["L'adresse e-mail a bien été modifiée.", "alert-success"],
        2 => ["L'ancienne adresse e-mail est incorrecte.", "alert-danger"],
        3 => ["La nouvelle adresse e-mail est incorrecte.", "alert-danger"],
        4 => ["Les deux adresses e-mail sont identiques.", "alert-danger"],
        5 => ["La nouvelle adresse e-mail est déjà utilisée par un autre utilisateur.", "alert-danger"]
    ];

    // Récupérer le code d’erreur depuis l'URL
    $codeErreur = isset($_GET['emailErreur']) ? (int)$_GET['emailErreur'] : 0;

    $menu = 1;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Changer Email - Semantic Analogy Explorer</title>
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
            <h1 class="title">Changer l'adresse email</h1>
            <form action="script-email.php" method="POST">
                <div class="input-field">
                    <input type="email" name="email1" id="email1" required>
                    <label for="email1">Ancienne adresse email :</label>
                </div>
                <div class="input-field">
                    <input type="email" name="email2" id="email2" required>
                    <label for="email2">Nouvelle adresse email :</label>
                </div>
                <button id="formButton" type="submit" class="btn fw-semibold">Valider</button>
            </form>
            <?php
                // Si le message d'erreur est différent de 0
                if ($codeErreur > 0 && $codeErreur < 6) {
                    echo "<br><div id='msg-error' class='alert' role='alert'></div>";
                }
            ?>
        </main>
        <script>
            // Je récupère le message d’erreur
            let msgCode = <?php echo json_encode($codeErreur); ?>;
            let msgError = <?php echo json_encode($erreursEmail[$codeErreur][0]); ?>;
            // Si le message d’erreur est différent de 0
            if (msgCode > 0 && msgCode < 6) {
                // J'affiche le message d'erreur
                document.getElementById('msg-error').innerHTML = msgError;
                // Je change la couleur du message d'erreur
                document.getElementById('msg-error').classList.add(<?php echo json_encode($erreursEmail[$codeErreur][1]); ?>);
                document.getElementById('msg-error').classList.add('visible');
                // Après l'expiration du cookie, on actualise la page pour le supprimer
                setTimeout(function () {
                    window.location.href = 'change_email.php';
                }, 10000);
            }
        </script>
    </body>
</html>