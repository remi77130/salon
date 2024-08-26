<!DOCTYPE html>

<!-- Nous allons créer une page HTML qui contient un tableau pour 
 afficher les utilisateurs récupérés par l'API. Ce tableau sera mis à jour 
 automatiquement toutes les minutes grâce à AJAX. -->

 
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>

<link rel="stylesheet" href="style/salon.css"> 
</head>
<body>
    <h2>Utilisateurs Récemment Inscrits</h2>
    <table id="users-table">
        <thead>
            <tr>
                <th>Avatar</th> 
                <th>Pseudo</th>
                <th>Âge</th>
                <th>Département</th>
                <th>Ville</th>
                <th>Sexe</th>
            </tr>
        </thead>
        <tbody>
            <!-- Les utilisateurs seront insérés ici par JavaScript -->
        </tbody>
    </table>


    <div id="salons">
        <h2>Salons de Discussion</h2>
        <button onclick="createSalon()">Créer un Salon</button> <!-- Bouton pour créer un salon -->
        <div id="salons-list">
            <!-- Les salons seront affichés ici -->

           
        </div>
    </div>

<script src="fonction/function.js">
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
    });</script>
</body>
</html>
