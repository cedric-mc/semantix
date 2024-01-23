<link rel="stylesheet" href="style.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('include/redirection.php');
include('include/connexion.php');
$stmt = $dbh->prepare("SELECT email FROM user WHERE pseudo = :pseudo");
$stmt->bindParam(':pseudo', $_SESSION['pseudo']);
$stmt->execute();
$ligne = $stmt->fetch(PDO::FETCH_OBJ);
$mail = $ligne->email;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="monkeyapp.png">
    <title>Modification Mot de passe</title>
</head>

<body>
<div class="wrapper">
    <form action="modif_password.php" method="post">
        <h1>Modifier votre mot de passe</h1>
        <div class ="input-box">
            <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($mail); ?>" required />
        </div>

        <button class="btn" type="submit">Envoyer mail</button>
        <div class="register-link">
            <a href="compte.php">Retour</a>
        </div>


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

        try {
            include('include/connexion_mail.php');
            $mail->isHTML(true);
            $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'Support');
            $mail->addAddress($email, $pseudo);
            $mail->Subject = 'Récupération de votre compte';
            $mail->Body = "Bonjour $pseudo,<br><br>Cliquez sur ce lien pour modifier votre mot de passe : <a href='https://perso-etudiant.u-pem.fr/~mamadou.ba2/projet-sae/recup2.php?email=$email'>Modifier le mot de passe</a>";
            $mail->CharSet = 'utf-8';

            $mail->send();
            echo 'Un mail a été envoyé à ' . $email . ' pour modifier votre mot de passe';
            $stmt = $dbh->prepare("INSERT INTO recuperation (email) VALUES (:email)");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Aucun compte associé à cet e-mail.';
    }
}
?>
</div>
</body>

</html>
