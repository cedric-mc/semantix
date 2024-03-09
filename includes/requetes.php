<?php
    // Requêtes SQL pour le classement
    $top10Scores = "SELECT pseudo, MAX(score) AS score FROM sae_scores s, sae_users u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 10;";
    $top10Users = "SELECT pseudo, SUM(score) AS score FROM sae_scores s, sae_users u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 10;";

    // Requêtes SQL pour le profil
    $lastConnexionProfil = "SELECT (SELECT MAX(timestamp) FROM sae_traces WHERE utilisateur_id = u.num_user AND action = 'Connexion au Site') AS lastConnexion FROM sae_users u, sae_traces t WHERE u.num_user = t.utilisateur_id AND action = 'Connexion au Site' AND pseudo = :pseudo";
    $scoreProfil = "SELECT MIN(score) AS minS, MAX(score) AS maxS, AVG(score) AS avgS, COUNT(score) AS nbParties FROM sae_scores s, sae_users u WHERE u.num_user = s.num_user AND u.pseudo = :pseudo;";
    $top3ScoresProfil = "SELECT pseudo, MAX(score) AS score FROM sae_scores s, sae_users u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 3;";

    // Requêtes SQL pour les traces
    $allUsersTrace = "SELECT timestamp, ip_adress, utilisateur_id, action, pseudo FROM sae_traces, sae_users WHERE utilisateur_id = num_user ORDER BY id DESC LIMIT 500;";
    $userTrace = "SELECT timestamp, ip_adress, action FROM sae_traces WHERE utilisateur_id = :num_user ORDER BY id DESC LIMIT 500;";

    // Requêtes SQL pour les amis
    $allFriends = "SELECT * FROM sae_friendship f, sae_users u WHERE f.user_id = u.num_user OR f.friend_id = :num_user AND f.statut = 1;";
    $canAddFriend = "SELECT * FROM sae_friendship f, sae_users u WHERE f.user_id = u.num_user OR f.friend_id = :num_user AND f.statut = 0;";
    $addFriend = "INSERT INTO sae_friendship (user_id, friend_id, statut) VALUES (:num_user, :friend_id, 0);";
    $acceptFriend = "UPDATE sae_friendship SET statut = 1 WHERE user_id = :num_user AND friend_id = :friend_id;";
    $deleteFriend = "DELETE FROM sae_friendship WHERE user_id = :num_user AND friend_id = :friend_id OR user_id = :friend_id AND friend_id = :num_user;";
    $listUsers = "SELECT * FROM sae_users WHERE num_user NOT IN (SELECT friend_id FROM sae_friendship WHERE user_id = :idUser) AND num_user <> :idUser;";
?>