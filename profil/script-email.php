<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldEmail = $_POST['email1'];
    $newEmail = $_POST['email2'];

    include_once("../class/User.php");
    include_once("../includes/conf.php"); // Connexion à la base de données
    session_start();

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION["user"])) {
        header('Location: ./');
        exit;
    }
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    $pseudo = $user->getPseudo();
    $email = $user->getEmail();

    // Vérifier si les deux emails sont identiques
    if ($oldEmail == $newEmail) {
        header('Location: ./?emailErreur=4');
        exit;
    }

    // Vérifier si l'ancien email est correct
    if ($email != $oldEmail) {
        header('Location: ./?emailErreur=2');
        exit;
    }

    // Vérifier si le nouvel email est déjà utilisé
    $sql = "SELECT email FROM sae_users WHERE email = :email";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(":email", $newEmail);
    $stmt->execute();
    $user = $stmt->fetch();
    $stmt->closeCursor();

    if ($user) {
        header('Location: ./?emailErreur=5');
        exit;
    }

    // Modifier l'email
    $sql = "UPDATE sae_users SET email = :email WHERE pseudo = :pseudo";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':email', $newEmail);
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->execute();
    $stmt->closeCursor();

    // Mail
    include("../mail/mailer.php");
    // Capture de la sortie de la page PHP dans une variable
    ob_start();
    include("../mail/connexion.php");
    $content = ob_get_clean();

    $mail->addAddress($newEmail);
    $mail->isHTML(true);
    $mail->Subject = "Changement d'email";
    $mail->Body = $content;
    $mail->CharSet = 'UTF-8';
    $mail->send();

    // Effacer les adresses précédentes
    $mail->clearAddresses();


    $mail->addAddress($oldEmail);
    $mail->isHTML(true);
    $mail->Subject = "Changement d'email";
    $mail->Body = $content;
    $mail->CharSet = 'UTF-8';
    $mail->send();

    // Journalisation
    $sql_num_user = "SELECT num_user FROM sae_users WHERE pseudo = :pseudo";
    $stmt_num_user = $cnx->prepare($sql_num_user);
    $stmt_num_user->execute(['pseudo' => $_SESSION['pseudo']]);
    $user_num_user = $stmt_num_user->fetch();
    $stmt_num_user->closeCursor();

    $num_user = $user_num_user['num_user'];

    include '../includes/fonctions.php';
    trace($num_user, "Changement d'adresse email", $cnx);

    header('Location: ./?emailErreur=1');
    exit;
}