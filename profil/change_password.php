<?php
    $menu = 1;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Changer Mot de passe - Semonkey</title>
        <!-- <link rel="stylesheet" href="../style/form.css"> -->
        <style>
            h1.title, label {
                color: black;
            }

            button {
                background: #000;
                color: #fff;
            }

            button:hover {
                background: #000;
                color: #000;
            }
        </style>
    </head>

    <body>
        <main class="glassmorphism no-glassmorphism">
            <h1 class="title">Changer le mot de passe</h1>
            <form action="script-password.php" method="POST">
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