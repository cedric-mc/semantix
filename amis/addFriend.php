<?php
if (!isset($_GET['idUser'])) {
    header('Location: ./');
    exit;
}
$idUser = $_GET['idUser'];
?>