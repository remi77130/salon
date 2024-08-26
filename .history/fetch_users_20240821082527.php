<?php
// Inclure le fichier de connexion à la base de données
require 'connect_bdd.php';

// Requête pour récupérer les 50 derniers utilisateurs inscrits, incluant l'avatar
$sql = "SELECT username, avatar, age, department, ville_users, gender FROM users ORDER BY created_at DESC LIMIT 50";
$result = $conn->query($sql);


$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$(document).ready(function() {
    // Requête AJAX pour récupérer les utilisateurs
    $.ajax({
        url: 'fetch_users.php',
        method: 'GET',
        dataType: 'json',
        success: function(users) {
            var userTableBody = $('#user-table-body');
            userTableBody.empty();

            users.forEach(function(user) {
                var rowClass = (user.gender === 'female') ? 'female-row' : '';
                var rowHtml = `
                    <tr class="${rowClass}">
                        <td>${user.username}</td>
                        <td><img src="${user.avatar}" alt="Avatar" width="50" height="50"></td>
                        <td>${user.age}</td>
                        <td>${user.department}</td>
                        <td>${user.ville_users}</td>
                    </tr>
                `;
                userTableBody.append(rowHtml);
            });
        },
        error: function(xhr, status, error) {
            console.error("Erreur lors de la récupération des utilisateurs : ", error);
        }
    });
});

// Retourner les résultats au format JSON
header('Content-Type: application/json');
echo json_encode($users);

$conn->close();
?>


