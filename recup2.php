<!DOCTYPE html>
<html>

<head>
    <title>Réinitialisez votre mot de passe</title>
</head>

<body>
    <?php
    include('connexion.php');
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    ?>

    <p>Réinitialisez votre mot de passe</p>
    <form action="recup2.php" method="post">
        <ul>
            <li>
                <label for="email">E-mail :</label>
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly required>
            </li>
            <li>
                <label for="mdp">Mot de passe: </label>
                <input type="password" id="mdp" name="mdp" required />
            </li>
            <li>
                <label for="mdpConf">Confirmez votre mot de passe: </label>
                <input type="password" id="mdpConf" name="mdpConf" required />
            </li>
            <div class="button">
                <button type="submit">Réinitialiser le mot de passe</button>
            </div>
        </ul>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];
        $mdpConf = $_POST['mdpConf'];
        $ok = true;

        if ($mdp != $mdpConf) {
            $ok = false;
            echo "Les champs du mot de passe ne correspondent pas.";
        }

        // Fonction de vérification du mot de passe
        function verifierMotDePasse($mdp)
        {
            // Vérifie si le mot de passe contient au moins 1 majuscule, 1 minuscule,
            // 1 chiffre, 1 caractère spécial et a au moins 7 caractères de longueur
            $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,}$/";

            // Utilisez la fonction preg_match() pour vérifier le motif
            return preg_match($pattern, $mdp);
        }

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

                echo "Votre mot de passe a été modifié avec succès";
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
