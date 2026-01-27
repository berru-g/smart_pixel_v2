<?php
// kill_all_bugs.php
session_start();

// DÉFINIR L'URL DE BASE UNE FOIS POUR TOUTES
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/smart_phpixel/smart_pixel_v2/public/';

echo "<h1 style='color: #f00;'>🔪 TUER TOUS LES BUGS</h1>";

echo "<div style='background: #000; color: #0f0; padding: 20px; font-family: monospace;'>";

echo "=== ÉTAPE 1: CORRECTION DES URL ===\n\n";
echo "URL de base: $base_url\n\n";

echo "=== ÉTAPE 2: TEST SESSION ===\n";
$_SESSION['user_id'] = 2;
$_SESSION['user_email'] = 'test@test.com';
echo "Session user_id: " . ($_SESSION['user_id'] ?? 'NULL') . "\n";
echo "Session email: " . ($_SESSION['user_email'] ?? 'NULL') . "\n\n";

echo "=== ÉTAPE 3: TEST DASHBOARD ===\n";
echo "<a href='{$base_url}dashboard.php' style='color: cyan; font-size: 20px;' target='_blank'>
      🔥 TEST DASHBOARD (ouvre nouvel onglet)
      </a>\n\n";

echo "=== ÉTAPE 4: CORRECTION FORMULAIRES ===\n";
echo "Pour index.php, ajoute dans le formulaire:\n";
echo "<pre style='color: yellow;'>
&lt;form method='POST' action='&lt;?php echo htmlspecialchars(\$_SERVER['PHP_SELF']); ?&gt;'&gt;
</pre>\n\n";

echo "=== ÉTAPE 5: CORRECTION REDIRECTIONS ===\n";
echo "Dans login.php, index.php, dashboard.php:\n";
echo "<pre style='color: yellow;'>
header('Location: " . $base_url . "dashboard.php');
// TOUJOURS utiliser l'URL complète
</pre>\n";

echo "</div>";

// TEST PRATIQUE
echo "<h2>🎯 TEST IMMÉDIAT</h2>";
echo "<iframe src='{$base_url}dashboard.php' width='100%' height='500px' style='border: 2px solid red;'></iframe>";

echo "<h2>📝 FICHIERS À CORRIGER (30 secondes)</h2>";
echo "<ol>
<li><strong>public/login.php</strong> - Ligne ~10:<br>
    <code>header('Location: {$base_url}dashboard.php');</code>
</li>
<li><strong>public/index.php</strong> - Ligne ~34:<br>
    <code>header('Location: {$base_url}dashboard.php');</code><br>
    Et dans le formulaire: <code>action='<?php echo htmlspecialchars(\$_SERVER[\"PHP_SELF\"]); ?>'</code>
</li>
<li><strong>public/dashboard.php</strong> - Ligne ~13:<br>
    <code>header('Location: {$base_url}login.php');</code>
</li>
</ol>";

echo "<h2 style='color: green;'>💥 EXÉCUTE CES 3 CHANGEMENTS PUIS RAFRAÎCHIS CETTE PAGE</h2>";
?>