<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Créer un compte</title>
    <link rel="icon" href="img/monkeyapp.png">

</head>

<div class="wrapper">
    <form action="creer.php" method="post">
        <h1>Créer un compte</h1>
                <div class="input-box">
                    <input placeholder="Pseudo" type="text" id="pseudo" name="pseudo" required />
                </div>

                <div class="input-box">
                <input placeholder="Email" type="email" id="email" name="email" required />
                </div>
                <div class="input-box">
                    <input placeholder="Confirmez votre e-mail" type="email" id="emailConf" name="emailConf" required />
                </div>
                <?php
                $maxDate = date('Y-m-d'); // Génère la date du jour au format YYYY-MM-DD
                ?>
                <div class="input-box">
                    <input placeholder="Année de naissance" type="date" id="annee" name="annee" required max="<?php echo $maxDate; ?>"/>
                </div>
                <div class="input-box">
                    <input placeholder="Mot de passe" type="password" id="mdp" name="mdp" required />
                </div>
                <div class="input-box">
                <input placeholder="Confirmez votre mot de passe:" type="password" id="mdpConf" name="mdpConf" required />
                </div>

                <button class ="btn" type="submit">Créer le compte</button>
        <div class="register-link"><a href="index.php">Retour</a></div>


    </form>
    <?php
    include('include/connexion.php');
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
        if ($ok) {
            $display = 'none';
        } else {
            $display = '';
        }
        // Fonction de vérification du mot de passe
        function verifierMotDePasse($mdp) {
            return preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-.]).{12,}$/', $mdp);
        }

        if (!verifierMotDePasse($mdp)) {
            $ok = false;
            echo "Mot de passe invalide. Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial et avoir au moins 12 caractères de long.";
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
                include('include/connexion_mail.php');

                $mail->isHTML(true);
                $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'Semonkey');
                $mail->addAddress($email, $pseudo);
                $mail->Subject = 'Validation de votre compte';
                $mail->Body = "Bonjour $pseudo,<br><br>Cliquez sur ce lien pour valider votre compte : <a href='https://perso-etudiant.u-pem.fr/~mamadou.ba2/projet/validation.php?token=$validation_token'>Valider le compte</a>";
                $mail->CharSet = 'utf-8';
                $mail->send();

                echo 'Un lien de vérification a été envoyé à votre e-mail : ' . $email;
                
                date_default_timezone_set('Europe/Paris');
                $date = date('Y-m-d H:i:s', strtotime('+3 minutes'));
                $stmt = $dbh->prepare("INSERT INTO validation (pseudo, email, annee, mdp, token, date_expir) VALUES (:pseudo, :email, :annee, :mdp, :validation_token, :date)");
                $stmt->bindParam(':pseudo', $pseudo);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':annee', $annee);
                $stmt->bindParam(':mdp', $mdpHash);
                $stmt->bindParam(':validation_token', $validation_token);
                $stmt->bindParam(':date', $date);
                $stmt->execute();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "<br>Il y a une erreur dans le formulaire.";
        }
    }
    ?>

</div>
</body>

</html>
