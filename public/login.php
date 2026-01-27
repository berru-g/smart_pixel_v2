<?php
// TOP du fichier public/login.php - AJOUTE CES 3 LIGNES
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//session_start();
require_once '../includes/auth.php';

if (Auth::isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (Auth::login($_POST['email'], $_POST['password'])) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Email ou mot de passe incorrect';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Connexion - Smart Pixel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <div class="login-logo-icon">◰</div>
                    <h1>Smart Pixel</h1>
                </div>
                <p class="login-subtitle">Analytics intelligentes pour votre succès</p>
            </div>

            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="email"></label>
                    <input type="email" name="email" id="email" placeholder="votre@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password"></label>
                    <input type="password" name="password" id="password" placeholder="Votre mot de passe" required>
                </div>

                <button type="submit" class="login-button">
                    <span>Se connecter</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </button>
            </form>

            <div class="register-link">
                <p>Pas de compte ? <a href="index.php">S'inscrire</a></p>
            </div>
        </div>
    </div>
</body>

</html>