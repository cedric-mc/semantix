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
      <label for="annee">Année de naissance: </label>
      <input type="number" id="annee" name="annee" required/>
    </li>
    <li>
      <label for="mdp">Mot de passe: </label>
      <input type="password" id="mdp" name="mdp" required/>
    </li>
    <div class="button">
  <button type="submit">Créer le compte</button>
</div>
</ul>
</form>
<?php
if($_POST) {
  $user =  'mamadou.ba2';
  $pass =  'mamadou';
  $dbh = new PDO('pgsql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);

  $pseudo=$_POST['pseudo'];
  $email=$_POST['email'];
  $annee=$_POST['annee'];
  $mdp=$_POST['mdp'];

  
  $dbh = new PDO('pgsql:host=sqletud.u-pem.fr;dbname=mamadou.ba2_db',$user,$pass);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $results="INSERT INTO tp1_dev.player VALUES ('$pseudo', '$email', '$annee', md5('$mdp'))";
  $dbh->exec($results);
  echo $pseudo." votre compte a été créé.";
  
        
}
?>
</html>

