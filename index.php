<?php
include('connexion.php');
session_start();

if (isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
    header('Location: compte.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>

<body>

    <h1>Se connecter</h1>

    <form action="" method="post">
        <ul>
            <li>
                <label for="pseudo">Pseudo: </label>
                <input type="text" id="pseudo" name="pseudo" />
            </li>
            <li>
                <label for="mdp">Mot de passe: </label>
                <input type="password" id="mdp" name="mdp" />
            </li>
            <div class="button">
                <button type="submit">Se connecter</button>
            </div>
        </ul>
    </form>

    <a href="creer.php"> Si vous n'avez pas de compte créez-en un</a><br>
    <a href="recup.php"> Si vous avez oublié votre mot de passe, cliquez sur ce lien</a>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo = $_POST['pseudo'];
        $mdp = $_POST['mdp'];
        $ok = false;

        $stmt = $dbh->prepare("SELECT pseudo FROM user WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();

        while ($ligne = $stmt->fetch(PDO::FETCH_OBJ)) {
            $ok = true;
        }

        if ($ok) {
            $stmt = $dbh->prepare("SELECT pseudo, mdp, email FROM user WHERE pseudo = :pseudo");
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->execute();

            $ligne = $stmt->fetch(PDO::FETCH_OBJ);

            if (password_verify($mdp, $ligne->mdp)) {
                $_SESSION['pseudo'] = $pseudo;
                $_SESSION['mdp'] = $mdp;
                $email = $ligne->email;

                try {
                    include('connexion_mail.php');
                    $mail->isHTML(true);
                    $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'Mamadou');
                    $mail->addAddress($email, $pseudo);
                    $mail->Subject = 'Connection de votre compte';
                    $mail->Body = "Bonjour $pseudo, vous venez de vous connecter à votre compte. Si ce n'était pas vous, modifiez immédiatement votre mot de passe.";
                    $mail->CharSet = 'utf-8';
                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

                header('Location: compte.php');
                exit();
            } else {
                echo "<center>Authentification ratée</center>";
            }
        } else {
            echo "<center>Pseudo inexistant</center>";
        }

        $stmt->closeCursor();
    }
    ?>

</body>

</html>
