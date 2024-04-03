<?php
    include_once("../includes/fonctions.php");
    include_once("../includes/conf.php");
    session_start();
    if (!isset($_SESSION['pseudo_temp'])) {
        header('Location: ../');
        exit;
    }
    $pseudo = $_SESSION['pseudo_temp'];

    //requête récuperer le num_user de l'utilisateur
    $queryGetNum = "SELECT num_user FROM sae_users WHERE search = :search";
    $stmtGetNum = $cnx->prepare($queryGetNum);
    $stmtGetNum->bindParam(":search", $pseudo, PDO::PARAM_STR);
    $stmtGetNum->execute();

    // Récupère le résultat
    $num_user = $stmtGetNum->fetchColumn();

    // Génération d'un code de confirmation (peut être un jeton unique)
    $code_confirmation = bin2hex(random_bytes(16));

    // Enregistrement du code de confirmation dans la base de données
    $query_insert_confirmation = "INSERT INTO sae_confirmation_codes (num_user, code) VALUES (:num_user, :code)";
    $stmt_insert_confirmation = $cnx->prepare($query_insert_confirmation);
    $stmt_insert_confirmation->bindParam(":num_user", $num_user, PDO::PARAM_INT);
    $stmt_insert_confirmation->bindParam(":code", $code_confirmation);
    $stmt_insert_confirmation->execute();

    //requête récuperer le mail de l'utilisateur
    $queryGetMail = "SELECT email FROM sae_users WHERE search = :search;";
    $stmtGetmail = $cnx->prepare($queryGetMail);
    $stmtGetmail->bindParam(":search", $pseudo, PDO::PARAM_STR);
    $stmtGetmail->execute();
    // Récupère le résultat
    $email = $stmtGetmail->fetchColumn();

    include("mailer.php");

    $mail->addAddress($email, $pseudo);

    // Contenu du mail
    $mail->isHTML(true);
    $mail->Subject = "Confirmation d'inscription";
    $mail->Body = getMailContent("../mail/inscription.php");
    $mail->Body = str_replace(":search", $pseudo, $mail->Body);
    $mail->Body = str_replace(":lienInscription", "$lienInscription?code=$code_confirmation", $mail->Body);
    $mail->CharSet = "UTF-8";
    $mail->AddEmbeddedImage("../img/monkey.png", "mylogo", "monkey.png", "base64", "image/png");
    $mail->send();
?>