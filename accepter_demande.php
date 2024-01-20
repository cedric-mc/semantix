<?php
include('connexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idDemande = $_POST['id'];

    // Mettre à jour le statut de la demande d'amitié dans la base de données
    $stmt = $dbh->prepare("UPDATE friendship SET status = 'Ami' WHERE id = :idDemande");
    $stmt->bindParam(':idDemande', $idDemande);
    $stmt->execute();

    echo "Demande d'amitié acceptée.";
}
?>
