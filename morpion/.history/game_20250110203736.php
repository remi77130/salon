<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Initialiser ou restaurer la grille
if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 3, array_fill(0, 3, ''));
    $_SESSION['turn'] = 'user'; // Le joueur commence
}

// Fonction pour vérifier si un joueur a gagné
function checkWinner($board, $player) {
    // Vérifie les lignes, colonnes et diagonales
    for ($i = 0; $i < 3; $i++) {
        if ($board[$i][0] === $player && $board[$i][1] === $player && $board[$i][2] === $player) return true;
        if ($board[0][$i] === $player && $board[1][$i] === $player && $board[2][$i] === $player) return true;
    }
    if ($board[0][0] === $player && $board[1][1] === $player && $board[2][2] === $player) return true;
    if ($board[0][2] === $player && $board[1][1] === $player && $board[2][0] === $player) return true;

    return false;
}

// Fonction pour vérifier si le plateau est plein
function isBoardFull($board) {
    foreach ($board as $row) {
        if (in_array('', $row)) return false;
    }
    return true;
}

// Processus après un clic utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cell'])) {
    [$row, $col] = explode(',', $_POST['cell']);
    if ($_SESSION['board'][$row][$col] === '' && $_SESSION['turn'] === 'user') {
        $_SESSION['board'][$row][$col] = 'X'; // Joueur joue 'X'
        $_SESSION['turn'] = 'computer';

        // Vérifie si le joueur a gagné
        if (checkWinner($_SESSION['board'], 'X')) {
            $message = "Félicitations, vous avez gagné !";
            saveGameResult('win'); // Sauvegarde la victoire
            resetGame();
        } elseif (isBoardFull($_SESSION['board'])) {
            $message = "C'est un match nul !";
            saveGameResult('draw'); // Sauvegarde le nul
            resetGame();
        }
    }

    // Tour de l'ordinateur
    if ($_SESSION['turn'] === 'computer') {
        computerPlay();
        if (checkWinner($_SESSION['board'], 'O')) {
            $message = "Vous avez perdu ! L'ordinateur a gagné.";
            saveGameResult('lose'); // Sauvegarde la défaite
            resetGame();
        } elseif (isBoardFull($_SESSION['board'])) {
            $message = "C'est un match nul !";
            saveGameResult('draw'); // Sauvegarde le nul
            resetGame();
        }
    }
}

// Fonction pour le jeu automatique de l'ordinateur
function computerPlay() {
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            if ($_SESSION['board'][$i][$j] === '') {
                $_SESSION['board'][$i][$j] = 'O'; // Ordinateur joue 'O'
                $_SESSION['turn'] = 'user';
                return;
            }
        }
    }
}

// Fonction pour réinitialiser le jeu
function resetGame() {
    $_SESSION['board'] = array_fill(0, 3, array_fill(0, 3, ''));
    $_SESSION['turn'] = 'user';
}

// Fonction pour sauvegarder le résultat de la partie
function saveGameResult($result) {
    require 'db.php';
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO games (user_id, result) VALUES (:user_id, :result)");
    $stmt->execute([':user_id' => $userId, ':result' => $result]);

    // Mise à jour des points
    if ($result === 'win') {
        $_SESSION['points'] += 5;
    } elseif ($result === 'lose') {
        $_SESSION['points'] -= 5;
    }

    $updatePoints = $conn->prepare("UPDATE users SET points = :points WHERE id = :id");
    $updatePoints->execute([':points' => $_SESSION['points'], ':id' => $userId]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Morpion</title>
    <style>
        table { border-collapse: collapse; margin: 20px auto; }
        td { width: 50px; height: 50px; text-align: center; font-size: 24px; border: 1px solid black; cursor: pointer; }
        td.empty:hover { background-color: #ddd; }
    </style>
</head>
<body>
    <h1>Morpion</h1>
    <p>Points : <?php echo $_SESSION['points']; ?></p>
    <?php if (isset($message)) echo "<p><strong>$message</strong></p>"; ?>

    <form method="POST">
        <table>
            <?php for ($i = 0; $i < 3; $i++): ?>
                <tr>
                    <?php for ($j = 0; $j < 3; $j++): ?>
                        <td class="<?php echo $_SESSION['board'][$i][$j] === '' ? 'empty' : ''; ?>">
                            <?php if ($_SESSION['board'][$i][$j] === ''): ?>
                                <button type="submit" name="cell" value="<?php echo "$i,$j"; ?>" style="all: unset;">-</button>
                            <?php else: ?>
                                <?php echo $_SESSION['board'][$i][$j]; ?>
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
    </form>
    <a href="dashboard.php">Retour au tableau de bord</a>
</body>
</html>
