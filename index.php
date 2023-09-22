<html>
<?php
session_start();
if (isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
	header('Location: compte.php');
	exit();
}
?>
<p>Se connecter</p>
<form action="" method="post">
  <ul>
    <li>
      <label for="pseudo">Pseudo: </label>
      <input type="text" id="pseudo" name="pseudo" />
    </li>
    <li>
      <label for="mdp">Mot de passe: </label>
      <input type="password" id="mdp" name="mdp" />
    </li>
    <div class="button">
  <button type="submit">Se connecter</button>
</div>
</ul>
</form>
<a href="creer.php"> Si vous n'avez pas de compte créez-en un</a>

<?php
if($_POST) {
  $user =  'mamadou.ba2';
  $pass =  'mamadou';
  $dbh = new PDO('pgsql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);

session_start();
$pseudo=$_POST['pseudo'];
$mdp=$_POST['mdp'];

$ok = false;

$results=$dbh->query("SELECT pseudo FROM tp1_dev.player");
while( $ligne = $results->fetch(PDO::FETCH_OBJ) ){
	if ($ligne->pseudo == $pseudo){
		$ok = true;
	}
}

if ($ok){
	$results=$dbh->query("SELECT pseudo, mdp FROM tp1_dev.player where pseudo = '$pseudo'");
	$ligne = $results->fetch(PDO::FETCH_OBJ);
	if ($ligne->mdp == md5($mdp)){
		$_SESSION['pseudo'] = $pseudo;
		$_SESSION['mdp'] = $mdp; 
		header('Location: compte.php');
		exit();
	}else{
		echo "<center>Authentification ratée</center>";
	}
}else{
	echo "<center>Pseudo inexistant</center>";
}
$results->closeCursor();
}
?>
</html>