<?php
session_start();
$conn = new mysqli("localhost", "root", "", "game_bdd");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de l'utilisateur
$user_id = $_SESSION['user_id'];
$points = $_SESSION['points'];

// Vérifier les points disponibles
if ($points < 5) {
    echo "Vous n'avez pas assez de points pour jouer.";
    exit();
}

// Débiter 5 points pour jouer
$conn->query("UPDATE users_games SET points = points - 5 WHERE id = $user_id");
$points -= 5;

// Initialiser la grille
$grid = isset($_POST['grid']) ? $_POST['grid'] : array_fill(0, 9, '');
$player = 'X';
$computer = 'O';
$result = '';

// Vérifier si un joueur a gagné
function checkWinner($grid, $symbol) {
    $patterns = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // Lignes
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // Colonnes
        [0, 4, 8], [2, 4, 6]            // Diagonales
    ];
    foreach ($patterns as $pattern) {
        if ($grid[$pattern[0]] == $symbol && $grid[$pattern[1]] == $symbol && $grid[$pattern[2]] == $symbol) {
            return true;
        }
    }
    return false;
}

// Tour de l'ordinateur
function computerMove(&$grid) {
    for ($i = 0; $i < 9; $i++) {
        if ($grid[$i] == '') {
            $grid[$i] = 'O';
            break;
        }
    }
}

// Vérifier l'état du jeu
if (isset($_POST['move'])) {
    $grid[$_POST['move']] = $player;

    if (checkWinner($grid, $player)) {
        $result = 'win';
    } elseif (!in_array('', $grid)) {
        $result = 'draw';
    } else {
        computerMove($grid);

        if (checkWinner($grid, $computer)) {
            $result = 'lose';
        } elseif (!in_array('', $grid)) {
            $result = 'draw';
        }
    }

    // Calcul des points et enregistrement
    if ($result) {
        $pointsChange = 0;
        if ($result == 'win') {
            $pointsChange = 10; // +5 pour jouer +5 pour gagner
        } elseif ($result == 'lose') {
            $pointsChange = 0; // -5 déjà débités
        } elseif ($result == 'draw') {
            $pointsChange = 5; // -5 pour jouer, +5 en retour
        }

        // Mise à jour de la base
        $stmt = $conn->prepare("INSERT INTO games_usr (user_id, result, points_change) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $user_id, $result, $pointsChange);
        $stmt->execute();
        $stmt->close();

        $conn->query("UPDATE users_games SET points = points + $pointsChange WHERE id = $user_id");
        $points += $pointsChange;
        $_SESSION['points'] = $points;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Jeu de Morpion</title>
</head>
<body>
    <h2>Jeu de Morpion</h2>
    <p>Points actuels : <?php echo $points; ?></p>
    <p>Résultat : <?php echo $result ? strtoupper($result) : "En cours..."; ?></p>
    <form method="post">
        <div style="display: grid; grid-template-columns: repeat(3, 50px); gap: 5px;">
            <?php for ($i = 0; $i < 9; $i++): ?>
                <button type="submit" name="move" value="<?php echo $i; ?>" 
                        style="width: 50px; height: 50px;">
                    <?php echo htmlspecialchars($grid[$i]); ?>
                </button>
                <input type="hidden" name="grid[]" value="<?php echo htmlspecialchars($grid[$i]); ?>">
            <?php endfor; ?>
        </div>
    </form>
    <a href="logout.php">Se déconnecter</a>
</body>
</html>
