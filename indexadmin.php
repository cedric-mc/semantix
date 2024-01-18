<link rel="stylesheet" href="style.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<?php
include('connexion.php');
session_start();

if (isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
    header('Location: acceuil.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="monkeyapp.png">
    <title>Connexion</title>
</head>

<body>
<style>
</style>
<div class="wrapper">
    <form action="indexadmin.php" method="post">
        <h1>Connexion admin</h1>
        <div class="input-box">
            <input type="text" id="pseudo" name="pseudo" placeholder = "Pseudo" required>
            <i class='bx bx-user'></i>
        </div>
        <div class="input-box">
            <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>
            <i class='bx bxs-lock-alt' ></i>
        </div>


        <button class="btn" type="submit">Se connecter</button>
        <div class="register-link">
            <a href="index.php"> Retour </a><br>
        </div>
    </form>


    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo = $_POST['pseudo'];
        $mdp = $_POST['mdp'];
        $ok = false;

        $stmt = $dbh->prepare("SELECT pseudo FROM admin WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->execute();

        while ($ligne = $stmt->fetch(PDO::FETCH_OBJ)) {
            $ok = true;
        }

        if ($ok) {
            $stmt = $dbh->prepare("SELECT pseudo, password, mail FROM admin WHERE pseudo = :pseudo");
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->execute();

            $ligne = $stmt->fetch(PDO::FETCH_OBJ);

            if (password_verify($mdp, $ligne->password)) {
                $_SESSION['pseudo'] = $pseudo;
                $_SESSION['mdp'] = $mdp;
                $email = $ligne->email;

                try {
                    include('connexion_mail.php');
                    $mail->isHTML(true);
                    $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'Support');
                    $mail->addAddress($email, $pseudo);
                    $mail->Subject = 'Connexion a votre compte';
                    $mail->Body = "Bonjour $pseudo, une connexion à été détécté à votre compte admin, veuillez réinitaliser le mot de passe si ce n'est pas vous";
                    $mail->CharSet = 'utf-8';
                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                $stmt = $dbh->prepare("SELECT id FROM admin WHERE pseudo = :pseudo");
                $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
                $stmt->execute();
                $ligne = $stmt->fetch(PDO::FETCH_OBJ);
                $id = $ligne->id;

                // TRACE
                $action = "Connexion Admin";
                $ip = $_SERVER['REMOTE_ADDR'];
                date_default_timezone_set('Europe/Paris');
                $date = date('y-m-d H:i:s');
                $stmt = $dbh->prepare("INSERT INTO trace (action, ip, date, id) VALUES (:action, :ip, :date, :id)");
                var_dump($stmt);
                $stmt->bindParam(':action', $action);
                $stmt->bindParam(':ip', $ip);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                header('Location: acceuilAdmin.php');
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
</div>

</body>

</html>
