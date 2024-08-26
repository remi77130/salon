<?php
require 'connect_bdd.php';

// Validation des données du formulaire
$pseudo = trim($_POST['username']);
if (strlen($pseudo) < 3 || strlen($pseudo) > 50) {
    die("Le pseudo doit contenir entre 3 et 50 caractères.");
}

$age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
if ($age === false || $age < 1 || $age > 120) {
    die("Âge invalide.");
}

$department = trim($_POST['department']);
if (strlen($department) > 100) {
    die("Le département ne peut pas dépasser 100 caractères.");
}

$gender = $_POST['gender'];
if (!in_array($gender, ['male', 'female', 'other'])) {
    die("Genre invalide.");
}
    
    // Gestion de l'upload de l'avatar
    $avatar = $_FILES['avatar'];
    $avatarName = $avatar['name'];
    $avatarTmpName = $avatar['tmp_name'];
    $avatarSize = $avatar['size'];
    $avatarError = $avatar['error'];
    $avatarType = $avatar['type'];
    
    // Extensions autorisées
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $avatarExt = strtolower(pathinfo($avatarName, PATHINFO_EXTENSION));
    
    if (in_array($avatarExt, $allowed)) {
        if ($avatarError === 0) {
            if ($avatarSize < 5000000) { // Limite de taille de 5MB
                // Nouveau nom de fichier unique pour l'avatar
                $avatarNewName = uniqid('', true) . "." . $avatarExt;
                $avatarDestination = 'uploads/' . $avatarNewName;
                
                // Déplacement du fichier téléchargé vers le dossier cible
                if (move_uploaded_file($avatarTmpName, $avatarDestination)) {
                    // Insertion des informations dans la base de données
                    $sql = "INSERT INTO users (username, avatar, age, department, gender) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssiss", $pseudo, $avatarDestination, $age, $department, $gender);
                    
                    if ($stmt->execute()) {
                        // Redirection après succès
                        header("Location: welcome.php");
                        exit();
                    } else {
                        echo "Erreur : " . $stmt->error;
                    }
                    
                    $stmt->close();
                } else {
                    echo "Erreur lors du téléchargement de l'avatar.";
                }
            } else {
                echo "Le fichier est trop volumineux.";
            }
        } else {
            echo "Erreur lors du téléchargement du fichier.";
        }
    } else {
        echo "Extension de fichier non autorisée. Les extensions autorisées sont : jpg, jpeg, png, gif.";
    }
} else {
    echo "Méthode de requête non autorisée.";
}

$conn->close();
?>
