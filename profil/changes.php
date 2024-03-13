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
        <!--<div class="modal fade text-center" id="historiqueModal" aria-labelledby="historiqueModalLabel" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content glassmorphism">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 w-100" id="historiqueModalLabel">Historique du jeu Semonkey</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php /*include("historique.php"); */?>
                    </div>
                </div>
            </div>
        </div>-->
        <div class="modal fade text-center" id="emailModal" aria-labelledby="emailModalLabel" tabindex="-1" style="display: none" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"> <!-- Mettre la classe pour le background ici -->
                <div class="modal-content glassmorphism"> <!-- Mettre la classe pour le background ici -->
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 w-100" id="emailModalLabel">Changer l'adresse email</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="script-email.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" placeholder="Ancienne adresse email" name="email1" id="email1" required>
                                <label for="email1">Ancienne adresse email :</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" placeholder="Nouvelle adresse email" name="email2" id="email2" required>
                                <label for="email2">Nouvelle adresse email :</label>
                            </div>
                            <button id="formButtonEmail" type="submit" class="btn btn-outline-secondary fw-semibold">Valider</button>
                        </form>
                    </div>
                    <?php if ($codeEmail > 0 && $codeEmail < 6) { ?>
                        <div class="modal-footer">
                            <div id="msgEmail" class="alert w-100 <?php echo $erreursEmail[$codeEmail][1]; ?>" role="alert">
                                <?php echo $erreursEmail[$codeEmail][0]; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="modal fade text-center" id="mdpModal" aria-labelledby="mdpModalLabel" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content glassmorphism">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 w-100" id="mdpModalLabel">Changer le mot de passe</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="script-password.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" placeholder="Ancien mot de passe" id="password1" name="password1" required>
                                <label for="password1">Ancien mot de passe :</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" placeholder="Nouveau mot de passe" id="password2" name="password2" required>
                                <label for="password2">Nouveau mot de passe :</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" placeholder="Confirmer le nouveau mot de passe" id="password3" name="password3" required>
                                <label for="password3">Confirmer le nouveau mot de passe :</label>
                            </div>
                            <button id="formButtonMdp" type="submit" class="btn btn-outline-secondary fw-semibold">Valider</button>
                        </form>
                    </div>
                    <?php if ($codeMdp > 0 && $codeMdp < 6) { ?>
                        <div class="modal-footer text-center">
                            <div id="msgMdp" class="alert w-100 <?php echo $erreursMdp[$codeMdp][1]; ?>" role="alert">
                                <?php echo $erreursMdp[$codeMdp][0]; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="modal fade text-center" id="photoModal" aria-labelledby="photoModalLabel" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content glassmorphism">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 w-100" id="photoModalLabel">Changer la photo de profil</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="script-photo.php" method="POST" enctype="multipart/form-data">
                            <div class="input-group">
                                <input type="file" class="form-control" aria-describedby="formButtonPhoto" aria-label="Upload" id="photo" name="photo" required>
                                <button id="formButtonPhoto" type="submit" class="btn btn-outline-secondary fw-semibold">Valider</button>
                            </div>
                        </form>
                    </div>
                    <?php if ($codePhoto > 0 && $codePhoto < 6) { ?>
                        <div class="modal-footer text-center">
                            <div id="msgPhoto" class="alert w-100 <?php echo $erreursPhoto[$codePhoto][1]; ?>" role="alert">
                                <?php echo $erreursPhoto[$codePhoto][0]; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>
