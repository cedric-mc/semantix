<?php
    if (empty($_POST['sujet']) || empty($_POST['message'])) {
        echo "<script>alert('Veuillez remplir tous les champs.')</script>";
        echo "<script>window.location.replace('../contact.php')</script>";
        exit;
    }
    $sujet = $_POST['sujet'];
    $message = $_POST['message'];

    include_once('../class/User.php');
    include_once('../includes/conf.php');

    // Erreur PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: ../connexion/");
        exit;
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    // Ajout de l'expéditeur du message
    $message .= "<br><br>Envoyé par : " . $user->getPseudo() . " (" . $user->getEmail() . ")";

    // PHPMailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    // Configurer PHPMailer
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = $host;
    $mail->SMTPAuth = true; // Activer l'authentification SMTP
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($username, $name);
    $mail->addAddress($username, $name);
    $mail->addReplyTo($user->getEmail(), $user->getPseudo());

    $mail->isHTML(true);
    $mail->Subject = $sujet;
    $mail->Body = $message;
    $mail->AltBody = $message;
    $mail->CharSet = "UTF-8";

    // Envoi du mail
    $mail->send();
    echo "<script>alert('Message envoyé.')</script>";
    echo "<script>window.location.replace('../contact.php')</script>";
    exit;
?>