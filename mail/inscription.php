<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Mail pour d'inscription</title>
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
            <h1>Inscription</h1>
            <img src="cid:mylogo" alt="Semantic Analogy Explorer" width="100px" height="100px">
        </header>
        <main>
            <p>
                Bienvenue :pseudo sur notre site !
                <br><br>
                Veuillez confirmer votre inscription en cliquant sur le lien suivant : <a href='<?php echo "$lienInscription?code=$code_confirmation"; ?>'>Confirmer</a>
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