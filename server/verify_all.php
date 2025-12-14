<?php
/**
 * Script de vérification finale - Vérifie que TOUT est en place
 * Accès : http://localhost/Portfolio/server/verify_all.php
 */

require_once __DIR__ . '/config/database.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Vérification Finale</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #1e293b; color: #fff; }
        .success { color: #10b981; font-weight: bold; }
        .error { color: #ef4444; font-weight: bold; }
        .info { color: #3b82f6; }
        .box { background: #0f172a; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #3b82f6; }
        .checklist { list-style: none; padding: 0; }
        .checklist li { padding: 8px 0; border-bottom: 1px solid #334155; }
        .checklist li:before { content: '✓ '; color: #10b981; font-weight: bold; margin-right: 10px; }
        .checklist li.error:before { content: '✗ '; color: #ef4444; }
    </style>
</head>
<body>
    <h1>✅ Vérification Finale</h1>
    <hr>";

try {
    $pdo = getDBConnection();
    
    $checks = [];
    
    // 1. Vérifier les colonnes
    echo "<h2>1. Structure de la base de données</h2>";
    $tables = [
        ['hero', 'profile_picture'],
        ['languages', 'toeic_url'],
        ['skills', 'logo_url']
    ];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("DESCRIBE {$table[0]}");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (in_array($table[1], $columns)) {
            $checks[] = "Colonne {$table[1]} dans {$table[0]}";
            echo "<p class='success'>✅ {$table[0]}.{$table[1]}</p>";
        } else {
            echo "<p class='error'>❌ {$table[0]}.{$table[1]} MANQUANTE</p>";
        }
    }
    
    // 2. Vérifier les données
    echo "<h2>2. Données</h2>";
    
    // Hero
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM hero");
    $heroCount = $stmt->fetch()['count'];
    if ($heroCount > 0) {
        $stmt = $pdo->query("SELECT profile_picture FROM hero LIMIT 1");
        $hero = $stmt->fetch();
        if (!empty($hero['profile_picture'])) {
            $checks[] = "Photo de profil configurée";
            echo "<p class='success'>✅ Hero avec photo : {$hero['profile_picture']}</p>";
        } else {
            echo "<p class='error'>❌ Photo de profil non configurée</p>";
        }
    }
    
    // Skills
    $stmt = $pdo->query("SELECT COUNT(*) as total, COUNT(logo_url) as with_logo FROM skills");
    $skills = $stmt->fetch();
    if ($skills['total'] >= 30) {
        $checks[] = "Compétences (31 attendues, {$skills['total']} trouvées)";
        echo "<p class='success'>✅ {$skills['total']} compétences</p>";
        if ($skills['with_logo'] == $skills['total']) {
            $checks[] = "Tous les logos des compétences";
            echo "<p class='success'>✅ Tous les logos présents</p>";
        } else {
            echo "<p class='error'>❌ {$skills['with_logo']}/{$skills['total']} compétences avec logo</p>";
        }
    } else {
        echo "<p class='error'>❌ Seulement {$skills['total']} compétences (attendu: 31+)</p>";
    }
    
    // Languages
    $stmt = $pdo->query("SELECT name, toeic_url FROM languages WHERE name = 'Anglais'");
    $english = $stmt->fetch();
    if ($english && !empty($english['toeic_url'])) {
        $checks[] = "Lien TOEIC configuré";
        echo "<p class='success'>✅ Lien TOEIC pour l'Anglais</p>";
    } else {
        echo "<p class='error'>❌ Lien TOEIC manquant</p>";
    }
    
    // Qualities
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM qualities");
    $qualitiesCount = $stmt->fetch()['count'];
    if ($qualitiesCount >= 4) {
        $checks[] = "Qualités ({$qualitiesCount})";
        echo "<p class='success'>✅ {$qualitiesCount} qualités</p>";
    } else {
        echo "<p class='error'>❌ Seulement {$qualitiesCount} qualités</p>";
    }
    
    // 3. Test API
    echo "<h2>3. Test de l'API</h2>";
    ob_start();
    include __DIR__ . '/api/portfolio.php';
    $apiOutput = ob_get_clean();
    $apiData = json_decode($apiOutput, true);
    
    if ($apiData) {
        echo "<p class='success'>✅ API fonctionne</p>";
        echo "<ul class='checklist'>";
        echo "<li>Hero : " . (isset($apiData['hero']) ? '✅' : '❌') . "</li>";
        echo "<li>Skills : " . (isset($apiData['skills']) ? count($apiData['skills']) . ' compétences' : '❌') . "</li>";
        echo "<li>Languages : " . (isset($apiData['languages']) ? count($apiData['languages']) . ' langues' : '❌') . "</li>";
        echo "<li>Qualities : " . (isset($apiData['qualities']) ? count($apiData['qualities']) . ' qualités' : '❌') . "</li>";
        echo "<li>Stats : " . (isset($apiData['stats']) ? count($apiData['stats']) . ' stats' : '❌') . "</li>";
        echo "<li>Projects : " . (isset($apiData['projects']) ? count($apiData['projects']) . ' projets' : '❌') . "</li>";
        echo "</ul>";
        
        // Vérifier le lien TOEIC dans l'API
        if (isset($apiData['languages'])) {
            foreach ($apiData['languages'] as $lang) {
                if ($lang['name'] === 'Anglais' && isset($lang['toeic_url']) && !empty($lang['toeic_url'])) {
                    $checks[] = "Lien TOEIC dans l'API";
                    echo "<p class='success'>✅ Lien TOEIC présent dans l'API</p>";
                    break;
                }
            }
        }
    } else {
        echo "<p class='error'>❌ API ne retourne pas de JSON valide</p>";
    }
    
    // Résumé
    echo "<hr><h2>📋 Résumé</h2>";
    echo "<p><strong>" . count($checks) . " vérification(s) réussie(s)</strong></p>";
    echo "<ul class='checklist'>";
    foreach ($checks as $check) {
        echo "<li>$check</li>";
    }
    echo "</ul>";
    
    if (count($checks) >= 5) {
        echo "<div class='box' style='border-color: #10b981;'>";
        echo "<p class='success' style='font-size: 1.2em;'>🎉 Tout semble correct !</p>";
        echo "<p>Votre portfolio devrait fonctionner correctement.</p>";
        echo "<p><strong>N'oubliez pas :</strong></p>";
        echo "<ul>";
        echo "<li>Placez votre photo dans : <code>client/public/images/profile-picture.jpg</code></li>";
        echo "<li>Videz le cache du navigateur (Ctrl+F5)</li>";
        echo "<li>Vérifiez la console du navigateur (F12) pour les erreurs</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div class='box' style='border-color: #ef4444;'>";
        echo "<p class='error'>⚠️ Certaines vérifications ont échoué</p>";
        echo "<p><a href='fix_all.php' style='color: #3b82f6;'>🔧 Exécuter la correction complète</a></p>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</body></html>";
?>


