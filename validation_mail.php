<link rel="stylesheet" href="style.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="monkeyapp.png">
    <title>Validation mail</title>
</head>

<?php
include('connexion.php');


// Récupérez le jeton de validation depuis l'URL
$validation_token = $_GET['token'];

// Vérifiez le jeton de validation dans la base de données
// Si le jeton est valide, activez le compte correspondant

$ok = false;
$stmt = $dbh->prepare("SELECT pseudo, request, date_expir FROM validation_mail WHERE token = :validation_token");
$stmt->bindParam(':validation_token', $validation_token);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_OBJ);
$date_expir = $row->date_expir;

date_default_timezone_set('Europe/Paris');
$date_actuelle = date('Y-m-d H:i:s');
if ($date_expir < $date_actuelle){
    $row = 0;
}

if ($row) {
    $pseudo = $row->pseudo;
    $request = $row->request;
    if (!$stmt->fetch()) {
        try {
            $stmt = $dbh->prepare($request);
            $stmt->execute();

            echo $pseudo . " votre mail a été modifié avec succès!";
            echo "<a href='index.php'> Page d'acceuil </a>";
            $stmt = $dbh->prepare("SELECT id, email FROM user WHERE pseudo = :pseudo");
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->execute();
            $ligne = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $ligne->id;
            $email = $ligne->email;

            include('connexion_mail.php');
            $mail->isHTML(true);
            $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'Support');
            $mail->addAddress($email, $pseudo);
            $mail->Subject = 'Changement Adresse mail';
            $mail->Body = "Bonjour $pseudo, nous vous confirmons la modification de votre mail MonkeyGame à cette adresse.";
            $mail->CharSet = 'utf-8';
            $mail->send();

            $deleteStmt = $dbh->prepare("DELETE FROM validation_mail WHERE pseudo = :pseudo");
            $deleteStmt->bindParam(':pseudo', $pseudo);
            $deleteStmt->execute();

            // TRACE
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
            header('Location: acceuil.php');
            session_unset();
            session_destroy();
            header ('location: index.php');


        } catch (PDOException $e) {
            echo "Erreur lors de la modification : " . $e->getMessage();
        }
    } else {
        echo "Erreur lors de la modification du mail.";
    }
} else {
    echo "Erreur lors de la modification du mail. Jeton invalide ou expiré.";
}
?>
