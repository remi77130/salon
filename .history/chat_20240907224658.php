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
	<title>Liste des Utilisateurs actifs</title>
	<link rel="stylesheet" href="style/salon.css">
	<style>
		.avatar64 {
			width: 64px;
			height: 64px;
			border-radius: 64px;
		}
		.avatar32 {
			width: 32px;
			height: 32px;
			border-radius: 32px;
		}
		.avatar16 {
			width: 16px;
			height: 16px;
			border-radius: 16px;
		}

			 /* Basic styles for the modal window */
		 .modal {
			 display: none;
			 position: fixed;
			 z-index: 9999;
			 left: 0;
			 top: 0;
			 width: 100%;
			 height: 100%;
			 background-color: rgba(0, 0, 0, 0.8); /* Black background with opacity */
			 justify-content: center;
			 align-items: center;
		 }

		.chat-popup {
			background-color: white;
			padding: 20px;
			border-radius: 10px;
			width: 750px;
			box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
			max-width: 80%;
		}

		.chat-header {
			display: flex;
			align-items: center;
			margin-bottom: 15px;
		}

		.avatar {
			width: 40px;
			height: 40px;
			border-radius: 50%;
			margin-right: 10px;
		}

		.username {
			font-size: 18px;
			font-weight: bold;
		}

		.chat-content {
			height: 200px;
			overflow-y: auto;
			border: 1px solid #ddd;
			padding: 10px;
			margin-bottom: 15px;
			background-color: #f9f9f9;
		}

		.chat-footer {
			display: flex;
		}

		.chat-input {
			flex: 1;
			padding: 5px;
			border-radius: 5px;
			border: 1px solid #ccc;
			margin-right: 5px;
		}

		.send-btn {
			padding: 5px 10px;
			background-color: #4CAF50;
			color: white;
			border: none;
			border-radius: 5px;
			cursor: pointer;
		}

		.close-btn {
			position: fixed;
			top: 0;
			right: 0;
			background-color: red;
			color: white;
			border: none;
			padding: 10px 20px;
			cursor: pointer;
			z-index: 10000;
		}

		.message {
			display: flex;
			flex-direction: column;
			padding: 10px;
			margin-bottom: 10px;
			max-width: 70%;
			border-radius: 10px;
		}

		.sent {
			align-self: flex-end;
			background-color: #d1e7dd;
			border: 1px solid #badbcc;
			margin-left: auto;
		}

		.received {
			align-self: flex-start;
			background-color: #f8d7da;
			border: 1px solid #f5c2c7;
		}
		#selected-profiles {
			padding: 10px;
			display: flex;
			gap:10px;
		}
		#selected-profiles div {
			background: yellow;
			color:black;
			cursor: pointer;
			border: 1px solid black;
			border-radius: 5px;
			padding: 5px;
		}
		#selected-profiles div:hover {
			background: orange;
		}
		tr {
			cursor: pointer;
		}


	</style>
</head>
<body>

<div class="container_profile_parent">
	<h2>Utilisateurs Récemment Inscrit </h2>
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
		<button onclick="createSalon()">Créer un Salon</button> <!-- Bouton pour créer un salon -->
		<div id="salons-list">
			<!-- Les salons seront affichés ici -->


		</div>
	</div>


</div>  <!-- fIN containeur_profil -->

<div class="options_menu_chat">
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



	function createChat(user, display = true) {
			let id = `chat_${user.id}`;
			user_private = user;
			$chat = $(`#${id}`);
			$('.modal').hide();
			if (!$chat.length) {
				let template = `
				<div id="${id}" class="modal">
					<div class="chat-popup">
						<div class="chat-header">
							<img src="${user.avatar}" alt="Avatar" class="avatar64">
							<div class="username">${user.username}</div>
						</div>
						<div class="chat-content"></div>
						<div class="chat-footer">
							<input type="text" class="chat-input" placeholder="Tapez votre message...">
							<button class="send-btn">Envoyer</button>
						</div>
					</div>
					<button class="close-btn" onclick="closeModal()">Fermer</button>
				</div>`;
				$('body').append(template);
			}
			if (display) {
				document.getElementById(`${id}`).style.display = 'flex';
			}
	}

	function closeModal() {
		user_private = false;
		$('.modal').hide();
	}

	function addUser(user) {
		users[user.id] = user;
		let class_user = (user.gender==='female') ? 'female-row': 'male-row';
		$userlistContainer.append(`
			<tr class="user ${class_user}" data-userid="${user.id}" data-username="${user.username}" data-avatar="${user.avatar}" data-age="${user.age}" data-ville="${user.ville}" data-dep="${user.dep}  data-gender="${user.gender}">
				<td><img class="avatar16" src="${user.avatar}" alt="${user.username}"></td>
				<td><div><b>${user.username}</b></div></td>
				<td><div>${user.age} ans</div></td>
				<td><div>${user.dep}</div></td>
				<td><div>${user.ville}</div></td>
			</tr>`);
		addDepartement(user.dep);
	}

	function addDepartement(dep) {
		// Select the #department-filter element
		let $departmentFilter = $('#department-filter');

		// Check if an option with the value 'dep' already exists
		if ($departmentFilter.find(`option[value='${dep}']`).length === 0) {
			// If not, create a new option element
			let newOption = $('<option></option>')
				.attr('value', dep) // Set the value attribute
				.text(dep); // Set the text of the option

			// Append the new option to the select element
			$departmentFilter.append(newOption);
		}
	}


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

	function addNotification(user) {
		$notications = $('#selected-profiles');
		if(!$notications.find(`div[data-userid=${user.id}]`).length) {
			$notications.append(`<div class="notification" data-userid="${user.id}" data-username="${user.username}">${user.username}</div>`);
		}
	}


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

</body>
</html>