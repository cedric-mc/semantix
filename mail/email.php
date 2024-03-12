<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Mail pour changement d'email</title>
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
            <h1>Changement d'email</h1>
            <img src="cid:mylogo" alt="Semantic Analogy Explorer" width="100px" height="100px">
        </header>
        <main>
            <p>
                Bonjour <?php echo $_SESSION['pseudo']; ?>,
                <br><br>
                Votre adresse e-mail a été modifiée.
                Si vous n'êtes pas à l'origine de cette modification, veuillez contacter l'administrateur du site.
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