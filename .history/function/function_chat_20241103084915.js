// ========== SECTION 1: Gestion des Événements de Connexion et Déconnexion des Utilisateurs ==========
/**
 * Ajoute un utilisateur à la liste des utilisateurs en ligne.
 * 
 * 
 * 
 * 
 * 
 */
socket.on('addUser', function(user) {
    addUser(user);
});









// ========== SECTION 1: Gestion de l'Affichage du Profil ==========
/**
 * Affiche les informations de profil de l'utilisateur.
 */
function showProfileContainer(userId) {
    
    fetch(`get_user_info.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            renderProfile(data);
            setupChatEvents(userId);
        })
        .catch(error => console.error('Erreur lors du chargement du profil:', error));
}


function handleMessageSend(userId, messageInput) {
    const message = messageInput.value.trim();
    if (message) {
        socket.emit('chatMessage', { to: userId, message });
        appendMessage(message, 'sent');
        messageInput.value = '';
    }
}

/**


// ========== SECTION 3: Fonctions Utilitaires ==========
/**
 * Assainit les entrées pour éviter les failles XSS.
 
 */
function sanitize(input) {
    const element = document.createElement('div');
    element.textContent = input;
    return element.innerHTML;
}


// ========== SECTION 4: Gestion des Filtres d'Utilisateur ==========
/**
 * Applique les filtres de genre, département et âge pour afficher les utilisateurs correspondants.
 */
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
        if (ageFilter === '-30') ageMatch = age < 30;
        else if (ageFilter === '30-40') ageMatch = age >= 30 && age <= 40;
        else if (ageFilter === '45+') ageMatch = age > 45;

        row.style.display = (genderMatch && departmentMatch && ageMatch) ? '' : 'none';
    });
}

['gender-filter', 'department-filter', 'age-filter'].forEach(filterId => {
    document.getElementById(filterId).addEventListener('change', applyFilters);
});


// ========== SECTION 5: Création de Fenêtres de Chat et Gestion des Notifications ==========
/**
 * Crée une fenêtre de chat pour un utilisateur donné.
 * @param {boolean} [display=true] - Indique si la fenêtre de chat doit être affichée immédiatement.
 */
function createChat(user, display = true) {
    const id = `chat_${user.id}`;
    user_private = user;
    let $chat = $(`#${id}`);
    $('.modal').hide();

    if (!$chat.length) {
        $('body').append(renderChatTemplate(user));
    }
    if (display) document.getElementById(id).style.display = 'flex';
}

/**
 * Rend le template de la fenêtre de chat.
 */
function renderChatTemplate(user) {
    return `
        <div id="chat_${user.id}" class="modal">
            <div class="chat-popup">
                <div class="chat-header">
                    <img src="${user.avatar}" alt="Avatar" class="avatar64">
                    <div class="username">${user.username}</div>
                    <div class="userage">${user.age} ans</div>
                    <div class="userdpt">${user.dep}</div>
                    <div class="userville">${user.ville}</div>
                </div>
                <div class="chat-content"></div>
                <div class="chat-footer">
                    <input type="text" class="chat-input" placeholder="Tapez votre message...">
                    <button class="send-btn">Envoyer</button>
                </div>
            </div>
            <button class="close-btn" onclick="closeModal()">Fermer</button>
        </div>`;
}

function closeModal() {
    user_private = false;
    $('.modal').hide();
}

function addUser(user) {
    users[user.id] = user;
    const class_user = (user.gender === 'female') ? 'female-row' : 'male-row';
    $userlistContainer.append(`
        <tr class="user ${class_user}" data-userid="${user.id}" data-username="${user.username}" 
            data-avatar="${user.avatar}" data-age="${user.age}" data-ville="${user.ville}" 
            data-dep="${user.dep}" data-gender="${user.gender}">
            <td><img class="avatar16" src="${user.avatar}" alt="${user.username}"></td>
            <td><b>${user.username}</b></td>
            <td>${user.age}</td>
            <td>${user.dep}</td>
            <td>${user.ville}</td>
        </tr>`);
    addDepartement(user.dep);
}

function addDepartement(dep) {
    const $departmentFilter = $('#department-filter');
    if ($departmentFilter.find(`option[value='${dep}']`).length === 0) {
        $departmentFilter.append($('<option></option>').attr('value', dep).text(dep));
    }
}

function addNotification(user) {
    const $notifications = $('#selected-profiles');
    if (!$notifications.find(`div[data-userid=${user.id}]`).length) {
        $notifications.append(`<div class="notification" data-userid="${user.id}" data-username="${user.username}">${user.username}</div>`);
    }
}


































/**
 *  @param {Object} user - L'utilisateur ajouté.
 * @param {Object} $chat - Conteneur du chat.
 * @param {string} message - Le message à ajouter.
 * @param {string} classe - La classe CSS ('sent' ou 'received').
 * @returns {string} La chaîne assainie.
 *  * @param {Event} e - L'événement de clic sur la notification.

 */

/**
 * Charge la liste complète des utilisateurs au démarrage.
 * Utilité : Met à jour la liste des utilisateurs connectés en temps réel.
 */
socket.on('users', function(users) {
    $userlistContainer.empty(); // Réinitialise le conteneur avant de le remplir.
    Object.values(users).forEach(user => {
        addUser(user);
    });
});

// ========== SECTION 2: Gestion de la Communication Privée ==========
/**
 * Reçoit et affiche un message privé.
 */
socket.on('private', function(user, message) {
    let $chat = $(`#chat_${user.id} .chat-content`);
    let classe = (user.id === myuser.id) ? 'sent' : 'received';

    if ($chat.is(":visible")) {
        appendMessage($chat, message, classe);
    } else {
        createChat(user, false);
        $chat = $(`#chat_${user.id} .chat-content`);
        appendMessage($chat, message, classe);
        addNotification(user);
    }
});


function appendMessage($chat, message, classe) {
    $chat.append(`<div class="message ${classe}">${message}</div>`);
    $chat.scrollTop($chat[0].scrollHeight);
}

// ========== SECTION 3: Gestion de l'Interface du Chat ==========
/**
 * Envoie un message depuis le champ d'entrée du chat.
 */
$(document).on('click', '.send-btn', (e) => {
    let input = $(e.currentTarget).parent().find('input');
    if (!input.val()) return;

    socket.emit('private', user_private.username, input.val());
    let $chat = $(`#chat_${user_private.id} .chat-content`);
    appendMessage($chat, input.val(), 'sent');
    input.val(''); // Réinitialise le champ d'entrée.
});

/**
 * Ouvre une fenêtre de chat pour l'utilisateur sélectionné.
 */
$userlistContainer.on('click', '.user', function() {
    const id = $(this).data('userid');
    let user = users[id];
    createChat(user);
});

// ========== SECTION 4: Notifications de Nouveaux Messages ==========
/**
 * Affiche une notification lorsqu'un nouveau message est reçu.
 */
$(document).on('click', '.notification', (e) => {
    $(e.currentTarget).remove();
    let user_id = $(e.currentTarget).data('userid');
    let user = users[user_id];
    createChat(user);
});



