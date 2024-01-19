<link rel="stylesheet" href="style.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('connexion.php');

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="monkeyapp.png">
    <title>Modification Mail</title>
</head>

<div class="wrapper">
    <form action="modif_mail.php" method="post">
        <h1>Changer de mail</h1>
        <br>
        <?php
        $stmt = $dbh->prepare("SELECT email FROM user WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
        $stmt->execute();
        $ligne = $stmt->fetch(PDO::FETCH_OBJ);
        $mail = $ligne->email;
        ?>
        <p align="center";>Mail actuel : <?php echo $mail;?> </p>
        <div class="input-box">
            <input placeholder="Nouveau Mail" type="email" id="mail" name="mail" required />
        </div>


        <button class ="btn" type="submit">Changer Mail</button>
        <div class="register-link"><a href="compte.php">Retour</a></div>


    </form>


    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $newEmail = $_POST['mail'];

        $stmt = $dbh->prepare("SELECT email FROM user WHERE email = :newEmail");
        $stmt->bindParam(':newEmail', $newEmail); // Utiliser :pseudoNew au lieu de :pseudo
        $stmt->execute();

        if ($stmt->fetch()) {
            $ok = false;
            echo "<br>Ce mail existe déjà, choisissez-en un autre.";
        } else {
            $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
            $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
            $stmt->execute();
            $ligne = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $ligne->id;

            $stmt = $dbh->prepare("UPDATE user SET email = :newEmail WHERE id = :id");
            $stmt->bindParam(':newEmail', $newEmail); // Utiliser :pseudoNew au lieu de :pseudo
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            echo "<p align='center'>Changement sauvegardé </p>";
            $action = "Modification mail";
            $ip = $_SERVER['REMOTE_ADDR'];
            date_default_timezone_set('Europe/Paris');
            $date = date('y-m-d H:i:s');
            $stmt = $dbh->prepare("INSERT INTO trace (action, ip, date, user_id) VALUES (:action, :ip, :date, :id)");
            $stmt->bindParam(':action', $action);
            $stmt->bindParam(':ip', $ip);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            include('connexion_mail.php');
            $pseudo = $_SESSION['pseudo'];
            $mail->isHTML(true);
            $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'Support');
            $mail->addAddress($newEmail, $pseudo);
            $mail->Subject = 'Changement Adresse mail';
            $mail->Body = "Bonjour $pseudo,<br><br> Nous vous envoyons ce mail pour vous prévenir que votre adresse mail à été modifiée.";
            $mail->CharSet = 'utf-8';
            $mail->send();
        }

    }
    ?>

</div>
