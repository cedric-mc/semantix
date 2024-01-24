<link rel="stylesheet" href="style/style.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/monkeyapp.png">
    <title>Récupération compte</title>
</head>

<body>
<div class="wrapper">
    <form action="recup.php" method="post">
        <h1>Récupérer votre compte</h1>
        <div class ="input-box">
                <input type="email" id="email" name="email" placeholder="Email" required />
        </div>
        <div class="register-link">
            <a href="index.php">Retour</a>
        </div>
            <button class="btn" type="submit">Récupérer le compte</button>



    </form>
    <?php
    include('include/connexion.php');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];

        $stmt = $dbh->prepare("SELECT pseudo, email FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_OBJ);

        if ($row) {
            $pseudo = $row->pseudo;
            $validation_token = md5(uniqid(rand(), true));
            try {
                include('include/connexion_mail.php');
                $mail->isHTML(true);
                $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'MonkeyGame');
                $mail->addAddress($email, $pseudo);
                $mail->Subject = 'Récupération de votre compte';
                $mail->Body = "Bonjour $pseudo,<br><br>Cliquez sur ce lien pour récupérer votre compte : <a href='https://perso-etudiant.u-pem.fr/~mamadou.ba2/projet-sae/recup2.php?token=$validation_token'>Récupérer le compte</a>";
                $mail->CharSet = 'utf-8';

                $mail->send();
                echo 'Un lien de récupération a été envoyé à ' . $email . ' si elle est dans notre base de données.';
                date_default_timezone_set('Europe/Paris');
                $date = date('Y-m-d H:i:s', strtotime('+3 minutes'));
                $stmt = $dbh->prepare("INSERT INTO validation_mail (pseudo, token, date_expir) VALUES (:pseudo, :validation_token, :date)");
                $stmt->bindParam(':pseudo', $pseudo);
                $stmt->bindParam(':validation_token', $validation_token);
                $stmt->bindParam(':date', $date);
                $stmt->execute();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo 'Aucun compte associé à cet e-mail.';
        }
    }
    ?>
</body>

</html>
</div>
