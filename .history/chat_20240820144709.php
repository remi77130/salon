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
    <?php
            require 'connect_bdd.php';
            $result = $conn->query("
                SELECT u.username, u.department, u.ville 
                FROM users u
            ");

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ville']) . "</td>"; // Affiche la ville
                echo "</tr>";
            }

            $conn->close();
            ?>

<script src="fonction/function.js"></script>
</body>
</html>
