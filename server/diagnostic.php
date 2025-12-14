<?php
/**
 * Script de diagnostic COMPLET - Voir EXACTEMENT ce qui ne va pas
 * Accès : http://localhost/Portfolio/server/diagnostic.php
 */

require_once __DIR__ . '/config/database.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Diagnostic Complet</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #1e293b; color: #fff; }
        .success { color: #10b981; font-weight: bold; }
        .error { color: #ef4444; font-weight: bold; }
        .info { color: #3b82f6; }
        .warning { color: #f59e0b; }
        .box { background: #0f172a; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #3b82f6; }
        pre { background: #1e293b; padding: 10px; border-radius: 5px; overflow-x: auto; font-size: 12px; }
        button { background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        button:hover { background: #2563eb; }
    </style>
</head>
<body>
    <h1>🔍 Diagnostic Complet</h1>
    <hr>";

try {
    $pdo = getDBConnection();
    echo "<div class='box'><p class='success'>✅ Connexion BDD OK</p></div>";
    
    // 1. Vérifier la structure des tables
    echo "<h2>1. Structure des tables</h2>";
    
    $tables = ['hero', 'skills', 'languages'];
    foreach ($tables as $table) {
        echo "<div class='box'>";
        echo "<h3>Table: $table</h3>";
        try {
            $stmt = $pdo->query("DESCRIBE $table");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "<p class='info'>Colonnes : " . implode(', ', $columns) . "</p>";
            
            // Vérifications spécifiques
            if ($table === 'skills' && !in_array('logo_url', $columns)) {
                echo "<p class='error'>❌ Colonne logo_url MANQUANTE !</p>";
                echo "<button onclick=\"window.location.href='fix_all.php'\">Corriger maintenant</button>";
            }
            if ($table === 'languages' && !in_array('toeic_url', $columns)) {
                echo "<p class='error'>❌ Colonne toeic_url MANQUANTE !</p>";
            }
            if ($table === 'hero' && !in_array('profile_picture', $columns)) {
                echo "<p class='error'>❌ Colonne profile_picture MANQUANTE !</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>❌ Erreur : " . $e->getMessage() . "</p>";
        }
        echo "</div>";
    }
    
    // 2. Vérifier les données
    echo "<h2>2. Données dans les tables</h2>";
    
    // Skills
    echo "<div class='box'>";
    echo "<h3>Compétences (skills)</h3>";
    $stmt = $pdo->query("SELECT COUNT(*) as total, COUNT(logo_url) as with_logo FROM skills");
    $stats = $stmt->fetch();
    echo "<p class='info'>Total : {$stats['total']} compétences</p>";
    echo "<p class='info'>Avec logo : {$stats['with_logo']} compétences</p>";
    
    if ($stats['total'] < 30) {
        echo "<p class='error'>❌ Il manque des compétences ! (attendu: 31, trouvé: {$stats['total']})</p>";
    }
    
    $stmt = $pdo->query("SELECT name, category, logo_url FROM skills ORDER BY category, name LIMIT 10");
    echo "<p><strong>Premières compétences :</strong></p><ul>";
    foreach ($stmt->fetchAll() as $skill) {
        $logoStatus = !empty($skill['logo_url']) ? '✅' : '❌';
        echo "<li>{$skill['name']} ({$skill['category']}) $logoStatus</li>";
    }
    echo "</ul></div>";
    
    // Languages
    echo "<div class='box'>";
    echo "<h3>Langues (languages)</h3>";
    $stmt = $pdo->query("SELECT * FROM languages");
    $languages = $stmt->fetchAll();
    foreach ($languages as $lang) {
        echo "<p><strong>{$lang['name']}</strong> : {$lang['level']}";
        if (isset($lang['toeic_url']) && !empty($lang['toeic_url'])) {
            echo " <span class='success'>✅ Lien TOEIC présent</span>";
            echo "<br><small style='color: #94a3b8;'>{$lang['toeic_url']}</small>";
        } else {
            echo " <span class='error'>❌ Lien TOEIC MANQUANT</span>";
        }
        echo "</p>";
    }
    echo "</div>";
    
    // Hero
    echo "<div class='box'>";
    echo "<h3>Hero</h3>";
    $stmt = $pdo->query("SELECT * FROM hero LIMIT 1");
    $hero = $stmt->fetch();
    if ($hero) {
        echo "<p><strong>Nom :</strong> {$hero['name']}</p>";
        if (isset($hero['profile_picture']) && !empty($hero['profile_picture'])) {
            echo "<p class='success'>✅ Photo configurée : {$hero['profile_picture']}</p>";
            echo "<p class='info'>ℹ️ Vérifiez que le fichier existe : client/public/images/profile-picture.jpg</p>";
        } else {
            echo "<p class='error'>❌ Photo NON configurée</p>";
        }
    }
    echo "</div>";
    
    // 3. Tester l'API
    echo "<h2>3. Test de l'API</h2>";
    echo "<div class='box'>";
    
    ob_start();
    include __DIR__ . '/api/portfolio.php';
    $apiOutput = ob_get_clean();
    
    $apiData = json_decode($apiOutput, true);
    
    if ($apiData) {
        echo "<p class='success'>✅ API retourne du JSON valide</p>";
        echo "<p class='info'>Compétences dans l'API : " . (isset($apiData['skills']) ? count($apiData['skills']) : 0) . "</p>";
        echo "<p class='info'>Langues dans l'API : " . (isset($apiData['languages']) ? count($apiData['languages']) : 0) . "</p>";
        
        // Vérifier le lien TOEIC dans l'API
        if (isset($apiData['languages'])) {
            foreach ($apiData['languages'] as $lang) {
                if ($lang['name'] === 'Anglais') {
                    if (isset($lang['toeic_url']) && !empty($lang['toeic_url'])) {
                        echo "<p class='success'>✅ Lien TOEIC dans l'API : OK</p>";
                    } else {
                        echo "<p class='error'>❌ Lien TOEIC MANQUANT dans l'API</p>";
                    }
                }
            }
        }
        
        // Vérifier profile_picture dans l'API
        if (isset($apiData['hero']['profile_picture']) && !empty($apiData['hero']['profile_picture'])) {
            echo "<p class='success'>✅ Photo dans l'API : {$apiData['hero']['profile_picture']}</p>";
        } else {
            echo "<p class='error'>❌ Photo MANQUANTE dans l'API</p>";
        }
        
        echo "<details><summary>Voir le JSON complet</summary><pre>" . htmlspecialchars(json_encode($apiData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre></details>";
    } else {
        echo "<p class='error'>❌ L'API ne retourne pas du JSON valide</p>";
        echo "<pre>" . htmlspecialchars(substr($apiOutput, 0, 500)) . "</pre>";
    }
    echo "</div>";
    
    // 4. Actions de correction
    echo "<h2>4. Actions</h2>";
    echo "<div class='box'>";
    echo "<p><button onclick=\"window.location.href='fix_all.php'\">🔧 Corriger TOUT</button></p>";
    echo "<p><button onclick=\"window.location.href='api/portfolio.php'\">🔗 Voir l'API</button></p>";
    echo "<p><button onclick=\"window.location.href='test_api.php'\">🧪 Tester l'API</button></p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='box'><p class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p></div>";
}

echo "</body></html>";
?>


