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

    // PHPMailer
    require "../PHPMailer/src/PHPMailer.php";
    require "../PHPMailer/src/SMTP.php";
    require "../PHPMailer/src/Exception.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    // Configurer PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $host;
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';

    $mail->SMTPAuth = true; // Activer l'authentification SMTP
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($user->getEmail(), $user->getPseudo());
    $mail->isHTML(true);
    $mail->Subject = $sujet;
    $mail->Body = $message;
    $mail->CharSet = "UTF-8";
    $mail->addAddress($username, $name);


    try {
        // Envoi du mail
        $mail->send();
        echo "<script>alert('Message envoyé.')</script>";
        echo "<script>window.location.replace('../contact.php')</script>";
        exit;
    } catch (Exception $e) {
        // Enregistrement du message d'erreur dans un fichier log
        file_put_contents('error_log.txt', 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . "\n", FILE_APPEND);
        echo "<script>alert('Message could not be sent. Check the error log for more details.')</script>";
        echo "<script>window.location.replace('../contact.php')</script>";
        exit;
    }
?>