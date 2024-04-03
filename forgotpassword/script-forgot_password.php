<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pseudo = $_POST['search'];
        $email = $_POST['email'];
        // Erreurs PHP
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        include_once("../includes/conf.php");
        include_once("../includes/fonctions.php");
        session_start();
        // Utilisateur connecté ?
        if (isset($_SESSION['user'])) {
            header("Location: ../");
            exit;
        }

        // Rechercher l'utilisateur dans la base de données
        $query_select_user = "SELECT * FROM sae_users WHERE search = :search AND email = :email";
        $stmt_select_user = $cnx->prepare($query_select_user);
        $stmt_select_user->bindParam(":search", $pseudo, PDO::PARAM_STR);
        $stmt_select_user->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt_select_user->execute();
        $user = $stmt_select_user->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Pseudo et email correspondent

            // Générer un code de réinitialisation
            $code_reinitialisation = bin2hex(random_bytes(16));

            // Enregistrement du code de réinitialisation dans la base de données
            $query_insert_code = "INSERT INTO sae_reset_code (num_user, code) VALUES (:num_user, :code)";
            $stmt_insert_code = $cnx->prepare($query_insert_code);
            $stmt_insert_code->bindParam(":num_user", $user['num_user']);
            $stmt_insert_code->bindParam(":code", $code_reinitialisation);
            $stmt_insert_code->execute();

            // Configurer PHPMailer
            include("../mail/mailer.php");

            $mail->addAddress($email, $pseudo);

            // Contenu du mail
            $mail->isHTML(true);
            $mail->Subject = "Réinitialisation de votre mot de passe";
            $mail->Body = getMailContent("../mail/forgotpassword.php");
            $mail->Body = str_replace(":search", $pseudo, $mail->Body);
            $mail->Body = str_replace(":lienReinitialisation", "$lienReinitialisation?code=$code_reinitialisation", $mail->Body);
            $mail->CharSet = "UTF-8";
            $mail->AddEmbeddedImage("../img/monkey.png", "mylogo", "monkey.png", "base64", "image/png");
            $mail->send();

            // Journalisation
            trace($user['num_user'], 3, $cnx);

            header('Location: ./?erreur=1');
            exit;
        }
    }
?>