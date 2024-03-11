<?php
    // Requêtes SQL pour le classement
    $top10Scores = "SELECT pseudo, MAX(score) AS score FROM sae_scores s, sae_users u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 10;";
    $top10Users = "SELECT pseudo, SUM(score) AS score FROM sae_scores s, sae_users u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 10;";

    // Requêtes SQL pour le profil
    $lastConnexionProfil = "SELECT (SELECT MAX(timestamp) FROM sae_traces WHERE utilisateur_id = u.num_user AND action = 1) AS lastConnexion FROM sae_users u, sae_traces t WHERE u.num_user = t.utilisateur_id AND action = 1 AND pseudo = :pseudo";
    $scoreProfil = "SELECT MIN(score) AS minS, MAX(score) AS maxS, AVG(score) AS avgS, COUNT(score) AS nbParties FROM sae_scores s, sae_users u WHERE u.num_user = s.num_user AND u.pseudo = :pseudo;";
    $top3ScoresProfil = "SELECT pseudo, MAX(score) AS score FROM sae_scores s, sae_users u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 3;";

    // Requêtes SQL pour les traces
    $allUsersTrace = "SELECT timestamp, ip_adress, utilisateur_id, a.action, pseudo FROM sae_traces t, sae_users u, sae_action a WHERE t.utilisateur_id = u.num_user AND t.action = a.id ORDER BY t.id DESC LIMIT 500;";
    $userTrace = "SELECT timestamp, ip_adress, a.action FROM sae_traces t, sae_action a WHERE t.utilisateur_id = :num_user AND t.action = a.id ORDER BY t.id DESC LIMIT 500;";

    // Requêtes SQL pour les amis
    $allFriends = "SELECT u.pseudo u.photo, f.statut FROM sae_friendship AS f JOIN sae_users AS u ON u.num_user = f.user_id OR u.num_user = f.friend_id WHERE (f.user_id = :num_user OR f.friend_id = :num_user) AND (f.user_id != :num_user AND f.friend_id = :num_user) AND u.num_user != :num_user;";
    $canAddFriend = "SELECT u.* FROM sae_users u, sae_friendship f WHERE (u.num_user = f.user_id OR u.num_user = f.friend_id) AND f.statut = 0 AND u.num_user != 22 AND u.statut = 1";
    $addFriend = "INSERT INTO sae_friendship (user_id, friend_id, statut) VALUES (:num_user, :friend_id, 0);";
    $acceptFriend = "UPDATE sae_friendship SET statut = 1 WHERE user_id = :num_user AND friend_id = :friend_id;";
    $refuseFriend = "DELETE FROM sae_friendship WHERE user_id = :num_user AND friend_id = :friend_id;";
    $deleteFriend = "DELETE FROM sae_friendship WHERE user_id = :num_user AND friend_id = :friend_id OR user_id = :friend_id AND friend_id = :num_user;";
    $listUsers = "SELECT * FROM sae_users WHERE num_user NOT IN (SELECT friend_id FROM sae_friendship WHERE user_id = :idUser) AND num_user <> :num_user ORDER BY num_user;";
    $listUsersPseudo = "SELECT num_user, pseudo FROM sae_users WHERE num_user NOT IN (SELECT friend_id FROM sae_friendship WHERE user_id = :idUser) AND num_user <> :num_user ORDER BY pseudo;";

    // Requêtes SQL pour le changement d'email
    $emailExists = "SELECT email FROM sae_users WHERE email = :email;";
    $changeEmail = "UPDATE sae_users SET email = :email WHERE pseudo = :pseudo;";
?>