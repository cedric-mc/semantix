<head><title> Acceuil </title></head>
<link href='menu.css' rel='stylesheet'>
<link href='style2.css' rel='stylesheet'>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('connexion.php');
include('menu.php');
if (isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])) {

    $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $id = $ligne->id;
    
    echo "<main> <body>";
    echo "Bonjour " . htmlspecialchars($_SESSION['pseudo']);



}
?>
<br>
</main>
</body>
