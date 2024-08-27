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
<h2>Utilisateurs Récemment Inscrit </h2>


<div class="filter_chat">
<!-- Ajout du Sélecteur de Filtre -->
<label for="gender-filter">Filtrer par sexe :</label>
    <select id="gender-filter" onchange="filterUsersByGender()">
        <option value="all">Tous</option>
        <option value="male">Homme</option>
        <option value="female">Femme</option>
    </select>




  <!-- Ajout du Sélecteur de Filtre pour le Département -->
  <label for="department-filter">Filtrer par département :</label>
    <select id="department-filter" onchange="filterUsersByDepartment()">
        <option value="all">Tous les départements</option>
        <!-- Les options seront remplies dynamiquement -->
    </select>
</div>

  <!-- Conteneur pour les étiquettes des profils sélectionnés -->
  <div classe="conteneur_selected_profil" id="selected-profiles" style="margin-top: 20px;"></div>



<table id="users-table">
<thead>
    <tr>
            <th>Avatar</th> 
            <th>Pseudo</th>
            <th>Âge</th>
            <th>Dpt</th>
            <th>Ville</th>
    </tr>
    </thead>
    <tbody>
    <!-- Les utilisateurs seront insérés ici par JavaScript -->
    </tbody>

</table>

<div id="chat-window" style="display:none;">
            <div id="chat-messages"></div>
            <input id="chat-input" type="text" placeholder="Entrez votre message">
            <button id="send-button">Envoyer</button>
        </div>


    <div id="salons">
        <h2>Salons de Discussion</h2>
        <button onclick="createSalon()">Créer un Salon</button> <!-- Bouton pour créer un salon -->
        <div id="salons-list">
        <!-- Les salons seront affichés ici -->

           
        </div>
    </div>

       <script src="/NodeProjects\salon_chat\node_modules\socket.io\client-dist\socket.io.js"></script>


<script src="fonction/function.js"></script>


</body>
</html>
