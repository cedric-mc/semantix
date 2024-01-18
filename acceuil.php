<head><title> Acceuil </title>
    <link rel="icon" href="monkeyapp.png">

</head>
<link href='menu.css' rel='stylesheet'>
<link href='style2.css' rel='stylesheet'>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('connexion.php');
include('redirection.php');
include('menu.php');

if (isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])) {

    $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $id = $ligne->id;
    
    echo "<main> <body> <div class='wrapper'>";
    echo "<h1> Bonjour " . htmlspecialchars($_SESSION['pseudo']);

    echo"</h1> </div>";

}
?>
<br>
</main>
</body>
