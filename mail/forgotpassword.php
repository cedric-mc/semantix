<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Mail pour réinitialisation de mot de passe</title>
        <style>
            header {
                background-color: #f3f3f3;
                padding: 20px;
                text-align: center;
            }

            footer {
                background-color: #f3f3f3;
                padding: 20px;
                text-align: center;
            }

            img {
                width: 6.25rem;
                height: auto;
                margin: 20px;
            }

            p {
                font-size: 20px;
                text-align: center;
            }
        </style>
    </head>

    <body>
        <header>
            <h1>Réinitialisation de mot de passe</h1>
            <img src="cid:mylogo" alt="Semantic Analogy Explorer" width="100px" height="100px">
        </header>
        <main>
            <p>
                Bonjour <?php echo $_SESSION['pseudo']; ?>,
                <br><br>
                Vous avez demandé la réinitialisation de votre mot de passe.
                Veuillez cliquer sur le lien suivant pour choisir un nouveau mot de passe : <a href="<?php echo $lienReinitialisation . '?code=' . $code_reinitialisation; ?>">Réinitialiser le mot de passe</a>
                <br><br>
                Cordialement,<br>
                L'équipe de Semonkey.
            </p>
        </main>
        <footer>
            <p>© 2024 Semonkey. Tous droits réservés.</p>
        </footer>
    </body>
</html>