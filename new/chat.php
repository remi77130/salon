<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$_SESSION['myuser'] = [
		'username' => htmlspecialchars($_POST['username']),
		'gender' => htmlspecialchars($_POST['gender']),
		'age' => (int)$_POST['age'],
		'ville' => htmlspecialchars($_POST['ville']),
		'avatar' => htmlspecialchars($_POST['avatar']),
	];
}
if (!isset($_SESSION['myuser'])) {
	header("Location: /");
	exit();
}
$_SESSION['myuser']['id'] = time();
$myuser = $_SESSION['myuser'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat Room</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
	<style>
		html, body {
			height: 100%;
			margin: 0;
			padding: 0;
			overflow: hidden;
		}

		#chat-container {
			display: flex;
			height: 100%;
		}

		#global-chat {
			flex: 3;
			display: flex;
			flex-direction: column;
			border-right: 1px solid #ccc;
		}

		.messages {
			flex: 1;
			overflow-y: auto;
			padding: 10px;
		}



		#userlist {
			flex: 1;
			padding: 10px;
			overflow-y: auto;
		}

		#userlist .user {
			padding: 5px;
			border-bottom: 1px solid #ccc;
			display: flex;
			align-items: center;
			gap: 10px;
			cursor: pointer;
		}
		#userlist .user:hover {
			background-color: #EEE;
		}

		#userlist .user img {
			width: 40px;
			height: 40px;
			border-radius: 50%;
			margin-right: 10px;
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

		.message-header {
			display: flex;
			align-items: center;
			margin-bottom: 5px;
		}

		.avatar {
			width: 30px;
			height: 30px;
			border-radius: 50%;
			margin-right: 10px;
		}

		.username {
			font-weight: bold;
			margin-right: auto;
		}

		.timestamp {
			font-size: 0.8em;
			color: #888;
		}

		.message-body {
			word-wrap: break-word;
		}
		header {
			height: 2em;
			background: lightgrey;
			font-size: 2em;
			display: flex;
			align-items: center;
		}
		header img.avatar {
			height: 40px;
			width: 40px;
		}

	</style>
</head>
<body>
<div id="chat-container">
	<div id="global-chat">
		<header></header>
		<div class="messages" id="public-messages"></div>
		<div id="chat-input" >
			<input type="text"  class="form-control" id="messageInput" placeholder="Entrez votre message...">
		</div>
	</div>

	<!-- User List Sidebar -->
	<div id="userlist">
		<!-- Users will be dynamically added here -->
	</div>
</div>

<div class="modal fade" id="privateChatModal" tabindex="-1" aria-labelledby="privateChatModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="privateChatModalLabel">Chat privé avec <span id="privateChatUsername"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="d-flex align-items-center mb-3">
					<img id="privateChatAvatar" src="" alt="" class="rounded-circle me-2" style="width: 50px; height: 50px;">
					<div id="privateChatDetails"></div>
				</div>
				<div id="privateChatMessages" style="height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
					<!-- Private chat messages will appear here -->
				</div>
				<div class="mt-3">
					<input type="text" id="privateChatInput" class="form-control" placeholder="Votre message...">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="sendPrivateMessage">Envoyer</button>
			</div>
		</div>
	</div>
</div>

<!-- Socket.io -->
<script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
<script>
	const $userlistContainer = $('#userlist');
	var myuser = {};
	myuser = <?php echo json_encode($myuser);?>;
	var users = {};
	var user_private = false;

	const socket = io('https://tchat-direct.com:2053', {query: {user: JSON.stringify(myuser)}});
	updateChat();

	socket.on('message', function (message, user) {
		const messageContainer = $('#public-messages');
		const currentTime = new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
		const messageClass = user.username === myuser.username ? 'sent' : 'received';
		messageContainer.append(`
        <div class="message ${messageClass}">
            <div class="message-header">
                <img src="${user.avatar}" alt="${user.username}" class="avatar">
                <span class="username">${user.username}</span>
                <span class="timestamp">${currentTime}</span>
            </div>
            <div class="message-body">
                ${message}
            </div>
        </div>
    `);
		messageContainer.scrollTop(messageContainer[0].scrollHeight);
	});

	function updateChat(user = false) {
		if (!user) {
			header = `Chat public`;
			$('.messages').hide();
			$('#public-messages').show();
			user_private = false;
		} else {
			header = `
			<div><img class="avatar" src="${user.avatar}" alt=""></div>
			<div>Chat privé avec ${user.username}</div>
			<div style="margin-left: auto"><button type="button"  class="closePrivateBtn btn btn-danger">X</button></div>`;
			$('.messages').hide();
			let chat_id = `chat_${user.id}`;
			let $chat = $(`#${chat_id}`);
			user_private = user;
			if (!$chat.length) {
				let chat = `<div id="${chat_id}" class="messages"></div>`;
				$('#public-messages').after(chat);
			} else {
				$chat.show();
			}
		}
		$('header').html(header);

	}

	function addUser(user) {
		users[user.id] = user;
		$userlistContainer.append(`
			<div class="user" data-userid="${user.id}" data-username="${user.username}" data-avatar="${user.avatar}" data-age="${user.age}" data-ville="${user.ville}">
				<img src="${user.avatar}" alt="${user.username}">
				<div><b>${user.username}</b></div>
				<div>${user.age} ans</div>
				<div>${user.ville}</div>
			</div>`);
	}

	socket.on('addUser', function (user) {
		addUser(user);
	});

	socket.on('removeUser', function (user) {
		$(`#userlist div.user[data-userid=${user.id}]`).remove();
		delete users[user.id];
	});


	socket.on('users', function (users) {
		$userlistContainer.empty();
		Object.values(users).forEach(user => {
			addUser(user);
		});
	});

	function addPrivateMessage(message, user) {

	}

	socket.on('private', function(message, user) {
		let $chat = $(`chat_${user.id}`);
		debugger;
		if ($chat.is(":visible")) {

		} else {
			$chat.append(`<div class="sent">${message}</div>`);
			$chat.scrollTop($privateChatMessages[0].scrollHeight); // Scroll to bottom
		}
	});

	$(document).on('click', '.closePrivateBtn', (e)=>{
	   updateChat();
	});


	$('#messageInput').on('keypress', function (e) {
		if (e.key === 'Enter' && $(this).val().trim() !== '') {
			if (!user_private) {
				socket.emit('message', $(this).val());
			}else {
				socket.emit('private', user_private.username, $(this).val());
			}
			$(this).val('');
		}
	});

	$userlistContainer.on('click', '.user', function() {
		const id = $(this).data('userid');
		let user = users[id];
		updateChat(user);
	});


</script>
</body>
</html>
