<?php
session_start();

// Initialize game
if (!isset($_SESSION['player_hp'])) {
    $_SESSION['player_hp'] = 100;
    $_SESSION['enemy_hp'] = 100;
    $_SESSION['message'] = "Battle begins! You encounter an enemy.";
}

// Handle actions
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == "attack") {
        $damage = rand(10, 20);
        $_SESSION['enemy_hp'] -= $damage;
        $_SESSION['message'] = "You attacked the enemy for $damage damage!";
    } elseif ($action == "heal") {
        $heal = rand(5, 15);
        $_SESSION['player_hp'] += $heal;
        $_SESSION['message'] = "You healed yourself for $heal HP!";
    }

    // Enemy turn if still alive
    if ($_SESSION['enemy_hp'] > 0) {
        $enemy_action = rand(1, 2);
        if ($enemy_action == 1) {
            $damage = rand(5, 15);
            $_SESSION['player_hp'] -= $damage;
            $_SESSION['message'] .= "<br>Enemy attacked you for $damage damage!";
        } else {
            $heal = rand(5, 10);
            $_SESSION['enemy_hp'] += $heal;
            $_SESSION['message'] .= "<br>Enemy healed for $heal HP!";
        }
    }

    // Check win/lose
    if ($_SESSION['player_hp'] <= 0) {
        $_SESSION['message'] = "💀 You were defeated! Game over.";
    } elseif ($_SESSION['enemy_hp'] <= 0) {
        $_SESSION['message'] = "🏆 You defeated the enemy! Victory!";
    }
}

// Reset game
if (isset($_POST['reset'])) {
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple PHP Shooter</title>
</head>
<body style="font-family: Arial; text-align: center; margin-top: 50px;">
    <h1>🔥 Simple Shooter Game 🔥</h1>

    <p><strong>Player HP:</strong> <?= $_SESSION['player_hp'] ?></p>
    <p><strong>Enemy HP:</strong> <?= $_SESSION['enemy_hp'] ?></p>
    <p style="color: blue;"><?= $_SESSION['message'] ?></p>

    <?php if ($_SESSION['player_hp'] > 0 && $_SESSION['enemy_hp'] > 0): ?>
        <form method="post">
            <button type="submit" name="action" value="attack">⚔️ Attack</button>
            <button type="submit" name="action" value="heal">❤️ Heal</button>
        </form>
    <?php endif; ?>

    <form method="post">
        <button type="submit" name="reset">🔄 Reset Game</button>
    </form>

    <p><a href="index.html">⬅️ Back to Home</a></p>
</body>
</html>
