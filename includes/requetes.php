<?php
    $top10Scores = "SELECT pseudo, MAX(score) AS score FROM SAE_SCORES s, SAE_USERS u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 10;";
    $top10Users = "SELECT pseudo, SUM(score) AS score FROM SAE_SCORES s, SAE_USERS u WHERE u.num_user = s.num_user GROUP BY pseudo ORDER BY score DESC LIMIT 10;";
?>