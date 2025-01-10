<?php
require 'db.php';

// Vérifie si le formulaire a été soumis

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données du formulaire

    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Hachage basique sécurisé du mot de passe

    try {
        $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
        $stmt->execute([
            ':email' => $email,
            ':username' => $username,
            ':password' => $password
        ]);
        echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <form method="POST" action="register.php">
        <label for="email">Email :</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="username">Pseudo :</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
