<?php
    include_once("../includes/conf.php");
    include("../includes/fonctions.php");
    // Erreur PHP
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    if (isset($_GET['code'])) {
        $code_confirmation = $_GET['code'];
    
        // Vérifier le code dans la table confirmation_codes
        $query_check_code = "SELECT * FROM sae_confirmation_codes WHERE code = :code";
        $stmt_check_code = $cnx->prepare($query_check_code);
        $stmt_check_code->bindParam(":code", $code_confirmation);
        $stmt_check_code->execute();
        $confirmation_data = $stmt_check_code->fetch(PDO::FETCH_ASSOC);
    
        if ($confirmation_data) {
            // Vérifier si le code n'a pas expiré (20 minutes dans cet exemple)
            $expiration_time = strtotime($confirmation_data['created_at']) + (20 * 60); // 20 minutes en secondes
            $current_time = time();

            if ($current_time <= $expiration_time) {
                // Mise à jour du statut à 1 dans la table users
                $query_update_statut = "UPDATE sae_users SET statut = 1 WHERE num_user = :num_user";
                $stmt_update_statut = $cnx->prepare($query_update_statut);
                $stmt_update_statut->bindParam(":num_user", $confirmation_data['num_user']);
                $stmt_update_statut->execute();
        
                // Supprimer l'enregistrement dans la table code_confirmation
                $query_delete_confirmation = "DELETE FROM sae_confirmation_codes  WHERE code = :code";
                $stmt_delete_confirmation = $cnx->prepare($query_delete_confirmation);
                $stmt_delete_confirmation->bindParam(":code", $code_confirmation);
                $stmt_delete_confirmation->execute();

                // Journalisation
                trace($confirmation_data['num_user'], 9, $cnx);
        
                header('Location: ../connexion/?erreur=4');
            } else {
                // Code de confirmation expiré, mais supprimer quand même l'enregistrement
                $query_delete_confirmation = "DELETE FROM sae_confirmation_codes WHERE code = :code";
                $stmt_delete_confirmation = $cnx->prepare($query_delete_confirmation);
                $stmt_delete_confirmation->bindParam(":code", $code_confirmation);
                $stmt_delete_confirmation->execute();

                header('Location: ../?erreur=3');
                exit();
            }
        } else {
            // Code de confirmation invalide l’utilisateur déjà confirmé
            header('Location: ../?erreur=5');
            exit();
        }
    } else {
        header('Location: ../');
        exit();
    }
?>