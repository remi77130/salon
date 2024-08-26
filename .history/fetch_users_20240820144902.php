<?php
require 'connect_bdd.php';
session_start();

$user_id = $_SESSION['user_id']; // Assurez-vous que l'ID utilisateur est stocké dans la session

$stmt = $conn->prepare("SELECT username, department, ville FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_info = $result->fetch_assoc();
    echo json_encode($user_info);
} else {
    echo json_encode(['error' => 'Utilisateur non trouvé']);
}

$stmt->close();
$conn->close();
?>
