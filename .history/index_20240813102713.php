<!DOCTYPE html>

<!-- Nous allons créer une page HTML qui contient un tableau pour 
 afficher les utilisateurs récupérés par l'API. Ce tableau sera mis à jour 
 automatiquement toutes les minutes grâce à AJAX. -->
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>

<link rel="stylesheet" href="style/salon/.css"> 
</head>
<body>
    <h2>Utilisateurs Récemment Inscrits</h2>
    <table id="users-table">
        <thead>
            <tr>
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

    <script>
        function fetchUsers() {
            fetch('fetch_users.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('#users-table tbody');
                    tbody.innerHTML = ''; // Vider le tableau

                    data.forEach(user => {
                        const row = document.createElement('tr');

                        const usernameCell = document.createElement('td');
                        usernameCell.textContent = user.username;
                        row.appendChild(usernameCell);

                        const ageCell = document.createElement('td');
                        ageCell.textContent = user.age;
                        row.appendChild(ageCell);

                        const departmentCell = document.createElement('td');
                        departmentCell.textContent = user.department;
                        row.appendChild(departmentCell);

                        const genderCell = document.createElement('td');
                        genderCell.textContent = user.gender === 'male' ? 'Homme' : (user.gender === 'female' ? 'Femme' : 'Autre');
                        row.appendChild(genderCell);

                        tbody.appendChild(row);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des utilisateurs:', error));
        }

        // Charger les utilisateurs au chargement de la page
        fetchUsers();

        // Mettre à jour les utilisateurs toutes les minutes
        setInterval(fetchUsers, 60000); // 60000 ms = 1 minute
    </script>
</body>
</html>
