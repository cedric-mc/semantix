<?php
    global $erreursEmail, $codeEmail, $erreursMdp, $codeMdp, $codePhoto, $erreursPhoto;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Changer - Semonkey</title>
    </head>

    <body>
        <div class="modal fade" id="emailModal" aria-labelledby="emailModalLabel" tabindex="-1" style="display: none; text-align-all: center" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 text-center" id="emailModalLabel">Changer l'adresse email</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="script-email.php" method="POST">
                            <div class="input-field">
                                <label for="email1">Ancienne adresse email :</label>
                                <input type="email" name="email1" id="email1" required>
                            </div>
                            <div class="input-field">
                                <label for="email2">Nouvelle adresse email :</label>
                                <input type="email" name="email2" id="email2" required>
                            </div>
                            <button id="formButton" type="submit" class="btn btn-outline-primary fw-semibold">Valider</button>
                        </form>
                    </div>
                    <?php if ($codeEmail > 0 && $codeEmail < 6) { ?>
                        <div class="modal-footer text-center">
                            <div id="msgEmail" class="alert <?php echo $erreursEmail[$codeEmail][1]; ?>" role="alert">
                                <?php echo $erreursEmail[$codeEmail][0]; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="modal fade" id="mdpModal" aria-labelledby="mdpModalLabel" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mdpModalLabel">Changer le mot de passe</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="script-password.php" method="POST">
                            <div class="input-field">
                                <label for="password1">Ancien mot de passe :</label>
                                <input type="password" id="password1" name="password1" required>
                            </div>
                            <div class="input-field">
                                <label for="password2">Nouveau mot de passe :</label>
                                <input type="password" id="password2" name="password2" required>
                            </div>
                            <div class="input-field">
                                <label for="password3">Confirmer le nouveau mot de passe :</label>
                                <input type="password" id="password3" name="password3" required>
                            </div>
                            <button id="formButton" type="submit" class="btn btn-outline-primary fw-semibold">Valider</button>
                        </form>
                    </div>
                    <?php if ($codeMdp > 0 && $codeMdp < 6) { ?>
                        <div class="modal-footer text-center">
                            <div id="msgMdp" class="alert <?php echo $erreursMdp[$codeMdp][1]; ?>" role="alert">
                                <?php echo $erreursMdp[$codeMdp][0]; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="modal fade" id="photoModal" aria-labelledby="photoModalLabel" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="photoModalLabel">Changer la photo de profil</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="script-photo.php" method="POST" enctype="multipart/form-data">
                            <div class="input-field">
                                <input type="file" id="photo" name="photo" required>
                            </div>
                            <button id="formButton" type="submit" class="btn btn-outline-primary fw-semibold">Valider</button>
                        </form>
                    </div>
                    <?php if ($codePhoto > 0 && $codePhoto < 6) { ?>
                        <div class="modal-footer text-center">
                            <div id="msgPhoto" class="alert <?php echo $erreursPhoto[$codePhoto][1]; ?>" role="alert">
                                <?php echo $erreursPhoto[$codePhoto][0]; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>
