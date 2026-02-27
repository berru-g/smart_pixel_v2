<?php
// load_doc.php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Chemin vers doc.md (ajuste selon ta structure)
$filePath = __DIR__ . '/doc.md';

// Vérifie si le fichier existe
if (!file_exists($filePath)) {
    http_response_code(404);
    echo json_encode([
        'error' => 'Fichier introuvable',
        'debug' => [
            'chemin_recherche' => $filePath,
            'chemin_absolu' => realpath(__DIR__),
            'fichiers_dans_dossier' => array_slice(scandir(__DIR__), 2) // Liste les fichiers sauf . et ..
        ]
    ]);
    exit;
}

// Lit le fichier
$content = file_get_contents($filePath);
if ($content === false) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Impossible de lire le fichier',
        'debug' => [
            'permissions' => substr(sprintf('%o', fileperms($filePath)), -4),
            'proprietaire' => posix_getpwuid(fileowner($filePath))['name']
        ]
    ]);
    exit;
}

// Extraire les sections (titres #, ##, etc.)
$sections = [];
preg_match_all('/^(#+)\s+(.*$)/m', $content, $matches, PREG_SET_ORDER);
foreach ($matches as $match) {
    $level = strlen($match[1]);
    $title = trim($match[2]);
    $anchor = strtolower(preg_replace('/[^a-z0-9]+/', '-', $title));
    $sections[] = [
        'level' => $level,
        'title' => $title,
        'anchor' => $anchor
    ];
}

echo json_encode([
    'success' => true,
    'content' => $content,
    'sections' => $sections,
    'file_path' => $filePath,
    'file_size' => filesize($filePath)
]);
?>
