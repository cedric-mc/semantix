<?php
$user =  'mamadou.ba2';
$pass =  'mamadou';
$dbh = new PDO('mysql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);
// Récupérez le jeton de validation depuis l'URL
$validation_token = $_GET['token'];

// Vérifiez le jeton de validation dans la base de données
// Si le jeton est valide, activez le compte correspondant
// ... (votre code pour vérifier et activer le compte dans la base de données)

$ok = false;
$results=$dbh->query("SELECT pseudo, email, annee, mdp, token FROM validation");
while( $ligne = $results->fetch(PDO::FETCH_OBJ) ){
  if ($ligne->token == $validation_token){
    $ok = true;
    $pseudo = $ligne->pseudo;
    $email = $ligne->email;
    $annee = $ligne->annee;
    $mdp = $ligne->mdp;
  }
}
$results=$dbh->query("SELECT pseudo FROM user");
  while( $ligne = $results->fetch(PDO::FETCH_OBJ) ){
    if ($ligne->pseudo == $pseudo){
      $ok = false;
    }
  }
if ($ok) {
	$dbh = new PDO('mysql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);
 	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 	$results="INSERT INTO user (pseudo, email, annee, mdp) VALUES ('$pseudo', '$email', '$annee','$mdp')";
 	$dbh->exec($results);
    echo $pseudo." votre compte a été activé avec succès!";
} else {
    echo "Erreur lors de l'activation du compte ou votre a déjà été activé";
}
?>
