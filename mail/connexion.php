<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Mail de Connexion - Semonkey</title>
        <style>
            p {
                font-size: 20px;
                text-align: center;
                margin-top: 20%;
            }
        </style>
    </head>

    <body>
        <header>
            <h1>Alerte de Connexion</h1>
            <img src="cid:mylogo" alt="Semonkey" width="100px" height="100px">
        </header>
        <p>
            Bonjour <?php echo $pseudo; ?>, Vous venez de vous connecter sur notre site !
            <br>
            <br>
            Si vous n'êtes pas à l'origine de cette connexion, veuillez changer immédiatement votre mot de passe !
        </p>
    </body>
</html>