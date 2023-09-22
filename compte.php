<?php
session_start();
if (isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])){
	echo "Bonjour ".$_SESSION['pseudo'];
}
?>
<html>
<a href="deco.php"> Se déconnecter</a>