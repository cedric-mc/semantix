<link rel="stylesheet" href="style/style.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/monkeyapp.png">
    <title>Validation compte</title>
</head>

<?php
include('include/connexion.php');


// Récupérez le jeton de validation depuis l'URL
$validation_token = $_GET['token'];

// Vérifiez le jeton de validation dans la base de données
// Si le jeton est valide, activez le compte correspondant

$ok = false;
$stmt = $dbh->prepare("SELECT pseudo, email, annee, mdp FROM validation WHERE token = :validation_token");
$stmt->bindParam(':validation_token', $validation_token);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_OBJ);

if ($row) {
    $pseudo = $row->pseudo;
    $email = $row->email;
    $annee = $row->annee;
    $mdp = $row->mdp;

    // Vérifier si le pseudo existe déjà dans la table 'user'
    $stmt = $dbh->prepare("SELECT pseudo FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->execute();

    if (!$stmt->fetch()) {
        try {
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $dbh->prepare("INSERT INTO user (pseudo, email, annee, mdp) VALUES (:pseudo, :email, :annee, :mdp)");
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':annee', $annee);
            $stmt->bindParam(':mdp', $mdp);
            $stmt->execute();

            echo $pseudo . " votre compte a été activé avec succès!";
            echo "<a href='index.php'> Page d'acceuil </a>";
            $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->execute();
            $ligne = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $ligne->id;

            // TRACE
            $action = "Creation";
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
            echo "Erreur lors de l'activation du compte : " . $e->getMessage();
        }
    } else {
        echo "Erreur lors de l'activation du compte. Votre compte a déjà été activé.";
    }
} else {
    echo "Erreur lors de l'activation du compte. Jeton invalide.";
}
?>
