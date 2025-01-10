<?php

// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Nom d'utilisateur par défaut de XAMPP
$password = ""; // Mot de passe par défaut de XAMPP (généralement vide)
$dbname = "game";



// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Gestion de l'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash du mot de passe

    // Vérifier si l'utilisateur existe déjà
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email ou pseudo déjà utilisé.";
    } else {


        // Insérer un nouvel utilisateur
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, points) VALUES (?, ?, ?, 100)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            echo "Inscription réussie. <a href='login.php'>Connectez-vous ici</a>.";
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
    $stmt->close();
}
$conn->close();
?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Pseudo" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit">S'inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
</body>
</html>