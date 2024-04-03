<?php
session_start();
// Utilisateur connecté ?
if (isset($_SESSION['user'])) {
    header("Location: ../");
    exit;
}

// Messages d'erreurs possibles
$messagesErreur = [
    1 => ["Le pseudo est déjà pris.", "alert-danger"],
    2 => ["L'email est déjà utilisé.", "alert-danger"],
    3 => ["Les mots de passes ne correspondent pas.", "alert-danger"],
    4 => ["Le mot de passe doit comporter au moins 12 caractères et contenir au minimum 1 lettre minuscule, 1 lettre majuscule, 1 chiffre et 1 caractère spécial.", "alert-danger"],
    5 => ["Votre inscription a bien été prise en compte. Vous allez reçevoir un mail afin de confirmer votre inscription.", "alert-warning"],
    6 => ["L'année de naissance doit être comprise entre 1930 et 2017.", "alert-danger"]
];

// Récupérer le code d’erreur depuis l'URL
$codeErreur = isset($_GET['erreur']) ? intval($_GET['erreur']) : 0;
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
		<meta charset="UTF-8">
		<title>Inscription - Semonkey</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="../style/form.css">
        <link rel="icon shortcut" href="../img/monkey.png">
        <?php include("../includes/head.php"); ?>
    </head>

    <body class="black">
        <main class="position-absolute top-50 start-50 translate-middle">
            <div class="glassmorphism">
                <form action="script-inscription.php" method="POST">
                    <h1 class="title">Inscription</h1>
                    <div class="input-field">
                        <input name="pseudo" type="text" id="pseudo" required>
                        <label for="pseudo">Pseudo</label>
                    </div>
                    <div class="input-field">
                        <input name="email" type="email" id="email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="input-field">
                        <input name="annee_naissance" type="number" id="annee_naissance" required>
                        <label for="annee_naissance">Année de Naissance</label>
                    </div>
                    <div class="input-field">
                        <input name="motdepasse1" id="motdepasse1" type="password" required>
                        <label for="motdepasse1">Mot de passe</label>
                    </div>
                    <div class="input-field">
                        <input name="motdepasse2" id="motdepasse2" type="password" required>
                        <label for="motdepasse2">Confirmer le mot de passe</label>
                    </div>
                    <button id="formButton" type="submit" class="btn fw-semibold">S'inscrire</button>
                    <div class="register">
                        <p>Déjà inscrit ? <a href="../">Connectez-vous.</a></p>
                    </div>
                </form>
                <?php
                // Si le message d'erreur est différent de 0 et inférieur à 7
                if ($codeErreur > 0 && $codeErreur < 7) {
                    echo "<div id='msg-error' class='alert' role='alert'></div>";
                }
                ?>
            </div>
        </main>
        <script>
            // Je récupère le message d’erreur
            let msgCode = <?php echo json_encode($codeErreur); ?>;
            let msgError = <?php echo json_encode($messagesErreur[$codeErreur][0]); ?>;
            // Si le message d’erreur est différent de 0
            if (msgCode > 0 && msgCode < 7) {
                // J'affiche le message d'erreur
                document.getElementById('msg-error').innerHTML = msgError;
                // Je change la couleur du message d'erreur
                document.getElementById('msg-error').classList.add(<?php echo json_encode($messagesErreur[$codeErreur][1]); ?>);
                document.getElementById('msg-error').classList.add('visible');
                // Je rafraîchis la page au bout de 10s pour supprimer la variable get erreur
                setTimeout(function () {
                    window.location.href = '';
                }, 10000);
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>