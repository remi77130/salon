<?php session_start();
$myuser = $_SESSION['user'];
?>

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

<div class="container_profile_parent">
	<h2>Utilisateurs en lignes </h2>
	<!-- Conteneur pour le profil sélectionné -->

	<div class="container_profil" id="container_profil" style="display:none;">
	</div>


	<div class="filter_chat">
		<!-- Ajout du Sélecteur de Filtre -->
		<label for="gender-filter">Genre :</label>

		<select id="gender-filter" onchange="applyFilters()">
			<option value="all">Tous</option>
			<option value="male">Homme</option>
			<option value="female">Femme</option>
		</select>


		<!-- Ajout du Sélecteur de Filtre pour le Département -->
		<label for="department-filter">Dpt :</label>
		<select id="department-filter" onchange="applyFilters()">
			<option value="all">Tous</option>
			<!-- Les options seront remplies dynamiquement -->
		</select>


		<!-- Ajout du Sélecteur de Filtre pour l'Âge -->
		<label for="age-filter">Âge :</label>
		<select id="age-filter" onchange="applyFilters()">
			<option value="all">Tous</option>
			<option value="-30">Moins de 30 ans</option>
			<option value="30-40">30 à 40 ans</option>
			<option value="45+">Plus de 45 ans</option>
		</select>


	</div>


	<!-- Conteneur pour les étiquettes des profils sélectionnés -->
	<div classe="conteneur_selected_profil" id="selected-profiles"></div>


	<div class="containeur_profil">


		<div class="container_users-table">
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

		</DIV>


	</div>  <!-- FIN CONTAINER 8PROFIL8 PARENT -->


	<div id="chat-window" style="display:none;">
		<div id="chat-messages"></div>
		<input name="message" id="chat-input" type="text" placeholder="Entrez votre message">
		<button id="send-button">Envoyer</button>
	</div>



	<div class="containeur_salons" id="salons">
		<h2>Salons de Discussion</h2>
		<button class="button_create_salons" id="create_salons" onclick="openSalonModal()">Créer un Salon</button>
		<div id="salons-list">
			<!-- Les salons seront affichés ici -->


		</div>
	</div>


<!-- Fenêtre modale pour créer un salon -->
<div id="salon-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeSalonModal()">&times;</span>
        <h3>Créer un Salon</h3>
        <form id="salon-form">
            <input type="text" id="salon-name" placeholder="Nom du salon" required>
            <button type="submit">Créer</button>
        </form>
    </div>
</div>










</div>  <!-- fIN containeur_profil -->

<div class="nav_bottom_chat">
	<nav>

		<button><a href="">Salons</a></button>
		<button><a href="">Options</a></button>
		<button>
			<a href="">Connécté</a></button>

	</nav>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
<script src="function/function.js"></script>
<script>
	const $userlistContainer = $('#users-table>tbody');

	var myuser = {};
	myuser = <?php echo json_encode($myuser);?>;
	var users = {};
	var user_private = false;

	const socket = io('https://tchat-direct.com:2053', {query: {user: JSON.stringify(myuser)}});




	socket.on('addUser', function (user) {
		addUser(user);
	});

	socket.on('removeUser', function (user) {
		$(`.user[data-userid=${user.id}]`).remove();
		delete users[user.id];
	});


	socket.on('users', function (users) {
		$userlistContainer.empty();
		Object.values(users).forEach(user => {
			addUser(user);
		});
	});


	socket.on('private', function(user, message) {
		//createChat(user, false);
		let $chat = $(`#chat_${user.id} .chat-content`);
		let classe = (user.id === myuser.id) ? 'sent':'received';
		if ($chat.is(":visible")) {
			$chat.append(`<div class="message ${classe}">${message}</div>`);
			$chat.scrollTop($chat[0].scrollHeight);
		} else {
			createChat(user, false);
			let $chat = $(`#chat_${user.id} .chat-content`);
			$chat.append(`<div class="message ${classe}">${message}</div>`);
			$chat.scrollTop($chat[0].scrollHeight);
			addNotification(user);
		}
	});


	$(document).on('click', '.send-btn', (e)=>{
		let input = $(e.currentTarget).parent().find('input');
		if (!input.val()) return;
		socket.emit('private', user_private.username, input.val());
		let $chat = $(`#chat_${user_private.id} .chat-content`);
		$chat.append(`<div class="message sent">${input.val()}</div>`);
		$chat.scrollTop($chat[0].scrollHeight);
		$(input).val('');
	});

	$(document).on('keypress', '.chat-input', function (e) {
		if (e.key === 'Enter' && $(this).val().trim() !== '') {
			let input = $(e.currentTarget).parent().find('input');
			if (!input.val()) return;
			socket.emit('private', user_private.username, input.val());
			let $chat = $(`#chat_${user_private.id} .chat-content`);
			$chat.append(`<div class="message sent">${input.val()}</div>`);
			$chat.scrollTop($chat[0].scrollHeight);
			$(input).val('');
		}
	});

	$userlistContainer.on('click', '.user', function() {
		const id = $(this).data('userid');
		let user = users[id];
		createChat(user);
	});
	$(document).on('click', '.notification', (e)=>{
		$(e.currentTarget).remove();
	   let user_id = $(e.currentTarget).data('userid');
	   let user = users[user_id];
	   createChat(user);
	});

</script>


<script>
    const currentUserId = <?php echo $_SESSION['user_id']; ?>; // ID de l'utilisateur connecté
</script>

</body>
</html>