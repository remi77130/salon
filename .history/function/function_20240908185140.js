
// Fonction pour afficher la div de profil
function showProfileContainer(userId) {
	fetch(`get_user_info.php?user_id=${userId}`)
		.then(response => response.json())
		.then(data => {
			if (data.error) {
				alert(data.error);
				return;
			}

			const container = document.getElementById('container_profil');
			container.innerHTML = `
                <div class="profile-content">
                    <button class="close-btn" onclick="closeProfileContainer()">Fermer</button>
                    <div>
                        <img src="${sanitize(data.avatar)}" alt="${sanitize(data.username)}" class="avatar">
                        <h3>${sanitize(data.username)}</h3>
                    </div>

                    
                    <div id="message_user">
                    
                    
                    
                    </div> <!-- Fenêtre de messagerie -->
                    <div id="chat-messages"></div>
                    <input id="chat-input" type="text" placeholder="Entrez votre message">
                    <button id="send-button">Envoyer</button>
                </div>
            `;
			container.style.display = 'block';

			document.getElementById('send-button').addEventListener('click', () => {
				const messageInput = document.getElementById('chat-input');
				const message = messageInput.value;
				if (message.trim()) {
					socket.emit('chatMessage', {to: userId, message});
					messageInput.value = '';
				}
			});

			socket.on('chatMessage', ({from, message}) => {
				if (from === userId) {
					const messagesContainer = document.getElementById('chat-messages');
					const messageElement = document.createElement('div');
					messageElement.textContent = message;
					messagesContainer.appendChild(messageElement);
				}
			});
		})
		.catch(error => console.error('Erreur lors du chargement du profil:', error));
}

function closeProfileContainer() {
	document.getElementById('container_profil').style.display = 'none';
}

// Fonction pour assainir les entrées utilisateur
function sanitize(input) {
	const element = document.createElement('div');
	element.textContent = input;
	return element.innerHTML;
}



// Fonction pour appliquer les filtres
function applyFilters() {
	const genderFilter = document.getElementById('gender-filter').value;
	const departmentFilter = document.getElementById('department-filter').value;
	const ageFilter = document.getElementById('age-filter').value;
	const rows = document.querySelectorAll('#users-table tbody tr');

	rows.forEach(row => {
		const genderClass = row.classList.contains('female-row') ? 'female' : row.classList.contains('male-row') ? 'male' : 'other';
		const department = row.querySelector('td:nth-child(4)').textContent;
		const age = parseInt(row.querySelector('td:nth-child(3)').textContent);

		const genderMatch = (genderFilter === 'all' || genderFilter === genderClass);
		const departmentMatch = (departmentFilter === 'all' || departmentFilter === department);

		let ageMatch = true;
		if (ageFilter === '-30') {
			ageMatch = age < 30;
		} else if (ageFilter === '30-40') {
			ageMatch = age >= 30 && age <= 40;
		} else if (ageFilter === '45+') {
			ageMatch = age > 45;
		}

		row.style.display = (genderMatch && departmentMatch && ageMatch) ? '' : 'none';
	});
}

// Ajout d'écouteurs d'événements pour appliquer les filtres
document.getElementById('gender-filter').addEventListener('change', applyFilters);
document.getElementById('department-filter').addEventListener('change', applyFilters);
document.getElementById('age-filter').addEventListener('change', applyFilters);




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

