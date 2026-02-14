<?php

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

// Vérifie si connecté
if (!Auth::isLoggedIn()) {
    // Redirige UNIQUEMENT si pas connecté
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
// 2. Récupérer les sites de l'utilisateur // dégager query pour prepare 
$stmt = $pdo->prepare("SELECT * FROM user_sites WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$userSites = $stmt->fetchAll();
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Souverain</title>
    <style>
        #chartdiv {
  width: 100%;
  min-height: 100vh;
  background: var(--text-color); 
}
</style>
</head>
<body>
    <div id="agenda">
        <h2><a href="../public/dashboard.php">↶</a></h2>
        <div id="chartdiv"></div>
    </div>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/plugins/colorPicker.js"></script>
<script src="https://cdn.amcharts.com/lib/5/gantt.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code - IDENTIQUE à la démo mais en français -->
<script>
am5.ready(function() {

var root = am5.Root.new("chartdiv");

// Set themes
root.setThemes([
  am5themes_Animated.new(root)
]);

// Create Gantt chart
var gantt = root.container.children.push(am5gantt.Gantt.new(root, {}));
gantt.get("colors").set("step", 3);
gantt.editButton.set("visible", true);

// Set category data - EN FRANÇAIS
gantt.yAxis.data.setAll([{
  name: "Idée",
  id: "gantt_0"
}, {
  name: "Lancement",
  id: "gantt_1"
}, {
  name: "Planification",
  id: "gantt_2"
}, {
  name: "Développement",
  id: "gantt_3"
}, {
  name: "Tests",
  id: "gantt_4"
}, {
  name: "Finalisation",
  id: "gantt_5"
}, {
  name: "Publication",
  id: "gantt_6"
}]);

// Set series data - IDENTIQUE mais commentaires en français
gantt.series.data.setAll([{
  start: 1758142800000,
  duration: 0,
  progress: 1,
  id: "gantt_0",
  linkTo: ["gantt_1"]
}, {
  start: 1758142800000,
  duration: 2,
  progress: 1,
  id: "gantt_1",
  linkTo: ["gantt_2"]
}, {
  start: 1758488400000,
  duration: 2,
  progress: 0.2,
  id: "gantt_2",
  linkTo: ["gantt_3"]
}, {
  start: 1758661200000,
  duration: 1,
  progress: 0.8,
  id: "gantt_3",
  linkTo: ["gantt_4"]
}, {
  start: 1758747600000,
  duration: 3,
  progress: 0,
  id: "gantt_4",
  linkTo: ["gantt_5"]
}, {
  start: 1759179600000,
  duration: 0,
  progress: 0,
  id: "gantt_5",
  linkTo: ["gantt_6"]
}, {
  start: 1759179600000,
  duration: 4,
  progress: 0,
  id: "gantt_6"
}]);

gantt.appear();

}); // end am5.ready()
</script>

</body>
</html>