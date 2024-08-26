<?php
require 'connect_bdd.php';

// Validation des données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = trim($_POST['username']);
    $age = (int)$_POST['age'];
    $department = trim($_POST['department']);
    $gender = $_POST['gender'];
    
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
            if ($avatarSize < 5000) { // Limite de taille de 5MB
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
                    } 
                    
                    else if (!move_uploaded_file($avatarTmpName, $avatarDestination)) {
                        error_log("Erreur lors du déplacement du fichier: " . error_get_last()['message']);
                        die("Erreur lors du téléchargement de l'avatar.");
                    }
                    
                }}}}}
$conn->close();
?>
