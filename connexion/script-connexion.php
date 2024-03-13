<?php
    include_once("../class/User.php");
    include_once("../includes/conf.php");
    include_once("../includes/fonctions.php");
    session_start();
    // erreur php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // Utilisateur déjà connecté ?
    if (isset($_SESSION['user'])) {
        header("Location: ../");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pseudo = $_POST["pseudo"];
        $motdepasse = $_POST["motdepasse"];

        // Rechercher l'utilisateur dans la base de données
        $query_select_user = "SELECT * FROM sae_users WHERE pseudo = :pseudo";
        $stmt_select_user = $cnx->prepare($query_select_user);
        $stmt_select_user->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
        $stmt_select_user->execute();
        $user = $stmt_select_user->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe
        if ($user) {
            $_SESSION['pseudo_temp'] = $pseudo;
            //Utilisateur à vérifié son email ?
            if($user['statut']==1) {
                // L'utilisateur existe, maintenant tu peux vérifier le mot de passe
                $stored_motdepasse = $user['motdepasse']; // Mot de passe stocké dans la base de données
                $stored_salt = $user['salt']; // Sel stocké dans la base de données

                // Recalculer le hachage avec le sel
                $hashed_input_motdepasse = hash_pbkdf2("sha256", $motdepasse, $stored_salt, 5000, 32);

                // Comparer les hachages
                if ($hashed_input_motdepasse === $stored_motdepasse) {
                    // Mot de passe correct
                    $idUser = intval($user['num_user']);
                    $email = $user['email'];
                    $year = $user['annee_naissance'];
                    $photo = $user['photo'];
                    if ($photo == null) {
                        $photo = "profil.webp";
                    }
                    $user = new User($idUser, $pseudo, $email, $year, $photo);
                    $_SESSION['user'] = serialize($user);

                    include("../mail/mailer.php");

                    // Capture de la sortie de la page PHP dans une variable
                    $content = getMailContent("../mail/connexion.php");

                    //Contenu du mail
                    $mail->isHTML();
                    $mail->Subject = "Connexion";
                    $mail->Body = $content;
                    $mail->Body = str_replace(":pseudo", $pseudo, $mail->Body);
                    $mail->CharSet = "UTF-8";
                    $mail->AddEmbeddedImage("../img/monkey.png", "mylogo", "monkey.png", "base64", "image/png");

                    $mail->addAddress($user->getEmail(), $user->getPseudo());
                    //Envoi du mail
                    $mail->send();

                    // Journalisation
                    $user->logging($cnx, 1);

                    header('Location: ../'); // Rediriger vers la page d'accueil
                    exit;
                } else {
                    // Mot de passe incorrect
                    header('Location: ./?erreur=2');
                    exit;
                }
            } else {
                // Demander à l'utilisateur de verifier son email
                include "../mail/envoyer_mail_token.php";
                header("Location: ./?erreur=7");
                exit;
            }
        } else {
            // L'utilisateur n'existe pas
            header("Location: ./?erreur=1");
            exit;
        }
    }
?>