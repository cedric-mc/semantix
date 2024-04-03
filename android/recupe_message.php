<?php
// Inclure les fichiers nécessaires et démarrer la session si nécessaire
include_once("../includes/conf.php");
header('Content-Type: application/json');
header('Content-Type: application/json');

$auth_token = $_POST['auth_token'];

// Retrieve user's search from auth token
$query_select_user = "SELECT pseudo FROM sae_token_user WHERE token = :token";
$stmt_select_user = $cnx->prepare($query_select_user);
$stmt_select_user->bindParam(":token", $auth_token, PDO::PARAM_STR);
$stmt_select_user->execute();
$user = $stmt_select_user->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $pseudo = $user['pseudo'];

    // Check if user's last update exists in sae_message_update table
    $query_select_last_update = "SELECT last_update FROM sae_message_update WHERE pseudo = :pseudo";
    $stmt_select_last_update = $cnx->prepare($query_select_last_update);
    $stmt_select_last_update->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    $stmt_select_last_update->execute();
    $last_update_row = $stmt_select_last_update->fetch(PDO::FETCH_ASSOC);

    if ($last_update_row) {
        // User's last update exists, retrieve messages after last update
        $last_update = $last_update_row['last_update'];
        $query_select_messages = "SELECT * FROM sae_message WHERE destinataire = :destinataire AND date > :last_update";
        $stmt_select_messages = $cnx->prepare($query_select_messages);
        $stmt_select_messages->bindParam(":destinataire", $pseudo, PDO::PARAM_STR);
        $stmt_select_messages->bindParam(":last_update", $last_update, PDO::PARAM_STR);
        $stmt_select_messages->execute();
        $new_messages = $stmt_select_messages->fetchAll(PDO::FETCH_ASSOC);
    }
        // User's last update does not exist, retrieve all messages
    $query_select_messages = "SELECT * FROM sae_message WHERE expediteur = :expediteur or destinataire = :destinataire order by date desc";
    $stmt_select_messages = $cnx->prepare($query_select_messages);
    $stmt_select_messages->bindParam(":destinataire", $pseudo, PDO::PARAM_STR);
    $stmt_select_messages->bindParam(":expediteur", $pseudo, PDO::PARAM_STR);
    $stmt_select_messages->execute();
    $messages = $stmt_select_messages->fetchAll(PDO::FETCH_ASSOC);


    // Update or insert last update date for user
    $now = date("Y-m-d H:i:s");
    if ($last_update_row) {
        $query_update_last_update = "UPDATE sae_message_update SET last_update = :last_update WHERE pseudo = :pseudo";
        $stmt_update_last_update = $cnx->prepare($query_update_last_update);
        $stmt_update_last_update->bindParam(":last_update", $now, PDO::PARAM_STR);
        $stmt_update_last_update->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
        $stmt_update_last_update->execute();
    } else {
        $query_insert_last_update = "INSERT INTO sae_message_update (pseudo, last_update) VALUES (:pseudo, :last_update)";
        $stmt_insert_last_update = $cnx->prepare($query_insert_last_update);
        $stmt_insert_last_update->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
        $stmt_insert_last_update->bindParam(":last_update", $now, PDO::PARAM_STR);
        $stmt_insert_last_update->execute();
    }

    // Return messages as JSON response
    echo json_encode(array("success" => true, "messages" => $messages, "new_messages" => $new_messages));
} else {
    // Invalid auth token
    echo json_encode(array("success" => false, "error" => "Invalid auth token"));
}
?>