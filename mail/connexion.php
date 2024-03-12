<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Mail de Connexion - Semonkey</title>
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
            <h1>Alerte de Connexion</h1>
            <img src="cid:mylogo" alt="Semonkey" width="100px" height="100px">
        </header>
        <main>
            <p>
                Bonjour <?php echo $pseudo; ?>,
                <br><br>
                Vous venez de vous connecter sur notre site !
                Si vous n'êtes pas à l'origine de cette connexion, veuillez changer immédiatement votre mot de passe !
                <br><br>
                Cordialement,<br>
                L'Équipe de Semonkey.
            </p>
        </main>
        <footer>
            <p>© 2024 Semonkey. Tous droits réservés.</p>
        </footer>
    </body>
</html>