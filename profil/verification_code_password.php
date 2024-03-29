<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $password3 = $_POST['password3'];

    // Erreurs PHP
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include_once("../includes/conf.php");
    include_once("../class/Game.php");
    include_once("../class/User.php");
    include_once("../includes/requetes.php");
    include_once("../includes/fonctions.php");
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: ./");
        exit;
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    // Vérifier si les nouveaux mots de passe sont identiques
    if ($password2 != $password3) {
        header("Location: ./?erreurMdp=3");
    }
    // Vérifier si l'ancien mot de passe correspond au nouveau
    if ($password1 == $password2) {
        header("Location: ./?erreurMdp=4");
        exit;
    }

    $pseudo = $user->getPseudo();
    $email = $user->getEmail();
    // Vérifier si l'ancien mot de passe est correct
    $request = $cnx->prepare($isPasswordCorrect);
    $request->bindParam(":pseudo", $pseudo);
    $request->execute();
    $result = $request->fetch();
    $hashPassword = hash_pbkdf2("sha256", $password1, $result['salt'], 5000, 32);
    if ($hashPassword != $result['motdepasse']) { // Mot de passe incorrect
        header("Location: ./?erreurMdp=2");
        exit;
    }

    // Vérifier si le nouveau mot de passe correspond aux normes de la CNIL
    if (strlen($password2) < 12 && !preg_match('/[0-9]/', $password2) && !preg_match('/[A-Z]/', $password2) && !preg_match('/[a-z]/', $password2) && !preg_match('/[^a-zA-Z0-9]/', $password2)) {
        header("Location: ./?erreurMdp=5");
        exit;
    }

    $verification_code = random_int(100000, 999999); // Générer un code à 6 chiffres

    $_SESSION['verification_code'] = $verification_code; // Stocker le code dans la session
    $_SESSION['verification_time'] = time(); // Stocker le temps de la demande
    $_SESSION['password2'] = $password2; // Stocker le nouveau mot de passe dans la session

    // Envoyer le code par email
    include("../mail/mailer.php");

    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Votre Code de Vérification";
    $mail->Body = str_replace(":pseudo", $pseudo, getMailContent("../mail/motdepasse.php"));
    $mail->Body = str_replace(":code", $verification_code, $mail->Body); // Remplacer le code dans le mail
    $mail->CharSet = "UTF-8";
    $mail->AddEmbeddedImage("../img/monkey.png", "mylogo", "monkey.png", "base64", "image/png");
    $mail->send();

    $user->logging($cnx, 11); // Journalisation

    header("Location: confirm_change_password.php"); // Rediriger l'utilisateur vers la page de vérification du code
} else {
    header("Location:./");
    exit;
}
?>
