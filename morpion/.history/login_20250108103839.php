<?php
session_start();
$conn = new mysqli("localhost", "root", "", "morpion");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password, points FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $points);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['points'] = $points;
        echo "Connexion réussie.";
        header("Location: game.php");
    } else {
        echo "Pseudo ou mot de passe incorrect.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Pseudo" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
