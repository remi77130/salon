<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
    <p>Points disponibles : <?php echo $_SESSION['points']; ?></p>
    <a href="logout.php">Déconnexion</a>
</body>
</html>
