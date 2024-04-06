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
        if ($image_data === false) { // Vérifier si le fichier a été lu
            echo "<script>alert('Erreur lors de la lecture du fichier');</script>";
            echo "<script>window.location.replace('./');</script>";
            exit();
        }
        // Si le fichier n'est pas une image
        if (exif_imagetype($_FILES["photo"]["tmp_name"]) === false) {
            echo "<script>alert('Le fichier n\'est pas une image');</script>";
            echo "<script>window.location.replace('./');</script>";
            exit();
        }
        // Si le fichier est trop grand (LONGBLOB = 4 Go)
        if ($_FILES["photo"]["size"] > 4294967295) {
            echo "<script>alert('Le fichier est trop grand');</script>";
            echo "<script>window.location.replace('./');</script>";
            exit();
        }

        $stmt = $cnx->prepare("UPDATE sae_users SET photo = ? WHERE num_user = ?");
        $stmt->execute(array($image_data, $user->getIdUser()));
        // Vérifier les erreurs de la requête
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] !== '00000') { // Vérifier si la requête a échoué
            echo "<script>alert('Erreur lors de la modification de la photo');</script>";
            echo "<script>window.location.replace('./');</script>";
            exit();
        }
        $user->setImageData($image_data);
        $user->logging($cnx, 12);
        $_SESSION["user"] = serialize($user);
        echo "<script>alert('Photo modifiée avec succès');</script>";
        echo "<script>window.location.replace('./');</script>";
        exit();
    }
    header("Location: ./");
    exit();
?>