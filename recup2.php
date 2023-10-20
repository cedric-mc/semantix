<html>
<?php
$email = $_GET['email'];
echo $email;
?>
<p>Réinitialisez votre mot de passe</p>
<form action="recup2.php" method="post">
  <ul>
    <li>
      <label for="mdp">Mot de passe: </label>
      <input type="password" id="mdp" name="mdp" required/>
    </li>
    <li>
      <label for="mdpConf">Confirmez votre mot de passe: </label>
      <input type="password" id="mdpConf" name="mdpConf" required/>
    </li>
    <div class="button">
  <button type="submit">Réinitialiser le mot de passe</button>
</div>
</ul>
</form>

<?php
if($_POST) {
$user =  'mamadou.ba2';
$pass =  'mamadou';
$dbh = new PDO('mysql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);

$mdp=$_POST['mdp'];
$mdpConf=$_POST['mdpConf'];
$ok = true;

if ($mdp != $mdpConf){
    $ok = false;
}

// Fonction de vérification du mot de passe
    function verifierMotDePasse($mdp) {
        // Vérifie si le mot de passe contient au moins 1 majuscule, 1 minuscule,
        // 1 chiffre, 1 caractère spécial et a au moins 7 caractères de longueur
        $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,}$/";

        // Utilisez la fonction preg_match() pour vérifier le motif
        if (preg_match($pattern, $mdp)) {
            return true; // Mot de passe valide
        } else {
            return false; // Mot de passe invalide
        }
    }

    if (verifierMotDePasse($mdp)) {
        1;
    } else {
        $ok = false;
        echo "Mot de passe invalide. Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial et avoir au moins 7 caractères de long.";
    }

  $mdpHash = password_hash($mdp, PASSWORD_BCRYPT);

if ($ok){
	try{
	$dbh = new PDO('mysql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$results=$dbh->query("UPDATE user set mdp = '$mdpHash' where email = '$email'");
	$dbh->exec($results);
	echo "Votre mot de passe a été modifié avec succès";
	}catch(PDOException $e){
		echo "Erreur : " . $e->getMessage();
	}
}else{
	echo "<br>Il y a une erreur.";
}
}
?>
</html>