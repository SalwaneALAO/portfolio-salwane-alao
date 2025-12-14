<?php
require_once __DIR__ . '/config/database.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT id, type, title, file_path, file_url FROM documents ORDER BY type, id");
    $docs = $stmt->fetchAll();
    
    echo "=== DOCUMENTS DANS LA BASE DE DONNÉES ===\n\n";
    
    foreach ($docs as $doc) {
        echo "ID: {$doc['id']}\n";
        echo "Type: {$doc['type']}\n";
        echo "Titre: {$doc['title']}\n";
        echo "Chemin fichier: " . ($doc['file_path'] ?: 'NULL') . "\n";
        echo "URL: " . ($doc['file_url'] ?: 'NULL') . "\n";
        echo "---\n";
    }
    
    echo "\n=== FICHIERS DANS uploads/ ===\n\n";
    $uploadsDir = __DIR__ . '/../client/public/uploads/';
    if (is_dir($uploadsDir)) {
        $files = scandir($uploadsDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && $file !== 'README.md' && $file !== '.gitkeep') {
                echo "- $file\n";
            }
        }
    } else {
        echo "Dossier uploads introuvable: $uploadsDir\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?>

