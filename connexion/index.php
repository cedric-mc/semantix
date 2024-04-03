<?php
session_start();
// Utilisateur connecté ?
if (isset($_SESSION['pseudo'])) {
    header('Location: ../');
    exit;
}

// Messages d’erreurs possibles
$messagesErreur = [
    1 => ["L'utilisateur n'existe pas.", "alert-danger"],
    2 => ["Le mot de passe est incorrect.", "alert-danger"],
    3 => ["Le token a expiré, connectez-vous pour pouvoir en re-générer un.", "alert-danger"],
    4 => ["Votre adresse email a bien été confirmé !\nVous pouvez désormais vous connecter.", "alert-success"],
    5 => ["L'utilisateur a déjà été confirmé.", "alert-danger"],
    6 => ["Déconnexion réussie !", "alert-success"],
    7 => ["Votre inscription n’étant pas confirmé.\nVous allez recevoir un email afin de confirmer votre inscription.", "alert-warning"]
];
// Récupérer le code d’erreur depuis l'URL
$codeErreur = isset($_GET['erreur']) ? (int)$_GET['erreur'] : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Connexion - Semonkey</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../style/style.css">
	<link rel="stylesheet" href="../style/form.css">
    <link rel="shortcut icon" href="../img/monkey.png">
    <?php include("../includes/head.php"); ?>
</head>

<body>
<main>
	<div class="glassmorphism form-connexion">
		<h1 class="title">Connexion</h1>
		<form action="script-connexion.php" method="POST">
			<div class="input-field">
				<input name="pseudo" type="text" id="pseudo" required> <label for="pseudo">Pseudo</label>
			</div>
			<div class="input-field">
				<input name="motdepasse" id="motdepasse" type="password" required> <label for="motdepasse">Mot de passe</label>
			</div>
			<div class="forget">
				<a href="../forgotpassword/">Mot de passe oublié ?</a>
			</div>
			<button id="formButton" type="submit" class="btn fw-semibold">Se connecter</button>
			<div class="register">
				<p>
                    Pas encore de compte ? <a href="../inscription/">Inscrivez-vous.</a>
                </p>
			</div>
		</form>
        <?php
        // Si le message d'erreur est différent de 0
        if ($codeErreur > 0 && $codeErreur < 8) {
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
    if (msgCode > 0 && msgCode < 8) {
        // J'affiche le message d'erreur
        document.getElementById('msg-error').innerHTML = msgError;
        // Je change la couleur du message d'erreur
        document.getElementById('msg-error').classList.add(<?php echo json_encode($messagesErreur[$codeErreur][1]); ?>);
        // Après l'expiration du cookie, on actualise la page pour le supprimer
        setTimeout(function () {
            window.location.href = './';
        }, 10000);
    }
</script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>