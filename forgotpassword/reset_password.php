<?php
session_start();
// Messages d'erreurs possibles
$messagesErreur = [
    1 => ["Les mots de passes ne correspondent pas.", "alert-danger"],
    2 => ["Le mot de passe doit comporter au moins 12 caractères et contenir au minimum 1 lettre minuscule, 1 lettre majuscule, 1 chiffre et 1 caractère spécial.", "alert-danger"],
    3 => ["Une erreur est survenue lors de la mise à jour du mot de passe.", "alert-danger"]
];

// Récupérer le code d'erreur depuis l'URL
$codeErreur = isset($_GET['erreur']) ? (int)$_GET['erreur'] : 0;

if ($_GET['code']) {

    include '../includes/conf.php';

    $code_reinitialisation = $_GET['code'];

    // Rechercher le code de réinitialisation dans la base de données
    $query_select_code = "SELECT * FROM sae_reset_code WHERE code = :code";
    $stmt_select_code = $cnx->prepare($query_select_code);
    $stmt_select_code->bindParam(":code", $code_reinitialisation);
    $stmt_select_code->execute();
    $reset_code = $stmt_select_code->fetch(PDO::FETCH_ASSOC);

    if ($reset_code) {
        //Code de réinitialisation trouvé, afficher alors le formulaire
        ?>
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>Réinistialiser mot de passe - Semonkey</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                <link rel="stylesheet" href="../style/style.css">
                <link rel="stylesheet" type="text/css" href="../style/form.css">
                <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
                <?php include("../includes/head.php"); ?>
            </head>

            <body>
                <main class="glassmorphism">
                    <h1 class="title">Définissez un nouveau mot de passe :</h1>
                    <form action="traitement_reset_password.php" method="POST">
                        <input type="hidden" name="num_user" value="<?php echo $reset_code['num_user']; ?>">
                        <input type="hidden" name="code_reinitialisation"
                               value="<?php echo htmlspecialchars($code_reinitialisation); ?>">
                        <div class="input-field">
                            <input type="password" name="nouveau_mot_de_passe" id="nouveau_mot_de_passe" minlength="12"
                                   required>
                            <label for="nouveau_mot_de_passe">Nouveau mot de passe :</label>
                        </div>
                        <div class="input-field">
                            <input type="password" name="confirmer_nouveau_mot_de_passe" id="confirmer_nouveau_mot_de_passe"
                                   minlength="12" required>
                            <label for="confirmer_nouveau_mot_de_passe">Confirmer le nouveau mot de passe :</label>
                        </div>
                        <button id="formButton" type="submit" class="btn fw-semibold">Réinitialiser le mot de passe</button>
                    </form>
                    <?php
                        // Si le message d'erreur est différent de 0
                        if ($codeErreur > 0 && $codeErreur < 8) {
                            echo "<br><div id='msg-error' class='alert' role='alert'></div>";
                        }
                    ?>
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
                            window.location.href = '../forgotpassword/reset_password.php?code=<?php echo $code_reinitialisation; ?>';
                        }, 10000);
                    }
                </script>
            </body>
        </html>

        <?php

    } else {
        header('Location: ./');
        exit;
    }
} else {
    header('Location: ./');
    exit;
}
?>