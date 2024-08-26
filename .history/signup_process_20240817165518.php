<?php
require 'connect_bdd.php';

// Validation des données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = trim($_POST['username']);
    $age = (int)$_POST['age'];
    $department = trim($_POST['department']);
    $gender = $_POST['gender'];

// Vérification de la disponibilité du pseudo
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
if ($stmt === false) {
    // Gestion de l'erreur de préparation de la requête
    error_log("Erreur de préparation de la requête : " . $conn->error);
    echo "Une erreur interne est survenue. Veuillez réessayer plus tard.";
    exit;
}



$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Si le pseudo existe déjà
    echo "Ce pseudo est déjà pris. Veuillez en choisir un autre.";
    exit;
}

// Le pseudo est disponible, vous pouvez continuer avec l'inscription
// Autres étapes du processus d'inscription...

$stmt->close();


    $pseudo = trim($_POST['username']);
        if (strlen($pseudo) < 3 || strlen($pseudo) > 8) {
            die("Le pseudo doit contenir entre 3 et 50 caractères.");
        }
        
        $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
        if ($age === false || $age < 18 || $age > 89) {
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
        
// Vérification de la disponibilité du pseudo
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Si le pseudo existe déjà
    echo "Ce pseudo est déjà pris. Veuillez en choisir un autre.";
    exit;
}
 
    
    
    
        // Gestion de l'upload de l'avatar
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
                        header("Location: chat.php");
                        exit();
                    } 
                    
                    else if (!move_uploaded_file($avatarTmpName, $avatarDestination)) {
                        error_log("Erreur lors du déplacement du fichier: " . error_get_last()['message']);
                        die("Erreur lors du téléchargement de l'avatar.");
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
$stmt->close();

?>
