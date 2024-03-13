<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pseudo = $_POST['pseudo'];
        $email = $_POST['email'];

        include_once("../includes/conf.php");
        include_once("../includes/fonctions.php");
        session_start();
        // Utilisateur connecté ?
        if (isset($_SESSION['user'])) {
            header("Location: ../");
            exit;
        }
        $user = User::createUserFromUser(unserialize($_SESSION['user']));

        // Rechercher l'utilisateur dans la base de données
        $query_select_user = "SELECT * FROM sae_users WHERE pseudo = :pseudo AND email = :email";
        $stmt_select_user = $cnx->prepare($query_select_user);
        $stmt_select_user->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
        $stmt_select_user->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt_select_user->execute();
        $user = $stmt_select_user->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            //Pseudo et email correspondent

            //Générer un code de réinitialisation
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
            $mail->isHTML();
            $mail->Subject = "Réinitialisation de votre mot de passe";
            $mail->Body = getMailContent("../mail/forgotpassword.php");
            $mail->Body = str_replace(":pseudo", $pseudo, $mail->Body);
            $mail->CharSet = "UTF-8";
            $mail->AddEmbeddedImage("../img/monkey.png", "mylogo", "monkey.png", "base64", "image/png");
            $mail->send();

            // Journalisation
            $user->logging($cnx, 3);

            header('Location: ./?erreur=1');
            exit;
        }
    }
?>