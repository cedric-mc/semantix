<?php
include('include/connexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idDemande = $_POST['id'];

    // Supprimer la relation d'amitié de la base de données
    $stmt = $dbh->prepare("DELETE FROM friendship WHERE id = :idDemande");
    $stmt->bindParam(':idDemande', $idDemande);
    $stmt->execute();

    echo "Ami supprimé avec succès.";
}
?>
