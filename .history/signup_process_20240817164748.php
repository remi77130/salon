<?php
require 'connect_bdd.php';

// Validation des données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = trim($_POST['username']);
    $age = (int)$_POST['age'];
    $department = trim($_POST['department']);
    $gender = $_POST['gender']; // Vérification de la disponibilité du pseudo
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Si le pseudo existe déjà
        echo "Ce pseudo est déjà pris. Veuillez en choisir un autre.";
        exit;
    } else {
        // Code pour gérer le téléchargement de l'avatar et l'inscription
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["avatar"]["name"]);

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO users (username, avatar, age, department, gender) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $username, $avatar, $age, $department, $gender);
            if ($stmt->execute()) {
                echo "Inscription réussie !";
            } else {
                echo "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        } else {
            echo "Erreur lors du téléchargement de l'avatar.";
        }
    }

    $stmt->close();
    $conn->close();
}

?>
