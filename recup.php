<html>

<p>Récupérer votre compte</p>
<form action="recup.php" method="post">
  <ul>
    <li>
      <label for="email">E-mail: </label>
      <input type="email" id="email" name="email" required/>
    </li>
    <div class="button">
  <button type="submit">Récupérer le compte</button>
</div>
</ul>
</form>
<form action="index.php">
    <button type="submit">Retour</button>
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

$email=$_POST['email'];

$ok = false;
$results=$dbh->query("SELECT pseudo, email FROM user");
while( $ligne = $results->fetch(PDO::FETCH_OBJ) ){
  if ($ligne->email == $email){
    $ok = true;
    $pseudo = $ligne->pseudo;
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
        $mail->Subject = 'Récuperation de votre compte';
        $mail->Body = "Bonjour $pseudo,<br><br>Cliquez sur ce lien pour récupérer votre compte : <a href='https://perso-etudiant.u-pem.fr/~mamadou.ba2/projet/recup2.php?email=$email'>Récupérer le compte</a>";

        
        $mail->send();
        echo 'Un lien de récupération a été envoyé à '.$email.' si elle est dans notre base de donnée.';
    
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}else{
  echo 'Un lien de récupération a été envoyé à '.$email.' si elle est dans notre base de donnée.';
}
}
?>
</html>