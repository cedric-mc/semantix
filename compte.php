<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$user =  'mamadou.ba2';
$pass =  'mamadou';

$dbh = new PDO('mysql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);
$results = $dbh->query("SELECT id FROM user WHERE pseudo = '{$_SESSION['pseudo']}'");
$ligne = $results->fetch(PDO::FETCH_OBJ);
$id = $ligne->id;

if (isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
	echo "Bonjour ".$_SESSION['pseudo'];
	echo '<br><br><form action="compte.php" method="post">
	<li>
    <label for="un">1 Joueur</label>
    <input type="radio" id="un" name="nb" value="un" required>
	</li>
	<li>
    <label for="multi">Multijoueur</label>
    <input type="radio" id="multi" name="nb" value="multi" required>
	</li>
    <input type="submit" value="Lancer la Partie">
	</form>';

	if($_POST) {

		$score = random_int(50, 100);
		$nb = isset($_POST['nb']);
		if ($nb === 'un') {
			echo "<h1>Partie lancée avec 1 joueur</h1>";
		} else{
			echo "<h1>Partie lancée en mode multijoueur</h1>";
		}

		$dbh = new PDO('mysql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 		$results="INSERT INTO score_game (score, user_id) VALUES ('$score', '$id')";
 		$dbh->exec($results);
		echo "<br> Votre score : ".$score;
	}
	$dbh = new PDO('mysql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);
	$results = $dbh->query("SELECT COUNT(score) as nb FROM score_game WHERE user_id='$id'");
	$ligne = $results->fetch(PDO::FETCH_OBJ);
	$nb_partie = $ligne->nb;

	$results = $dbh->query("SELECT MAX(score) as max FROM score_game WHERE user_id='$id'");
	$ligne = $results->fetch(PDO::FETCH_OBJ);
	$max = $ligne->max;

	$results = $dbh->query("SELECT MIN(score) as min FROM score_game WHERE user_id='$id'");
	$ligne = $results->fetch(PDO::FETCH_OBJ);
	$min = $ligne->min;

	$results = $dbh->query("SELECT SUM(score) as sum FROM score_game WHERE user_id='$id'");
	$ligne = $results->fetch(PDO::FETCH_OBJ);
	$sum = $ligne->sum;

	$moyenne = ($sum/$nb_partie);

	echo "<h2>Statistiques de Parties</h2>

		<table border='1'>
			<tr>
				<th>Moyenne</th>
				<th>Minimum</th>
				<th>Maximum</th>
				<th>Nombre de Parties</th>
			</tr>
			<tr>
				<td>".$moyenne."</td>
				<td>".$min."</td>
				<td>".$max."</td>
				<td>".$nb_partie."</td>
			</tr>
		</table>";

}
?>
<br>
<a href="deco.php"> Se déconnecter</a>
