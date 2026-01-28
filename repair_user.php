<?php
// repair_users.php - À exécuter une fois pour corriger les utilisateurs existants

require_once 'includes/config.php';

function repairExistingUsers() {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME, 
        DB_USER, 
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Récupérer tous les utilisateurs
    $stmt = $pdo->query("SELECT id, email, password_hash FROM users");
    $users = $stmt->fetchAll();
    
    echo "<h3>Réparation des utilisateurs</h3>";
    echo "<p>Nombre d'utilisateurs trouvés: " . count($users) . "</p>";
    
    foreach ($users as $user) {
        echo "<hr>";
        echo "<p>Utilisateur ID: {$user['id']} - Email: {$user['email']}</p>";
        
        // Vérifier le hash
        if (empty($user['password_hash'])) {
            echo "<p style='color:red'>Hash vide - à réparer</p>";
            // Définir un mot de passe par défaut
            $defaultPassword = 'TempPass123';
            $newHash = password_hash($defaultPassword, PASSWORD_DEFAULT);
            
            $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $update->execute([$newHash, $user['id']]);
            echo "<p style='color:green'>Mot de passe réparé: $defaultPassword</p>";
            
        } elseif (!password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
            echo "<p style='color:green'>Hash valide</p>";
        } else {
            echo "<p style='color:orange'>Hash nécessite rehash</p>";
        }
    }
    
    echo "<hr><h3>Réparation terminée</h3>";
}

if (isset($_GET['run'])) {
    repairExistingUsers();
} else {
    echo "<h2>Script de réparation des utilisateurs</h2>";
    echo "<p>Ce script vérifie et répare les utilisateurs existants.</p>";
    echo "<p><a href='?run=1'>Exécuter la réparation</a></p>";
}
?>