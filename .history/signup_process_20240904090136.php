<?php
require 'connect_bdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = trim($_POST['username']);
    $age = (int)$_POST['age'];
    $department = trim($_POST['department']);
    $ville_users = $_POST['ville_users']; // La ville sélectionnée par l'utilisateur
    $gender = $_POST['gender'];

    // Validation des données
    if (strlen($pseudo) < 3 || strlen($pseudo) > 120) {
        die("Ton pseudo doit contenir entre 3 et 12 caractères.");
    }

    $age = filter_var($age, FILTER_VALIDATE_INT);
    if ($age === false || $age < 15 || $age > 89) {
        die("Âge invalide.");
    }

    if (strlen($department) > 100) {
        die("Le département ne peut pas dépasser 100 caractères.");
    }

    if (!in_array($gender, ['male', 'female', 'other'])) {
        die("Genre invalide.");
    }

    // Vérification de la disponibilité du pseudo
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    if ($stmt === false) {
        error_log("Erreur de préparation de la requête : " . $conn->error);
        echo "Pseudo pas disponible.";
        exit;
    }

    $stmt->bind_param("s", $pseudo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Si le pseudo existe déjà
        echo "Ce pseudo est déjà pris. Veuillez en choisir un autre.";
        $stmt->close();
        exit;
    }

    // Gestion de l'upload de l'avatar seulement si un fichier a été téléchargé
    $avatarDestination = 'uploads/avatar_default.jpg'; // Image par défaut
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {

        $avatar = $_FILES['avatar'];
        $avatarName = $avatar['name'];

        $avatarTmpName = $avatar['tmp_name'];
        $avatarSize = $avatar['size'];

        $avatarError = $avatar['error'];
        $avatarType = $avatar['type'];


        $check = getimagesize($avatarTmpName);
        if ($check === false) {
            die("Le fichier n'est pas une image valide.");
        }

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $avatarExt = strtolower(pathinfo($avatarName, PATHINFO_EXTENSION));

        if (!in_array($avatarExt, $allowed)) {
            die("Extension de fichier non autorisée. Les extensions autorisées sont : jpg, jpeg, png, gif.");
        }

        if ($avatarError !== 0) {
            die("Erreur lors du téléchargement du fichier.");
        }

        if ($avatarSize > 5000000) { // Limite de taille de 5MB
            die("Le fichier est trop volumineux.");
        }

        // Nouveau nom de fichier unique pour l'avatar
        $avatarNewName = uniqid('', true) . "." . $avatarExt;
        $avatarDestination = 'uploads/' . $avatarNewName;

        // Déplacement du fichier téléchargé vers le dossier cible
        if (!move_uploaded_file($avatarTmpName, $avatarDestination)) {
            error_log("Erreur lors du déplacement du fichier: " . error_get_last()['message']);
            die("Erreur lors du téléchargement de l'avatar.");
        }
        }

    // Insertion des informations dans la base de données, y compris la ville sélectionnée
    $sql = "INSERT INTO users (username, avatar, age, department, ville_users, gender) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Erreur de préparation de la requête : " . $conn->error);
        die("Une erreur interne est survenue. Veuillez réessayer plus tard.");
    }



    $stmt->bind_param("ssisss", $pseudo, $avatarDestination, $age, $department, $ville_users, $gender);

    if ($stmt->execute()) {
        // Redirection après succès
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