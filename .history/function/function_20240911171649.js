
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
				const fileInput = document.querySelector('.chat-file-input');
				const message = messageInput.value;
				const file = fileInput.files[0];

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















// CHAT.PHP


