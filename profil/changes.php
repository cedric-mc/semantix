<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Changer - Semonkey</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            h1.title, label {
                color: black;
            }

            button {
                background: #000;
                color: #fff;
            }

            button:hover {
                background: #000;
                color: #000;
            }
        </style>
    </head>

    <body>
        <div class="modal fade" id="emailModal" aria-labelledby="emailModalLabel" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="emailModalLabel">Changer l'adresse email</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="script-email.php" method="POST">
                            <div class="input-field">
                                <input type="email" name="email1" id="email1" required>
                                <label for="email1">Ancienne adresse email :</label>
                            </div>
                            <div class="input-field">
                                <input type="email" name="email2" id="email2" required>
                                <label for="email2">Nouvelle adresse email :</label>
                            </div>
                            <button id="formButton" type="submit" class="btn fw-semibold">Valider</button>
                        </form>
                    </div>
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
                                <input type="password" id="password1" name="password1" required>
                                <label for="password1">Ancien mot de passe :</label>
                            </div>
                            <div class="input-field">
                                <input type="password" id="password2" name="password2" required>
                                <label for="password2">Nouveau mot de passe :</label>
                            </div>
                            <div class="input-field">
                                <input type="password" id="password3" name="password3" required>
                                <label for="password3">Confirmer le nouveau mot de passe :</label>
                            </div>
                            <button id="formButton" type="submit" class="btn fw-semibold">Valider</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="photoModal" aria-labelledby="photoModalLabel" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="photoModalLabel">Modal 3</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="script-photo.php" method="POST" enctype="multipart/form-data">
                            <div class="input-field">
                                <input type="file" id="photo" name="photo" required>
                            </div>
                            <button id="formButton" type="submit" class="btn fw-semibold">Valider</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>