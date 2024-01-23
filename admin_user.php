<?php
include('include/connexion.php');
session_start();

// Vérifier si la requête est une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le contenu de la requête et le décoder
    $content = file_get_contents("php://input");
    $decoded = json_decode($content, true);

    // Vérifier si userId est présent dans les données décodées
    if (isset($decoded['userId'])) {
        $userId = $decoded['userId'];
        $stmt = $dbh->prepare("UPDATE user SET admin = 1 WHERE id = :userId;");
        // Liaison du paramètre :userId à la variable $userId
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        // Exécution de la requête
        $stmt->execute();

        echo "L'utilisateur à les droits d'admin accordé";
    } else {
        echo "userId non reçu";
    }
}
?>
