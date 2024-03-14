<?php
    include_once("../class/User.php");
    include_once("../includes/conf.php");
    session_start();
    if (!isset($_SESSION["user"])) {
        header("Location: ./");
        exit();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["photo"])) {
        $user = User::createUserFromUser(unserialize($_SESSION["user"]));
        $image_data = file_get_contents($_FILES["photo"]["tmp_name"]);

        $stmt = $cnx->prepare("UPDATE sae_users SET photo = ? WHERE num_user = ?");
        $stmt->execute(array($image_data, $user->getIdUser()));
        // Vérifier les erreurs de la requête
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] !== '00000') {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $errorInfo[2]);
        }
        $user->setImageData($image_data);
        $_SESSION["user"] = serialize($user);
        echo "<script>alert('Photo modifiée avec succès');</script>";
        echo "<script>window.location.replace('./');</script>";
        exit();
    }
    header("Location: ./");
    exit();
?>