<?php
session_start();
include 'connexion.php';

if (!isset($_SESSION['utilisateur_id'])) {
    die("Utilisateur non connecté.");
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$photo_id = intval($_POST['photo_id']);

// Vérifier si l'utilisateur a déjà liké la photo
$query = "SELECT * FROM like_photo WHERE utilisateur_id = ? AND photo_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $utilisateur_id, $photo_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Supprimer le like
    $query = "DELETE FROM like_photo WHERE utilisateur_id = ? AND photo_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $utilisateur_id, $photo_id);
    $stmt->execute();

    echo "unliked";
} else {
    // Ajouter le like
    $query = "INSERT INTO like_photo (utilisateur_id, photo_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $utilisateur_id, $photo_id);
    $stmt->execute();

    // Ajouter une notification
    $query = "INSERT INTO notifications (utilisateur_id, type, photo_id) VALUES (?, 'like', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $utilisateur_id, $photo_id);
    $stmt->execute();

    echo "liked";
}
?>
