<?php
// Inclure le fichier de connexion à la base de données
require 'connect_bdd.php';


// fichier fetch_users.php qui retournera les 50 derniers utilisateurs inscrits au format JSON. 
//Ce fichier sera appelé via AJAX pour mettre à jour la liste des utilisateurs sur la page.


// Requête pour récupérer les 50 derniers utilisateurs inscrits, triés par date d'inscription (du plus récent au plus ancien)
$sql = "SELECT username, avatar, age, department, gender FROM users ORDER BY created_at DESC LIMIT 50";
$result = $conn->query($sql);

$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Retourner les résultats au format JSON
header('Content-Type: application/json');
echo json_encode($users);

$conn->close();
?>
