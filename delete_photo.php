<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur ou l'administrateur est connecté
if (!isset($_SESSION['utilisateur_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifiez si l'ID de la photo est défini
if (!isset($_POST['photo_id'])) {
    die("ID de la photo non spécifié.");
}

$photo_id = intval($_POST['photo_id']);

// Supprimer la photo de l'album
if (isset($_SESSION['utilisateur_id'])) {
    $query_delete = "DELETE FROM albums WHERE id = ? AND utilisateur_id = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param("ii", $photo_id, $_SESSION['utilisateur_id']);
} elseif (isset($_SESSION['admin_id'])) {
    $query_delete = "DELETE FROM albums WHERE id = ? AND admin_id = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param("ii", $photo_id, $_SESSION['admin_id']);
}

if ($stmt_delete->execute()) {
    // Rediriger pour éviter la resoumission du formulaire
    header("Location: vous.php");
    exit();
} else {
    die("Erreur lors de la suppression de la photo.");
}
?>
