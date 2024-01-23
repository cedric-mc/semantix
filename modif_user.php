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
    <title>Modification Pseudo</title>
</head>

<div class="wrapper">
    <form action="modif_user.php" method="post">
        <h1>Changer pseudo</h1>
        <br>
        <p align="center";>Pseudo actuel : <?php echo htmlspecialchars($_SESSION['pseudo']);?> </p>
        <div class="input-box">
            <input placeholder="Nouveau Pseudo" type="text" id="pseudo" name="pseudo" required />
        </div>


        <button class ="btn" type="submit">Changer pseudo</button>
        <div class="register-link"><a href="compte.php">Retour</a></div>


    </form>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pseudoNew = $_POST['pseudo'];

    $stmt = $dbh->prepare("SELECT pseudo FROM user WHERE pseudo = :pseudoNew");
    $stmt->bindParam(':pseudoNew', $pseudoNew); // Utiliser :pseudoNew au lieu de :pseudo
    $stmt->execute();

    if ($stmt->fetch()) {
        $ok = false;
        echo "<br>Ce pseudo existe déjà, choisissez-en un autre.";
    } else {
        $stmt = $dbh->prepare("SELECT id,email FROM user WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
        $stmt->execute();
        $ligne = $stmt->fetch(PDO::FETCH_OBJ);
        $id = $ligne->id;
        $email = $ligne->email;

        $stmt = $dbh->prepare("UPDATE user SET pseudo = :pseudoNew WHERE id = :id");
        $stmt->bindParam(':pseudoNew', $pseudoNew); // Utiliser :pseudoNew au lieu de :pseudo
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $_SESSION['pseudo'] = $pseudoNew;
        echo "<p align='center'>Changement sauvegardé </p>";

        $action = "Modification Pseudo";
        $ip = $_SERVER['REMOTE_ADDR'];
        date_default_timezone_set('Europe/Paris');
        $date = date('y-m-d H:i:s');
        $stmt = $dbh->prepare("INSERT INTO trace (action, ip, date, user_id) VALUES (:action, :ip, :date, :id)");
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        include('include/connexion_mail.php');
        $pseudoNew = $_SESSION['pseudo'];
        $mail->isHTML(true);
        $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'Mamadou');
        $mail->addAddress($email, $pseudoNew);
        $mail->Subject = 'Changement de Pseudo';
        $mail->Body = "Bonjour $pseudoNew,<br><br> Nous vous envoyons ce mail pour vous prévenir que votre pseudo à été modifiée.";
        $mail->CharSet = 'utf-8';
        $mail->send();
    }


}
?>

</div>
