<?php
    $top10Scores = "SELECT pseudo, MAX(score) AS score FROM SAE_SCORES s, SAE_USERS u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 10;";
    $top10Users = "SELECT pseudo, SUM(score) AS score FROM SAE_SCORES s, SAE_USERS u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 10;";
    $profil = "SELECT (SELECT MAX(timestamp) FROM SAE_TRACES WHERE utilisateur_id = u.num_user AND action = 'Connexion au Site') AS lastConnexion FROM SAE_USERS u, SAE_TRACES t WHERE u.num_user = t.utilisateur_id AND action = 'Connexion au Site' AND pseudo = :pseudo"
?>