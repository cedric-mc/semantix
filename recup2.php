<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/monkeyapp.png">
    <title>Réinitialisez votre mot de passe</title>
</head>

<body>
    <?php
    include('include/connexion.php');

    $validation_token = $_GET['token'];
    $stmt = $dbh->prepare("SELECT pseudo, date_expir FROM validation_mail WHERE token = :validation_token");
    $stmt->bindParam(':validation_token', $validation_token);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    $pseudo = $row->pseudo;
    $date_expir = $row->date_expir;

    date_default_timezone_set('Europe/Paris');
    $date_actuelle = date('Y-m-d H:i:s');
    if ($date_expir < $date_actuelle){
        $row = 0;
        echo "Le jeton a expiré, reformulez une nouvelle demande.\n";
    }

    $stmt = $dbh->prepare("SELECT email FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $email = $ligne->email;
    ?>

<div class ="wrapper">
    <form action="recup2.php" method="post">
        <h1>Réinitialisez votre mot de passe</h1>
        <div class="input-box">
                <input placeholder = "Email" type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly required>
        </div>
        <div class="input-box">
            <input placeholder = "Pseudo" type="text" id="pseudo" name="pseudo" value="<?php echo htmlspecialchars($pseudo); ?>" readonly required>
        </div>
        <div class="input-box">
            <input placeholder ="Mot de passe" type="password" id="mdp" name="mdp" required />
        </div>
        <div class="input-box">
                <input placeholder="Confirmez votre mot de passe:" type="password" id="mdpConf" name="mdpConf" required />
        </div>

            <?php if ($row != 0){ echo'<button class="btn" type="submit">Réinitialiser le mot de passe</button>';}?>

        <div class="register-link">
            <a href="index.php"> Revenir à l'acceuil </a>
        </div>
    </form>
</div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $pseudo = $_POST['pseudo'];
        $mdp = $_POST['mdp'];
        $mdpConf = $_POST['mdpConf'];
        $ok = true;

        if ($mdp != $mdpConf) {
            $ok = false;
            echo "Les champs du mot de passe ne correspondent pas.";
        }

        // Fonction de vérification du mot de passe
        function verifierMotDePasse($mdp)
        {return preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,}$/', $mdp);}

        if (!verifierMotDePasse($mdp)) {
            $ok = false;
            echo "Mot de passe invalide. Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial et avoir au moins 7 caractères de long.";
        }

        $mdpHash = password_hash($mdp, PASSWORD_BCRYPT);

        if ($ok) {
            try {
                $stmt = $dbh->prepare("UPDATE user SET mdp = :mdp WHERE email = :email");
                $stmt->bindParam(':mdp', $mdpHash);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                
                $deleteStmt = $dbh->prepare("DELETE FROM validation_mail WHERE pseudo = :pseudo");
                $deleteStmt->bindParam(':pseudo', $pseudo);
                $deleteStmt->execute();

                if (!$deleteStmt->execute()) {
                    echo "Erreur lors de la suppression : " . $deleteStmt->errorInfo()[2];
                }

                echo "Votre mot de passe a été modifié avec succès";
                $stmt = $dbh->prepare("SELECT id FROM user WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $ligne = $stmt->fetch(PDO::FETCH_OBJ);
                $id = $ligne->id;

                // TRACE
                $action = "Modification mot de passe";
                $ip = $_SERVER['REMOTE_ADDR'];
                date_default_timezone_set('Europe/Paris');
                $date = date('y-m-d H:i:s');
                $stmt = $dbh->prepare("INSERT INTO trace (action, ip, date, user_id) VALUES (:action, :ip, :date, :id)");
                $stmt->bindParam(':action', $action);
                $stmt->bindParam(':ip', $ip);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        } else {
            echo "<br>Il y a une erreur.";
        }
    }
    ?>
</body>

</html>
