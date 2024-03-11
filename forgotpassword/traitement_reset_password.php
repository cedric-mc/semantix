<?php
include_once("../includes/conf.php");
include_once("../class/User.php");
session_start();
// Utilisateur déjà connecté ?
if (isset($_SESSION['user'])) {
    header("Location: ../");
    exit;
}
$user = User::createUserFromUser(unserialize($_SESSION['user']));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_user = $_POST['num_user'];
    $nouveau_mot_de_passe = $_POST["nouveau_mot_de_passe"];
    $confirmer_mot_de_passe = $_POST["confirmer_nouveau_mot_de_passe"];
    $code_reinitialisation = $_POST['code_reinitialisation'] ?? null;


    // Vérifier si les mots de passe correspondent
    if ($nouveau_mot_de_passe === $confirmer_mot_de_passe) {
        //mot de passe conforme ? (> 12 caractères ; minuscule ; majuscule ; chiffre ; caractère special)
        if (!preg_match("/[0-9]/", $nouveau_mot_de_passe) || !preg_match("/[a-z]/", $nouveau_mot_de_passe) || !preg_match("/[A-Z]/", $nouveau_mot_de_passe) || !preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $nouveau_mot_de_passe)) {
            header('Location: reset_password.php?code=' . urlencode($code_reinitialisation) . '&erreur=2');
            exit;
        }

        // Vérifier si l'ancien mot de passe est correct
        $sql = "SELECT email, salt FROM sae_users WHERE num_user = :num_user";
        $stmt = $cnx->prepare($sql);
        $stmt->bindParam(':num_user', $num_user, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch();
        $stmt->closeCursor();

        $stored_salt = $user['salt']; // Sel stocké dans la base de données

        $motdepasse = hash_pbkdf2("sha256", $nouveau_mot_de_passe, $stored_salt, 5000, 32); // hachage du mot de passe

        // Mettre à jour le mot de passe dans la base de données
        $query_update_mot_de_passe = "UPDATE sae_users SET motdepasse = :mot_de_passe WHERE num_user = :num_user";
        $stmt_update_mot_de_passe = $cnx->prepare($query_update_mot_de_passe);
        $stmt_update_mot_de_passe->bindParam(":mot_de_passe", $motdepasse, PDO::PARAM_STR);
        $stmt_update_mot_de_passe->bindParam(":num_user", $num_user, PDO::PARAM_INT);


        if ($stmt_update_mot_de_passe->execute()) {
            // Supprimer le code de réinitialisation utilisé
            $query_delete_code = "DELETE FROM sae_reset_code WHERE num_user = :num_user";
            $stmt_delete_code = $cnx->prepare($query_delete_code);
            $stmt_delete_code->bindParam(":num_user", $num_user, PDO::PARAM_INT);
            $stmt_delete_code->execute();

            //Mail
            include '../mail/mailer.php';

            $mail->addAddress($user['email']);

            $mail->isHTML(true);
            $mail->Subject = "Changement de mot de passe";
            $mail->Body =
                "Bonjour " . $user->getPseudo() . ",<br>
                    Votre mot de passe a été modifié. Si vous n'êtes pas à l'origine de cette modification, veuillez contacter l'administrateur du site.<br>
                    Cordialement,<br>
                    L'équipe de Semantic Analogy Explorer.";
            $mail->CharSet = 'UTF-8';

            $mail->send();

            // Journalisation
            $user->logging($cnx, 5);

            header('Location: forgot_password.php?erreur=2');
            exit;
        } else {
            // Erreur lors de la mise à jour du mot de passe
            header('Location: reset_password.php?code=' . urlencode($code_reinitialisation) . '&erreur=3');
            exit;
        }
    } else {
        // Les mots de passe ne correspondent pas
        header('Location: reset_password.php?code=' . urlencode($code_reinitialisation) . '&erreur=1');
        exit;
    }
} else {
    header('Location: forgot_password.php');
    exit;
}
?>