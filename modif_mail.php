<link rel="stylesheet" href="style/style.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('include/redirection.php');
include('include/connexion.php');

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
        $email = $ligne->email;
        ?>
        <p align="center";>Mail actuel : <?php echo $email;?> </p>
        <div class="input-box">
            <input placeholder="Nouveau Mail" type="email" id="mail" name="mail" required />
        </div>


        <button class ="btn" type="submit">Changer Mail</button>
        <div class="register-link"><a href="compte.php">Retour</a></div>


    </form>


    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $newEmail = $_POST['mail'];
        $validation_token = md5(uniqid(rand(), true));

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

            $pseudo = $_SESSION['pseudo'];
            $request = "UPDATE user SET email = '$newEmail' WHERE id = $id";
            date_default_timezone_set('Europe/Paris');
            $date = date('Y-m-d H:i:s', strtotime('+3 minutes'));
            $stmt = $dbh->prepare("INSERT INTO validation_mail (pseudo, request, token, date_expir) VALUES (:pseudo, :request, :validation_token, :date)");
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':request', $request);
            $stmt->bindParam(':validation_token', $validation_token);
            $stmt->bindParam(':date', $date);
            $stmt->execute();


            include('include/connexion_mail.php');
            $mail->isHTML(true);
            $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'Support');
            $mail->addAddress($email, $pseudo);
            $mail->Subject = 'Changement Adresse mail';
            $mail->Body = "Bonjour $pseudo,<br><br> Nous vous envoyons ce mail pour confirmer la modification de votre adresse mail MonkeyGame vers $newEmail,
            cliquez sur ce lien pour valider le changement : <a href='https://perso-etudiant.u-pem.fr/~mamadou.ba2/projet-sae/validation_mail.php?token=$validation_token'>Valider le mail</a>";
            $mail->CharSet = 'utf-8';
            $mail->send();

            echo "<p align='center'>Mail de modification envoyé </p>";
        }

    }
    ?>

</div>
