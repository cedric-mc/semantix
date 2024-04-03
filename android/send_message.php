<?php
include_once("../includes/conf.php");

header('Content-Type: application/json');

// Récupérer les données envoyées par POST
$recipient = $_POST['recipient'];
$subject = $_POST['subject'];
$content = $_POST['content'];
$auth_token = $_POST['auth_token'];

// Retrieve user's search from auth token
$query_select_user = "SELECT search FROM sae_token_user WHERE token = :token";
$stmt_select_user = $cnx->prepare($query_select_user);
$stmt_select_user->bindParam(":token", $auth_token, PDO::PARAM_STR);
$stmt_select_user->execute();
$user = $stmt_select_user->fetch(PDO::FETCH_ASSOC);

// Vérifier que le search du destinataire existe
$query_check_recipient = "SELECT * FROM sae_users WHERE search = :recipient";
$stmt_check_recipient = $cnx->prepare($query_check_recipient);
$stmt_check_recipient->bindParam(":recipient", $recipient, PDO::PARAM_STR);
$stmt_check_recipient->execute();
$recipient_exists = $stmt_check_recipient->fetch(PDO::FETCH_ASSOC);


if ($user && $recipient_exists) {
    $pseudo = $user['search'];
    // Vérifier si l'utilisateur n'a pas envoyé plus de 10 messages dans l'heure
    $query_message_count = "SELECT COUNT(*) AS message_count FROM sae_message WHERE expediteur = :expediteur AND date > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
    $stmt_message_count = $cnx->prepare($query_message_count);
    $stmt_message_count->bindParam(":expediteur", $pseudo, PDO::PARAM_STR);
    $stmt_message_count->execute();
    $message_count_row = $stmt_message_count->fetch(PDO::FETCH_ASSOC);
    $message_count = $message_count_row['message_count'];

    if ($message_count >= 10) {
        // L'utilisateur a déjà envoyé plus de 10 messages dans l'heure
        echo json_encode(array("success" => false, "error" => "Vous avez atteint la limite de 10 messages par heure"));
    } else {
        // Insérer le nouveau message dans la base de données
        $query_insert_message = "INSERT INTO sae_message (expediteur, destinataire, sujet, contenu, date) VALUES (:expediteur, :destinataire, :sujet, :contenu, NOW())";
        $stmt_insert_message = $cnx->prepare($query_insert_message);
        $stmt_insert_message->bindParam(":expediteur", $pseudo, PDO::PARAM_STR);
        $stmt_insert_message->bindParam(":destinataire", $recipient, PDO::PARAM_STR);
        $stmt_insert_message->bindParam(":sujet", $subject, PDO::PARAM_STR);
        $stmt_insert_message->bindParam(":contenu", $content, PDO::PARAM_STR);
        $stmt_insert_message->execute();

        echo json_encode(array("success" => true));
    }
}else{
    echo json_encode(array("success" => false));
}
?>
