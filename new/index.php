<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login Page</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
	<style>
		body, html {
			height: 100%;
		}
		.login-container {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
		}
		.avatar-container img {
			width: 50px;
			height: 50px;
			margin: 5px;
		}
		.avatar-container input[type="radio"] {
			display: none;
		}
		.avatar-container input[type="radio"]:checked + img {
			border: 2px solid #007bff;
			border-radius: 50%;
		}
	</style>
</head>
<body>
<div class="login-container">
	<div class="card p-4">
		<h2 class="card-title text-center mb-4">Login</h2>
		<form action="chat.php" method="post">
			<div class="mb-3">
				<label for="username" class="form-label">Pseudo</label>
				<input type="text" class="form-control" id="username" name="username" pattern="[a-zA-Z0-9]+" required>
			</div>
			<div class="mb-3">
				<label for="gender" class="form-label">Gender</label>
				<select class="form-select" id="gender" name="gender" required>
					<option value="homme">Homme</option>
					<option value="femme">Femme</option>
				</select>
			</div>
			<div class="mb-3">
				<label for="age" class="form-label">Age</label>
				<input type="number" class="form-control" id="age" name="age" min="18" max="99" required>
			</div>
			<div class="mb-3">
				<label for="ville" class="form-label">Ville</label>
				<input type="text" class="form-control" id="ville" name="ville" required>
			</div>
			<div class="mb-3">
				<label class="form-label">Avatar</label>
				<div class="avatar-container d-flex flex-wrap">
					<label>
						<input type="radio" name="avatar" value="/avatar/astronaute.png" required>
						<img src="/avatar/astronaute.png" alt="Avatar 1">
					</label>
					<label>
						<input type="radio" name="avatar" value="/avatar/femme1.png" required>
						<img src="/avatar/femme1.png" alt="Avatar 2">
					</label>
					<label>
						<input type="radio" name="avatar" value="/avatar/femme2.png" required>
						<img src="/avatar/femme2.png" alt="Avatar 3">
					</label>
					<label>
						<input type="radio" name="avatar" value="/avatar/fille.png" required>
						<img src="/avatar/fille.png" alt="Avatar 3">
					</label>
					<label>
						<input type="radio" name="avatar" value="/avatar/garcon.png" required>
						<img src="/avatar/garcon.png" alt="Avatar 3">
					</label>
					<label>
						<input type="radio" name="avatar" value="/avatar/homme2.png" required>
						<img src="/avatar/homme2.png" alt="Avatar 3">
					</label>
					<label>
						<input type="radio" name="avatar" value="/avatar/homme3.png" required>
						<img src="/avatar/homme3.png" alt="Avatar 3">
					</label>
					<label>
						<input type="radio" name="avatar" value="/avatar/joueur.png" required>
						<img src="/avatar/joueur.png" alt="Avatar 3">
					</label>
					<label>
						<input type="radio" name="avatar" value="/avatar/user1.png" required>
						<img src="/avatar/user1.png" alt="Avatar 3">
					</label>
				</div>
			</div>
			<button type="submit" class="btn btn-primary w-100">Entrer sur le chat</button>
		</form>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
