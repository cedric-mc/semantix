<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $oldPassword = $_SESSION['password1'];
        $newPassword = $_SESSION['password2'];
        $confirmPassword = $_SESSION['password3'];

        include("../includes/conf.php");
        include_once("../class/User.php");
        include_once("../includes/fonctions.php");
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../');
            exit;
        }
        $user = User::createUserFromUser(unserialize($_SESSION['user']));
        $pseudo = $user->getPseudo();

        // Vérifier si le code correspond et n'a pas expiré
        $user_input_code = $_POST['verification_code'];
        $stored_code = $_SESSION['verification_code'];
        $code_time = $_SESSION['verification_time'];

        if ($user_input_code != $stored_code || (time() - $code_time) > 600) { // 600 secondes = 10 minutes
            header("Location: ./?erreurMdp=6");
            exit;
        }

        // Vérifier si l"ancien mot de passe est correct
        $sql = "SELECT num_user, motdepasse, salt, email FROM sae_users WHERE pseudo = :pseudo";
        $stmt = $cnx->prepare($sql);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();
        $user = $stmt->fetch();
        $stmt->closeCursor();

        //Pour la journalisation, on récupère le num_user
        $num_user = $user['num_user'];

        // L'utilisateur existe maintenant, tu peux vérifier le mot de passe
        $stored_motdepasse = $user['motdepasse']; // Mot de passe stocké dans la base de données
        $stored_salt = $user['salt']; // Sel stocké dans la base de données

        // Recalculer le hachage avec le sel
        $hashed_input_motdepasse = hash_pbkdf2("sha256", $oldPassword, $stored_salt, 5000, 32);

        // Comparer les mots de passe hachés
        if ($hashed_input_motdepasse === $stored_motdepasse) {
            // Vérifier si le nouveau mot de passe est différent de l'ancien
            if ($oldPassword === $newPassword) {
                header('Location: change_password.php?confirmMdpError=4');
                exit;
            }
            // Vérifier si les nouveaux mots de passe correspondent entre eux
            if ($newPassword !== $confirmPassword) {
                header('Location: change_password.php?confirmMdpError=3');
                exit;
            }
            // Vérifier si le nouveau mot de passe respecte les normes de la CNIL
            // 12 caractères minimum, au moins une majuscule, une minuscule, un chiffre et un caractère spécial
            if (strlen($newPassword) < 12 && !preg_match('/[0-9]/', $newPassword) && !preg_match('/[A-Z]/', $newPassword) && !preg_match('/[a-z]/', $newPassword) && !preg_match('/[^a-zA-Z0-9]/', $newPassword)) {
                header("Location: change_password.php?confirmMdpError=5");
                exit;
            }

            // Générer un nouveau sel
            $new_salt = random_bytes(16);
            // Hacher le mot de passe avec le nouveau sel
            $hashed_new_motdepasse = hash_pbkdf2("sha256", $newPassword, $new_salt, 5000, 32);

            // Mettre à jour le mot de passe et le sel dans la base de données
            $stmt = $cnx->prepare("UPDATE sae_users SET motdepasse = :motdepasse, salt = :salt WHERE pseudo = :pseudo");
            $stmt->bindParam(':motdepasse', $hashed_new_motdepasse);
            $stmt->bindParam(':salt', $new_salt);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->execute();
            $stmt->closeCursor();

            //Mail
            include '../mail/mailer.php';
            $mail->addAddress($user['email']);

            $mail->isHTML(true);
            $mail->Subject = "Changement de mot de passe";
            $mail->Body = getMailContent("../mail/change_password.php");
            $mail->Body = str_replace(":pseudo", $pseudo, $mail->Body);
            $mail->CharSet = "UTF-8";
            $mail->addEmbeddedImage("../img/monkey.png", "mylogo", "monkey.png", "base64", "image/png");
            $mail->send();

            // Journalisation
            trace($num_user, 5, $cnx);

            // Rediriger vers la page de connexion avec un message, mot de passe modifié
            header('Location: change_password.php?confirmMdpError=1');
        } else {
            // L'ancien mot de passe est incorrect
            header('Location: change_password.php?confirmMdpError=2');
        }
        exit;
    } else {
        header('Location: change_password.php');
        exit;
    }
?>
