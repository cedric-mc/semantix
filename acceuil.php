<head><title> Acceuil </title>
    <link rel="icon" href="monkeyapp.png">

</head>
<link href='style/menu.css' rel='stylesheet'>
<link href='style/style2.css' rel='stylesheet'>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include('include/connexion.php');

include('include/redirection.php');

include('include/menu.php');


if (isset($_SESSION['pseudo']) && isset($_SESSION['mdp'])) {

    $stmt = $dbh->prepare("SELECT id FROM user WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $_SESSION['pseudo']);
    $stmt->execute();
    $ligne = $stmt->fetch(PDO::FETCH_OBJ);
    $id = $ligne->id;
    $_SESSION['id'] = $id;

    $stmt = $dbh->prepare("SELECT admin FROM user WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_OBJ);
    if ($admin->admin == 1) {
        $_SESSION['admin'] = 1;
    }
    else{
        $_SESSION['admin']= 0;
    }
    
    echo "<main> <body> <div class='wrapper'>";
    echo "<h1> Bonjour " . htmlspecialchars($_SESSION['pseudo']);

    echo"</h1> </div>";

}
?>

<!-- Bouton pour copier le texte -->
<!--
<button onclick="copyText()">Copier le message</button>

<script>
    function copyText() {
        // Le message prédéfini
        var textToCopy = "Je viens de faire un score de 55 sur MonkeyGame vient me rejoindre ! https://perso-etudiant.u-pem.fr/~mamadou.ba2/projet-sae/index.php";

        // Utiliser l'API Clipboard pour copier le texte
        navigator.clipboard.writeText(textToCopy).then(function() {
            // Message de confirmation
            alert("Message copié dans le presse-papiers !");
        })
            .catch(function(error) {
                // Gestion des erreurs éventuelles
                alert("Erreur lors de la copie : ", error);
            });
    }
</script>
-->

<br>
</main>
</body>
