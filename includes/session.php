<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Définit la durée de vie de la session en secondes (ici, une heure)
$session_duration = 3600; // 1 heure

// Définit la durée de vie du cookie de session en secondes
session_set_cookie_params($session_duration);

// Actualise la session
session_regenerate_id(true);

?>