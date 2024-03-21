<?php
    include_once("../class/User.php");
    include_once("../includes/conf.php");
    include_once("../includes/fonctions.php");
    include_once("../includes/session.php");
    require_once 'vendor/autoload.php'; // Inclure la bibliothèque JWT

    use Firebase\JWT\JWT;

    $pseudo = $_POST['username'];
    $motdepasse = $_POST['password'];

    // Rechercher l'utilisateur dans la base de données
    $query_select_user = "SELECT * FROM sae_users WHERE pseudo = :pseudo";
    $stmt_select_user = $cnx->prepare($query_select_user);
    $stmt_select_user->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
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
                $tokenData = [
                    'pseudo' => $pseudo,
                    'expiration' => time() + (60 * 60) // expiration du jeton dans 1 heure
                ];
            
                // Générer le jeton JWT avec les revendications
                $jwtToken = JWT::encode($tokenData, 'votre_clé_secrète');
            
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