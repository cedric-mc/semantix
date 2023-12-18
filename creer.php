<!DOCTYPE html>
<html>

<head>
    <title>Créer un compte</title>
</head>

<body>
    <p>Créer un compte</p>
    <form action="creer.php" method="post">
        <ul>
            <li>
                <label for="pseudo">Pseudo: </label>
                <input type="text" id="pseudo" name="pseudo" required />
            </li>
            <li>
                <label for="email">E-mail: </label>
                <input type="email" id="email" name="email" required />
            </li>
            <li>
                <label for="emailConf">Confirmez votre e-mail: </label>
                <input type="email" id="emailConf" name="emailConf" required />
            </li>
            <li>
                <label for="annee">Année de naissance: </label>
                <input type="number" id="annee" name="annee" required />
            </li>
            <li>
                <label for="mdp">Mot de passe: </label>
                <input type="password" id="mdp" name="mdp" required />
            </li>
            <li>
                <label for="mdpConf">Confirmez votre mot de passe: </label>
                <input type="password" id="mdpConf" name="mdpConf" required />
            </li>
            <div class="button">
                <button type="submit">Créer le compte</button>
            </div>
        </ul>
    </form>

    <?php
    include('connexion.php');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo = $_POST['pseudo'];
        $email = $_POST['email'];
        $emailConf = $_POST['emailConf'];
        $annee = $_POST['annee'];
        $mdp = $_POST['mdp'];
        $mdpConf = $_POST['mdpConf'];

        $ok = true;

        // Fonction de vérification du mot de passe
        function verifierMotDePasse($mdp)
        {
            // Vérifie si le mot de passe contient au moins 1 majuscule, 1 minuscule,
            // 1 chiffre, 1 caractère spécial et a au moins 7 caractères de longueur
            $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,}$/";

            // Utilisez la fonction preg_match() pour vérifier le motif
            return preg_match($pattern, $mdp);
        }

        if (!verifierMotDePasse($mdp)) {
            $ok = false;
            echo "Mot de passe invalide. Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial et avoir au moins 7 caractères de long.";
        }

        $mdpHash = password_hash($mdp, PASSWORD_BCRYPT);

        if ($email != $emailConf || $mdp != $mdpConf) {
            $ok = false;
            echo "<br>Les champs d'email ou de mot de passe ne correspondent pas.";
        }

        $stmt = $dbh->prepare("SELECT pseudo FROM user WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();

        if ($stmt->fetch()) {
            $ok = false;
            echo "<br>Ce pseudo existe déjà, choisissez-en un autre.";
        }

        if ($ok) {
            $validation_token = md5(uniqid(rand(), true));

            try {
                include('connexion_mail.php');

                $mail->isHTML(true);
                $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'Mamadou');
                $mail->addAddress($email, $pseudo);
                $mail->Subject = 'Validation de votre compte';
                $mail->Body = "Bonjour $pseudo,<br><br>Cliquez sur ce lien pour valider votre compte : <a href='https://perso-etudiant.u-pem.fr/~mamadou.ba2/projet/validation.php?token=$validation_token'>Valider le compte</a>";
                $mail->CharSet = 'utf-8';
                $mail->send();

                echo 'Un lien de vérification a été envoyé à votre e-mail : ' . $email;

                $stmt = $dbh->prepare("INSERT INTO validation (pseudo, email, annee, mdp, token) VALUES (:pseudo, :email, :annee, :mdp, :validation_token)");
                $stmt->bindParam(':pseudo', $pseudo);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':annee', $annee);
                $stmt->bindParam(':mdp', $mdpHash);
                $stmt->bindParam(':validation_token', $validation_token);
                $stmt->execute();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "<br>Il y a une erreur dans le formulaire.";
        }
    }
    ?>

    <form action="index.php">
        <button type="submit">Retour</button>
    </form>
</body>

</html>
