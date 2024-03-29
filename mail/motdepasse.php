<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Mail de demande de changement de mot de passe</title>
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
            <h1>Demande de changement de mot de passe</h1>
            <img src="cid:mylogo" alt="Semantic Analogy Explorer" width="100px" height="100px">
        </header>
        <main>
            <p>
                Bonjour :pseudo,
                <br><br>
                Votre code de vérification pour changer votre mot de passe est : :code.
                <br><br>
                Si vous n'avez pas demandé de changement de mot de passe, veuillez ignorer ce message.
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