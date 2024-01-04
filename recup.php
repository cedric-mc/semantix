<!DOCTYPE html>
<html>

<head>
    <title>Récupérer votre compte</title>
</head>

<body>
    <p>Récupérer votre compte</p>
    <form action="recup.php" method="post">
        <ul>
            <li>
                <label for="email">E-mail: </label>
                <input type="email" id="email" name="email" required />
            </li>
            <div class="button">
                <button type="submit">Récupérer le compte</button>
            </div>
        </ul>
    </form>

    <form action="index.php">
        <button type="submit">Retour</button>
    </form>

    <?php
    include('connexion.php');
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

            try {
                include('connexion_mail.php');
                $mail->isHTML(true);
                $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'Mamadou');
                $mail->addAddress($email, $pseudo);
                $mail->Subject = 'Récupération de votre compte';
                $mail->Body = "Bonjour $pseudo,<br><br>Cliquez sur ce lien pour récupérer votre compte : <a href='https://perso-etudiant.u-pem.fr/~mamadou.ba2/projet/recup2.php?email=$email'>Récupérer le compte</a>";
                $mail->CharSet = 'utf-8';

                $mail->send();
                echo 'Un lien de récupération a été envoyé à ' . $email . ' si elle est dans notre base de données.';
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
