<?php
include('connexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idAmi = $_POST['idAmi'];
    $idUtilisateur = $_SESSION['id'];

    // Insérer la demande d'amitié dans la base de données
    $stmt = $dbh->prepare("INSERT INTO friendship (user_id1, user_id2, action_user_id, status) VALUES (:idUtilisateur, :idAmi, :idUtilisateur, 'En attente')");
    $stmt->bindParam(':idUtilisateur', $idUtilisateur);
    $stmt->bindParam(':idAmi', $idAmi);
    $stmt->execute();

    echo "Demande d'amitié envoyée.";
}
?>
