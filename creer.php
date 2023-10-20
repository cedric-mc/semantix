<html>

<p>Créer un compte</p>
<form action="creer.php" method="post">
  <ul>
    <li>
      <label for="pseudo">Pseudo: </label>
      <input type="text" id="pseudo" name="pseudo" required/>
    </li>
    <li>
      <label for="email">E-mail: </label>
      <input type="email" id="email" name="email" required/>
    </li>
    <li>
      <label for="emailConf">Confirmez votre e-mail: </label>
      <input type="email" id="emailConf" name="emailConf" required/>
    </li>
    <li>
      <label for="annee">Année de naissance: </label>
      <input type="number" id="annee" name="annee" required/>
    </li>
    <li>
      <label for="mdp">Mot de passe: </label>
      <input type="password" id="mdp" name="mdp" required/>
    </li>
    <li>
      <label for="mdpConf">Confirmez votre mot de passe: </label>
      <input type="password" id="mdpConf" name="mdpConf" required/>
    </li>
    <div class="button">
  <button type="submit">Créer le compte</button>
</div>
</ul>
</form>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!file_exists('PHPMailer/src/Exception.php')) exit("Le fichier 'PHPMailer/src/Exception.php' n'existe pas !");

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if($_POST) {
  $user =  'mamadou.ba2';
  $pass =  'mamadou';
  $dbh = new PDO('mysql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);

  $pseudo=$_POST['pseudo'];
  $email=$_POST['email'];
  $emailConf=$_POST['emailConf'];
  $annee=$_POST['annee'];
  $mdp=$_POST['mdp'];
  $mdpConf=$_POST['mdpConf'];

    $ok = true;

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

  $validation_token = md5(uniqid(rand(), true));

  if ($email != $emailConf || $mdp != $mdpConf){
    $ok = false;
  }

  $results=$dbh->query("SELECT pseudo FROM user");
  while( $ligne = $results->fetch(PDO::FETCH_OBJ) ){
    if ($ligne->pseudo == $pseudo){
      $ok = false;
      echo "<br>Ce pseudo existe déjà, choisissez-en un autre.";
    }
  }
  if ($ok){
    try {
        //Server settings
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'partage.u-pem.fr';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'mamadou.ba2@edu.univ-eiffel.fr';                     //SMTP username
        $mail->Password   = 'Ahmed  77186';                               //SMTP password
        $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->setFrom('mamadou.ba2@edu.univ-eiffel.fr', 'Mamadou');
        $mail->addAddress($email, $pseudo);
        $mail->Subject = 'Validation de votre compte';
        $mail->Body = "Bonjour $pseudo,\n\nCliquez sur ce lien pour valider votre compte : https://perso-etudiant.u-pem.fr/~mamadou.ba2/validation.php?token=$validation_token";
        
        $mail->send();
        echo 'Un lien de vérification a été envoyé à votre e-mail : '.$email;
        $dbh = new PDO('mysql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $results="INSERT INTO validation (pseudo, email, annee, mdp, token) VALUES ('$pseudo', '$email', '$annee', '$mdpHash', '$validation_token')";
        $dbh->exec($results);
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }else{
  echo "<br>Il y a une erreur dans le formulaire.";
  }
}

?>
<form action="index.php">
    <button type="submit">Retour</button>
</form>
</html>

