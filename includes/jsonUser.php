<?php
    include_once("class/User.php");
    session_start();
    $user = User::createUserFromUser(unserialize($_SESSION['user']));
    $userData = [
        $user->getIdUser(),
        $user->getPseudo(),
        $user->getImageData(),
    ];

    echo json_encode($userData);
?>