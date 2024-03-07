<?php
    // Requêtes SQL pour le classement
    $top10Scores = "SELECT pseudo, MAX(score) AS score FROM SAE_SCORES s, SAE_USERS u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 10;";
    $top10Users = "SELECT pseudo, SUM(score) AS score FROM SAE_SCORES s, SAE_USERS u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 10;";

    // Requêtes SQL pour le profil
    $lastConnexionProfil = "SELECT (SELECT MAX(timestamp) FROM SAE_TRACES WHERE utilisateur_id = u.num_user AND action = 'Connexion au Site') AS lastConnexion FROM SAE_USERS u, SAE_TRACES t WHERE u.num_user = t.utilisateur_id AND action = 'Connexion au Site' AND pseudo = :pseudo";
    $scoreProfil = "SELECT MIN(score) AS minS, MAX(score) AS maxS, AVG(score) AS avgS, COUNT(score) AS nbParties FROM SAE_SCORES s, SAE_USERS u WHERE u.num_user = s.num_user AND u.pseudo = :pseudo;";
    $top3ScoresProfil = "SELECT pseudo, MAX(score) AS score FROM SAE_SCORES s, SAE_USERS u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 3;";

    // Requêtes SQL pour les traces
    $allUsersTrace = "SELECT timestamp, ip_adress, utilisateur_id, action, pseudo FROM SAE_TRACES, SAE_USERS WHERE utilisateur_id = num_user ORDER BY id DESC LIMIT 500;";
    $userTrace = "SELECT timestamp, ip_adress, action FROM SAE_TRACES WHERE utilisateur_id = :num_user ORDER BY id DESC LIMIT 500;";
?>