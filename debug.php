<?php
// debug_tout.php - Mets à la racine et exécute
echo "<!DOCTYPE html><html><head><style>
body {font-family: monospace; margin: 20px; background: #0f0f23; color: #ccc;}
.pass {color: #0f0;}
.fail {color: #f00;}
.warn {color: #ff0;}
.section {background: #1a1a2e; padding: 15px; margin: 10px 0; border-left: 4px solid #00ccff;}
pre {background: #000; padding: 10px; border-radius: 5px;}
</style></head><body>";

echo "<h1>🔧 DEBUG COMPLET - SMART PIXEL</h1>";

// 1. CONFIGURATION PHP
echo "<div class='section'><h2>1. CONFIGURATION PHP</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Session status: " . session_status() . " (" . 
     (session_status() == PHP_SESSION_ACTIVE ? "ACTIVE" : 
      (session_status() == PHP_SESSION_NONE ? "NONE" : "DISABLED")) . ")<br>";
echo "Session ID: " . (session_id() ?: "NONE") . "<br>";
echo "Display errors: " . (ini_get('display_errors') ? "ON" : "OFF") . "<br>";
echo "Error reporting: " . ini_get('error_reporting') . "<br>";
echo "Include path: " . ini_get('include_path') . "<br>";
echo "</div>";

// 2. STRUCTURE DES FICHIERS
echo "<div class='section'><h2>2. STRUCTURE DES FICHIERS</h2>";
$root = __DIR__;
echo "Racine: $root<br>";

$files = [
    'includes/config.php',
    'includes/auth.php', 
    'public/index.php',
    'public/login.php',
    'public/dashboard.php'
];

foreach ($files as $file) {
    $path = $root . '/' . $file;
    if (file_exists($path)) {
        $size = filesize($path);
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        echo "<span class='pass'>✓</span> $file ($size bytes, perm: $perms)<br>";
    } else {
        echo "<span class='fail'>✗</span> $file - MANQUANT<br>";
    }
}
echo "</div>";

// 3. TEST INCLUSIONS
echo "<div class='section'><h2>3. TEST D'INCLUSIONS</h2>";
ob_start();
try {
    require_once $root . '/includes/config.php';
    echo "<span class='pass'>✓ includes/config.php</span> - Chargé<br>";
    
    // Vérifie les constantes
    $constants = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
    foreach ($constants as $const) {
        echo defined($const) ? 
             "<span class='pass'>✓</span> $const = " . constant($const) . "<br>" : 
             "<span class='fail'>✗</span> $const NON DÉFINIE<br>";
    }
} catch (Exception $e) {
    echo "<span class='fail'>✗ includes/config.php - ERREUR: " . $e->getMessage() . "</span><br>";
}
$output = ob_get_clean();
echo $output;

ob_start();
try {
    require_once $root . '/includes/auth.php';
    echo "<span class='pass'>✓ includes/auth.php</span> - Chargé<br>";
    
    // Vérifie la classe
    if (class_exists('Auth')) {
        echo "<span class='pass'>✓ Classe Auth existe</span><br>";
        
        // Test méthodes
        $methods = ['register', 'login', 'isLoggedIn', 'logout'];
        foreach ($methods as $method) {
            echo method_exists('Auth', $method) ? 
                 "<span class='pass'>✓</span> Auth::$method()<br>" : 
                 "<span class='fail'>✗</span> Auth::$method() MANQUANTE<br>";
        }
    } else {
        echo "<span class='fail'>✗ Classe Auth N'EXISTE PAS</span><br>";
    }
} catch (Exception $e) {
    echo "<span class='fail'>✗ includes/auth.php - ERREUR: " . $e->getMessage() . "</span><br>";
}
$output = ob_get_clean();
echo $output;
echo "</div>";

// 4. TEST BASE DE DONNÉES
echo "<div class='section'><h2>4. TEST BASE DE DONNÉES</h2>";
try {
    if (!defined('DB_HOST')) die("<span class='fail'>Constantes DB non définies</span>");
    
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<span class='pass'>✓ Connexion DB réussie</span><br>";
    
    // Tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables trouvées: " . count($tables) . "<br>";
    foreach ($tables as $table) {
        echo "&nbsp;&nbsp;• $table<br>";
    }
    
    // Utilisateurs
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $userCount = $stmt->fetchColumn();
    echo "Utilisateurs: $userCount<br>";
    
    if ($userCount > 0) {
        $users = $pdo->query("SELECT id, email, plan FROM users ORDER BY id LIMIT 5")->fetchAll();
        foreach ($users as $user) {
            echo "&nbsp;&nbsp;• ID {$user['id']}: {$user['email']} ({$user['plan']})<br>";
        }
    }
    
} catch (PDOException $e) {
    echo "<span class='fail'>✗ ERREUR DB: " . $e->getMessage() . "</span><br>";
}
echo "</div>";

// 5. TEST SESSION ET AUTH
echo "<div class='section'><h2>5. TEST SESSION ET AUTHENTIFICATION</h2>";
echo "Session avant test:<br>";
echo "&nbsp;&nbsp;user_id: " . ($_SESSION['user_id'] ?? 'NULL') . "<br>";
echo "&nbsp;&nbsp;user_email: " . ($_SESSION['user_email'] ?? 'NULL') . "<br>";

// Test avec un utilisateur existant
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $testUser = $pdo->query("SELECT id, email FROM users WHERE id = 2 LIMIT 1")->fetch();
    
    if ($testUser) {
        echo "<br>Test avec user ID 2 ({$testUser['email']}):<br>";
        
        // Simule login
        $_SESSION['user_id'] = $testUser['id'];
        $_SESSION['user_email'] = $testUser['email'];
        
        echo "&nbsp;&nbsp;Session définie<br>";
        echo "&nbsp;&nbsp;Nouveau user_id: " . $_SESSION['user_id'] . "<br>";
        
        // Test isLoggedIn
        if (class_exists('Auth')) {
            echo "&nbsp;&nbsp;Auth::isLoggedIn(): " . (Auth::isLoggedIn() ? "TRUE" : "FALSE") . "<br>";
        }
    } else {
        echo "<span class='warn'>⚠ User ID 2 non trouvé</span><br>";
    }
} catch (Exception $e) {
    echo "<span class='fail'>✗ " . $e->getMessage() . "</span><br>";
}
echo "</div>";

// 6. TEST PATHS ABSOLUS
echo "<div class='section'><h2>6. CHEMINS ABSOLUS</h2>";
echo "__DIR__ (actuel): $root<br>";
echo "Pour public/login.php:<br>";
echo "&nbsp;&nbsp;Chemin vers auth.php: " . realpath($root . '/../includes/auth.php') ?: "NON TROUVÉ" . "<br>";
echo "&nbsp;&nbsp;Chemin depuis login: " . dirname($root) . '/includes/auth.php' . "<br>";

echo "<br>Test inclusion depuis public/ :<br>";
$testPath = $root . '/public/test_include.php';
file_put_contents($testPath, '<?php 
echo "Test inclusion: "; 
require_once "../includes/auth.php"; 
echo "OK"; 
?>');

if (file_exists($testPath)) {
    ob_start();
    include $testPath;
    $result = ob_get_clean();
    echo "Résultat: $result<br>";
    unlink($testPath);
}
echo "</div>";

// 7. RECOMMANDATIONS
echo "<div class='section'><h2>7. DIAGNOSTIC</h2>";
$issues = [];

// Vérifie session_start()
if (session_status() !== PHP_SESSION_ACTIVE) {
    $issues[] = "Session non démarrée - ajoute session_start() en haut de chaque fichier public/";
}

// Vérifie chemins
if (!file_exists($root . '/includes/auth.php')) {
    $issues[] = "Fichier auth.php introuvable";
}

// Vérifie DB
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
} catch (Exception $e) {
    $issues[] = "Connexion DB échouée: " . $e->getMessage();
}

if (empty($issues)) {
    echo "<span class='pass'>✓ Aucun problème critique détecté</span><br>";
    echo "Le problème est probablement dans la logique d'authentification.<br>";
    echo "<br><strong>Solution rapide:</strong><br>";
    echo "1. Dans public/login.php - ajoute <code>session_start();</code> en ligne 2<br>";
    echo "2. Dans public/dashboard.php - ajoute <code>session_start();</code> en ligne 2<br>";
    echo "3. Dans includes/auth.php login() - ajoute:<pre>if (session_status() === PHP_SESSION_NONE) {
    session_start();
}</pre>";
} else {
    echo "<span class='fail'>✗ PROBLEMES:</span><br>";
    foreach ($issues as $issue) {
        echo "• $issue<br>";
    }
}
echo "</div>";

// 8. TEST FINAL
echo "<div class='section'><h2>8. TEST FINAL AUTOMATIQUE</h2>";
echo "<button onclick=\"runTests()\">Lancer le test complet</button>";
echo "<div id='testResults'></div>";
echo "</div>";

echo <<<HTML
<script>
function runTests() {
    document.getElementById('testResults').innerHTML = 'Exécution...';
    
    fetch('debug_ajax.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('testResults').innerHTML = data;
    })
    .catch(error => {
        document.getElementById('testResults').innerHTML = 'Erreur: ' + error;
    });
}
</script>
HTML;

echo "</body></html>";