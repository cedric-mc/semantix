<?php
include_once("../class/User.php");
include_once("../includes/conf.php");
include_once("../includes/fonctions.php");

header('Content-Type: application/json');

// Vérifier si le jeton d'authentification est présent dans les SharedPreferences
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $authToken = $_POST['auth_token'];

    // Rechercher le jeton dans la base de données
    $query_select_token = "SELECT * FROM sae_token_user WHERE token = :token AND datetime >= DATE_SUB(NOW(), INTERVAL 720 MINUTE)";
    $stmt_select_token = $cnx->prepare($query_select_token);
    $stmt_select_token->bindParam(":token", $authToken, PDO::PARAM_STR);
    $stmt_select_token->execute();
    $token = $stmt_select_token->fetch(PDO::FETCH_ASSOC);

    if ($token) {
        // Jeton valide, renvoyer une réponse JSON avec succès
        echo json_encode(array("success" => true));
        exit();
    }
}

// Si le jeton n'est pas valide ou n'existe pas, renvoyer une réponse JSON avec échec
echo json_encode(array("success" => false));
?>
