// Assurez-vous que socket est défini correctement
const socket = io(); // Connexion à Socket.io, doit être la première ligne après l'inclusion de socket.io.js

async function fetchUsers() {
    try {
        const response = await fetch('fetch_users.php');
        
        if (!response.ok) {
            throw new Error('Erreur réseau : ' + response.status);
        }

        const data = await response.json();
        const tbody = document.querySelector('#users-table tbody');
        tbody.innerHTML = ''; // Vide le tableau avant de le remplir

        const departmentFilter = document.getElementById('department-filter');
        const departments = new Set(); // Utilise un Set pour des départements uniques
        const fragment = document.createDocumentFragment(); // Fragment pour limiter les reflows/repaints

        data.forEach(user => {
            const row = document.createElement('tr');

            // Ajoute un attribut data-user-id avec l'ID de l'utilisateur
            row.setAttribute('data-user-id', user.id);

            // Ajoute le département à la liste des départements uniques
            departments.add(user.department);

            // Applique une classe à la ligne en fonction du sexe
            row.classList.add(user.gender === 'female' ? 'female-row' :
                              user.gender === 'male' ? 'male-row' : 'other-row');

            // Assainissement des données pour éviter les injections XSS
            row.innerHTML = `
                <td><img src="${sanitize(user.avatar)}" alt="${sanitize(user.username)}" class="avatar"></td>
                <td>${sanitize(user.username)}</td>
                <td>${sanitize(user.age)}</td>
                <td>${sanitize(user.department)}</td>
                <td>${sanitize(user.ville_users)}</td>
            `;

            fragment.appendChild(row); // Ajoute la ligne au fragment

            row.addEventListener('click', () => {
                showProfileContainer(user.id); // Appelle la fonction pour afficher le profil
            });
        });

        tbody.appendChild(fragment); // Ajoute toutes les lignes en une seule opération DOM

        // Mise à jour des options du filtre département
        updateDepartmentFilter(departmentFilter, departments);

        // Applique les filtres dès le chargement des utilisateurs
        applyFilters();
    } catch (error) {
        alert('Une erreur est survenue lors du chargement des utilisateurs. Veuillez réessayer plus tard.');
        console.error('Erreur lors du chargement des utilisateurs:', error);
    }
}

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
                    <div id="message_user"></div> <!-- Fenêtre de messagerie -->
                    <div id="chat-messages"></div>
                    <input id="chat-input" type="text" placeholder="Entrez votre message">
                    <button id="send-button">Envoyer</button>
                </div>
            `;
            container.style.display = 'block'; // Afficher la div

            // Ajouter l'événement d'envoi de message
            document.getElementById('send-button').addEventListener('click', () => {
                const messageInput = document.getElementById('chat-input');
                const message = messageInput.value;
                if (message.trim()) {
                    socket.emit('chatMessage', { to: userId, message });
                    appendMessage('Vous', message);
                    messageInput.value = '';
                }
            });

            // Gestion de la réception des messages
            socket.on('chatMessage', ({ from, message }) => {
                if (from === userId) {
                    appendMessage(sanitize(data.username), message);
                }
            });
        })
        .catch(error => console.error('Erreur lors du chargement du profil:', error));
}

function appendMessage(sender, message) {
    const messagesContainer = document.getElementById('message_user');
    const messageElement = document.createElement('div');
    messageElement.classList.add('message');
    messageElement.innerHTML = `<strong>${sanitize(sender)}:</strong> ${sanitize(message)}`;
    messagesContainer.appendChild(messageElement);
    messagesContainer.scrollTop = messagesContainer.scrollHeight; // Défile automatiquement vers le bas
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

function updateDepartmentFilter(filterElement, departments) {
    filterElement.innerHTML = '<option value="all">Tous</option>';
    departments.forEach(department => {
        const option = document.createElement('option');
        option.value = department;
        option.textContent = department;
        filterElement.appendChild(option);
    });
}

function applyFilters() {
    const genderFilter = document.getElementById('gender-filter').value;
    const departmentFilter = document.getElementById('department-filter').value;
    const rows = document.querySelectorAll('#users-table tbody tr');

    rows.forEach(row => {
        const genderClass = row.classList.contains('female-row') ? 'female' :
                            row.classList.contains('male-row') ? 'male' : 'other';
        const department = row.querySelector('td:nth-child(4)').textContent;

        const genderMatch = (genderFilter === 'all' || genderFilter === genderClass);
        const departmentMatch = (departmentFilter === 'all' || departmentFilter === department);

        row.style.display = (genderMatch && departmentMatch) ? '' : 'none';
    });
}

// Ajout d'écouteurs d'événements pour appliquer les filtres
document.getElementById('gender-filter').addEventListener('change', applyFilters);
document.getElementById('department-filter').addEventListener('change', applyFilters);

// Charge les utilisateurs au chargement de la page
fetchUsers();

// Met à jour les utilisateurs toutes les minutes
setInterval(fetchUsers, 60000); // 60000 ms = 1 minute