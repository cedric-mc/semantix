<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $oldPassword = $_SESSION['password1'];
        $newPassword = $_SESSION['password2'];
        $confirmPassword = $_SESSION['password3'];

        // Erreurs PHP
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        include_once("../includes/conf.php");
        include_once("../class/User.php");
        include_once("../includes/requetes.php");
        include_once("../includes/fonctions.php");
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../');
            exit;
        }
        $user = User::createUserFromUser(unserialize($_SESSION['user']));
        $pseudo = $user->getPseudo();
        $email = $user->getEmail();

        // Vérifier si le code correspond et n'a pas expiré
        $enterCode = $_POST['verification_code'];
        $realCode = $_SESSION['verification_code'];
        $codeTime = $_SESSION['verification_time'];

        if ($enterCode != $realCode || (time() - $codeTime) > 600) { // 600 secondes = 10 minutes
            header("Location: ./?erreurMdp=6");
            exit;
        }
        // Générer un nouveau sel
        $new_salt = random_bytes(16);
        // Hacher le mot de passe avec le nouveau sel
        $hashed_new_motdepasse = hash_pbkdf2("sha256", $newPassword, $new_salt, 5000, 32);

        // Mettre à jour le mot de passe et le sel dans la base de données
        $stmt = $cnx->prepare($changePassword);
        $stmt->bindParam(':motdepasse', $hashed_new_motdepasse);
        $stmt->bindParam(':salt', $new_salt);
        $stmt->bindParam(':search', $pseudo);
        $stmt->execute();
        $stmt->closeCursor();

        //Mail
        include '../mail/mailer.php';
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Changement de mot de passe";
        $mail->Body = str_replace(":search", $pseudo, getMailContent("../mail/password.php"));
        $mail->CharSet = "UTF-8";
        $mail->addEmbeddedImage("../img/monkey.png", "mylogo", "monkey.png", "base64", "image/png");
        $mail->send();

        $user->logging($cnx, 5); // Journalisation

        // Rediriger vers la page de connexion avec un message, mot de passe modifié
        header("Location: ./?erreurMdp=1");
        exit;
    } else {
        header('Location: ./');
        exit;
    }
?>
