<?php
require 'connect_bdd.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données
    $pseudo = trim($_POST['username']);
    $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
    $department = trim($_POST['department']);
    $ville_users = htmlspecialchars($_POST['ville_users']); // Assainissement de la ville
    $gender = $_POST['gender'];

    // Validation du pseudo
    if (strlen($pseudo) < 3 || strlen($pseudo) > 120) {
        die("Ton pseudo doit contenir entre 3 et 12 caractères.");
    }

    // Validation de l'âge
    if ($age === false || $age < 15 || $age > 89) {
        die("Âge invalide.");
    }

    // Validation du département
    if (strlen($department) > 100) {
        die("Le département ne peut pas dépasser 100 caractères.");
    }

    // Validation du genre
    if (!in_array($gender, ['male', 'female', 'other'])) {
        die("Genre invalide.");
    }

    // Générer un pseudo unique
    $original_pseudo = $pseudo;
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    if ($stmt === false) {
        error_log("Erreur de préparation de la requête : " . $conn->error);
        die("Une erreur interne est survenue. Veuillez réessayer plus tard.");
    }

    // Boucle pour générer un pseudo unique
    $pseudo_taken = true;
    $i = 1;
    while ($pseudo_taken) {
        $stmt->bind_param("s", $pseudo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $pseudo = $original_pseudo . $i;
            $i++;
        } else {
            $pseudo_taken = false;
        }
    }
    $stmt->free_result(); // Libère le résultat pour réutiliser $stmt

    // Vérifier et traiter l'image téléchargée
    $imageData = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $imageType = $_FILES['profile_image']['type'];
        if (in_array($imageType, ['image/jpeg', 'image/png'])) {
            $imageData = file_get_contents($_FILES['profile_image']['tmp_name']);
        } else {
            die("Type de fichier non supporté.");
        }
    } else {
        die("Erreur lors du téléchargement de l'image.");
    }

    // Insertion de l'utilisateur dans la base de données
    $stmt = $conn->prepare("INSERT INTO users (username, age, department, ville_users, gender, profile_image) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Erreur de préparation de la requête d'insertion : " . $conn->error);
        die("Une erreur est survenue lors de l'inscription.");
    }
    $stmt->bind_param("sisssb", $pseudo, $age, $department, $ville_users, $gender, $imageData);

    if ($stmt->execute()) {
        // Stockage des informations de l'utilisateur en session
        $_SESSION['user'] = [
            'id' => $conn->insert_id,
            'username' => $pseudo,
            'age' => $age,
            'dep' => $department,
            'ville' => $ville_users,
            'gender' => $gender,
        ];

        header("Location: chat.php");
        exit();
    } else {
        error_log("Erreur lors de l'exécution de la requête : " . $stmt->error);
        die("Une erreur est survenue lors de l'inscription.");
    }

    $stmt->close();
} else {
    echo "Méthode de requête non autorisée.";
}

$conn->close();
?>
