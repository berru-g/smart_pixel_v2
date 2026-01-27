<?php
// exorcise_bug.php
session_start();
ob_start();

echo "<!DOCTYPE html><html><head><style>
body { background: #000; color: #0f0; font-family: monospace; padding: 20px; }
.cursed { color: #f00; font-size: 24px; text-shadow: 0 0 10px #f00; }
.blessed { color: #0f0; font-size: 24px; text-shadow: 0 0 10px #0f0; }
iframe { border: 3px solid #f00; background: white; }
</style></head><body>";

echo "<h1 class='cursed'>👹 EXORCISME DU BUG</h1>";

// 1. TUER LA SESSION ACTUELLE
session_destroy();
session_start();

// 2. CRÉER UNE SESSION PARFAITE
$_SESSION = [
    'user_id' => 2,
    'user_email' => 'test@test.com',
    'exorcised' => true,
    'timestamp' => time()
];

echo "<h2>1. 🔥 SESSION SACRIFIÉE ET RECRÉÉE</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// 3. CRÉER UN SITE SI PAS EXISTANT
$pdo = new PDO("mysql:host=localhost;dbname=smartpixel_app;charset=utf8", "root", "root");

$stmt = $pdo->prepare("SELECT COUNT(*) FROM user_sites WHERE user_id = 2");
$siteCount = $stmt->fetchColumn();

if ($siteCount == 0) {
    echo "<h2 class='cursed'>2. ⚰️ USER 2 N'A AUCUN SITE - CRÉATION...</h2>";
    
    $tracking_code = 'SP_EXORCISED_' . time();
    $public_key = bin2hex(random_bytes(32));
    
    $stmt = $pdo->prepare("INSERT INTO user_sites (user_id, site_name, domain, tracking_code, public_key) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([2, 'Site Exorcisé', 'exorcisme.com', $tracking_code, $public_key]);
    
    echo "<p class='blessed'>✅ SITE CRÉÉ: $tracking_code</p>";
}

// 4. TEST DASHBOARD EN IFRAME
$dashboard_url = 'http://' . $_SERVER['HTTP_HOST'] . '/smart_phpixel/smart_pixel_v2/public/dashboard.php?exorcism=' . time();

echo "<h2>3. 📺 TEST DASHBOARD (iframe direct)</h2>";
echo "<iframe src='$dashboard_url' width='100%' height='600px' id='exorcismFrame'></iframe>";

// 5. BOUTON DE FORCE BRUTE
echo "<h2>4. 💥 BOUTON DE FORCE BRUTE</h2>";
echo "<button onclick='forceEntry()' style='font-size: 20px; padding: 15px; background: #f00; color: white; border: none;'>
        ENTRER DE FORCE DANS LE DASHBOARD
      </button>";

// 6. VERSION ULTIME DU DASHBOARD
echo "<h2>5. 📄 CODE DASHBOARD ULTIME</h2>";
echo "<pre style='background: #111; padding: 10px; overflow: auto; max-height: 300px;'>";
echo htmlspecialchars('<?php
// dashboard_exorcised.php - Version ultime
session_start();

// DEBUG FORCÉ
error_log("=== EXORCISM ENTRY ===");
error_log("SESSION: " . print_r($_SESSION, true));
error_log("GET: " . print_r($_GET, true));

// BYPASS TEMPORAIRE - À ENLEVER APRÈS
if (isset($_GET["force"])) {
    $_SESSION["user_id"] = 2;
    $_SESSION["user_email"] = "test@test.com";
}

// VÉRIFICATION ULTRA SIMPLE
if (empty($_SESSION["user_id"])) {
    header("Location: http://' . $_SERVER['HTTP_HOST'] . '/smart_phpixel/smart_pixel_v2/public/login.php?error=no_session");
    exit();
}

// SI ON ARRIVE ICI, ÇA MARCHE
echo "<h1>🎉 DASHBOARD ACCESSIBLE!</h1>";
echo "User ID: " . $_SESSION["user_id"];
// ... reste du code dashboard
?>');
echo "</pre>";

echo "<script>
function forceEntry() {
    // Ouverture avec paramètre force
    window.open('$dashboard_url&force=1', '_blank');
    
    // Injection JS dans l'iframe
    var iframe = document.getElementById('exorcismFrame');
    iframe.contentWindow.postMessage('FORCE_ENTRY', '*');
}
</script>";

echo "<hr><h1 class='blessed'>✨ SI ÇA MARCHE TOUJOURS PAS :</h1>";
echo "<ol>
<li>Crée un fichier <strong>dashboard_simple.php</strong> avec le code ci-dessus</li>
<li>Teste-le directement</li>
<li>Si ça marche, le bug est dans TON dashboard.php</li>
<li>Si ça marche pas, le bug est dans la CONFIG PHP/MAMP</li>
</ol>";

echo "<p style='color: #ff0; font-size: 18px;'>Poste une capture d'écran de l'iframe + les logs PHP (error_log)</p>";

echo "</body></html>";
ob_end_flush();