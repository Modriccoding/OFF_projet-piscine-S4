<?php
include 'connexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $photo_id = $_POST['photo_id'];
    $legende = $_POST['legende'] ?? '';

    $query_update_legende = "UPDATE albums SET legende = ? WHERE id = ?";
    $stmt_update_legende = $conn->prepare($query_update_legende);
    $stmt_update_legende->bind_param("si", $legende, $photo_id);
    $stmt_update_legende->execute();

    header("Location: vous.php");
    exit();
}
?>
