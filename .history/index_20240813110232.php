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
            <th>Avatar</th> <!-- Nouvelle colonne pour l'avatar -->
                <th>Pseudo</th>
                <th>Âge</th>
                <th>Département</th>
                <th>Sexe</th>
            </tr>
        </thead>
        <tbody>
            <!-- Les utilisateurs seront insérés ici par JavaScript -->
        </tbody>
    </table>
<script src="fonction/function.js"></script>
</body>
</html>
