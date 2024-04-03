<?php
    include_once("../class/User.php");
    include_once("../includes/conf.php");
    include_once("../includes/fonctions.php");
    session_start();
    include_once("../includes/session.php");

    header('Content-Type: application/json');

    $pseudo = $_POST['search'];
    $motdepasse = $_POST['motdepasse'];

    // Rechercher l'utilisateur dans la base de données
    $query_select_user = "SELECT * FROM sae_users WHERE search = :search";
    $stmt_select_user = $cnx->prepare($query_select_user);
    $stmt_select_user->bindParam(":search", $pseudo, PDO::PARAM_STR);
    $stmt_select_user->execute();
    $user = $stmt_select_user->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe
    if ($user) {
        if($user['statut']==1) {
            // L'utilisateur existe, maintenant tu peux vérifier le mot de passe
            $stored_motdepasse = $user['motdepasse']; // Mot de passe stocké dans la base de données
            $stored_salt = $user['salt']; // Sel stocké dans la base de données

            // Recalculer le hachage avec le sel
            $hashed_input_motdepasse = hash_pbkdf2("sha256", $motdepasse, $stored_salt, 5000, 32);

            // Comparer les hachages
            if ($hashed_input_motdepasse === $stored_motdepasse) {
                $length = 32;
                $randomBytes = random_bytes($length);
                $jwtToken= bin2hex($randomBytes);
                $_SESSION['user'] = serialize($user);

                // Vérifier si le search existe déjà dans sae_token_user
                $query_check_token = "SELECT * FROM sae_token_user WHERE search = :search";
                $stmt_check_token = $cnx->prepare($query_check_token);
                $stmt_check_token->bindParam(":search", $pseudo, PDO::PARAM_STR);
                $stmt_check_token->execute();
                $existing_token = $stmt_check_token->fetch(PDO::FETCH_ASSOC);

                if ($existing_token) {
                    // Mettre à jour le token existant
                    $query_update_token = "UPDATE sae_token_user SET token = :token, datetime = NOW() WHERE search = :search";
                    $stmt_update_token = $cnx->prepare($query_update_token);
                    $stmt_update_token->bindParam(":token", $jwtToken, PDO::PARAM_STR);
                    $stmt_update_token->bindParam(":search", $pseudo, PDO::PARAM_STR);
                    $stmt_update_token->execute();
                } else {
                    // Insérer un nouveau token
                    $query_insert_token = "INSERT INTO sae_token_user (search, token, datetime) VALUES (:search, :token, NOW())";
                    $stmt_insert_token = $cnx->prepare($query_insert_token);
                    $stmt_insert_token->bindParam(":search", $pseudo, PDO::PARAM_STR);
                    $stmt_insert_token->bindParam(":token", $jwtToken, PDO::PARAM_STR);
                    $stmt_insert_token->execute();
                }

                // Renvoyer une réponse JSON avec le succès et le jeton d'authentification
                echo json_encode(array("success" => true, "auth_token" => $jwtToken));
            } else {
                // Authentification échouée
                echo json_encode(array("success" => false));
            }
        }
    }else {
        // Authentification échouée
        echo json_encode(array("success" => false));
    }

?>