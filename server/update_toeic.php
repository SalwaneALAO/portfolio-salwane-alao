<?php
/**
 * Script pour mettre à jour le lien TOEIC dans la base de données
 * Accès : http://localhost/Portfolio/server/update_toeic.php
 */

require_once __DIR__ . '/config/database.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Mise à jour TOEIC</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #1e293b; color: #fff; }
        .success { color: #10b981; }
        .error { color: #ef4444; }
    </style>
</head>
<body>
    <h1>🔗 Mise à jour du lien TOEIC</h1>
    <hr>";

try {
    $pdo = getDBConnection();
    
    $toeicUrl = 'https://www.etsglobal.org/fr/en/digital-score-report/F52F1F6398C5E176AC5C315AB1EF063A5F2568AA85AD8C6281F8971C0D62A500TUFqajdlTVBTLzZGdmpqZGhtZEx2RkM0Vy9VQmkyWkVoYWQrMGlkY2kyVUFGUjZX';
    
    // Vérifier si la colonne existe
    $stmt = $pdo->query("SHOW COLUMNS FROM languages LIKE 'toeic_url'");
    if ($stmt->rowCount() == 0) {
        // Ajouter la colonne
        $pdo->exec("ALTER TABLE languages ADD COLUMN toeic_url VARCHAR(500) AFTER flag_emoji");
        echo "<p class='success'>✅ Colonne toeic_url ajoutée</p>";
    }
    
    // Mettre à jour le lien TOEIC
    $stmt = $pdo->prepare("UPDATE languages SET toeic_url = ? WHERE name = 'Anglais'");
    $stmt->execute([$toeicUrl]);
    
    echo "<p class='success'>✅ Lien TOEIC mis à jour avec succès !</p>";
    echo "<p><a href='api/portfolio.php' style='color: #3b82f6;'>Vérifier l'API</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</body></html>";
?>


