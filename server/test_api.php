<?php
/**
 * Script pour tester l'API et voir exactement ce qui est retourné
 * Accès : http://localhost/Portfolio/server/test_api.php
 */

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Test API</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #1e293b; color: #fff; }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        pre { background: #0f172a; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .section { margin: 20px 0; padding: 15px; background: #1e293b; border-left: 4px solid #3b82f6; }
    </style>
</head>
<body>
    <h1>🧪 Test de l'API Portfolio</h1>
    <hr>";

// Tester l'API
$apiUrl = 'http://localhost/Portfolio/server/api/portfolio.php';
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<div class='section'>";
echo "<h2>📡 Réponse HTTP : $httpCode</h2>";

if ($httpCode == 200) {
    $data = json_decode($response, true);
    
    if ($data) {
        echo "<p class='success'>✅ JSON valide</p>";
        
        echo "<h3>📊 Structure des données :</h3>";
        echo "<ul>";
        echo "<li><strong>Hero:</strong> " . (isset($data['hero']) ? '✅ Présent' : '❌ Manquant') . "</li>";
        echo "<li><strong>Story:</strong> " . (isset($data['story']) ? '✅ ' . count($data['story']) . ' éléments' : '❌ Manquant') . "</li>";
        echo "<li><strong>Skills:</strong> " . (isset($data['skills']) ? '✅ ' . count($data['skills']) . ' compétences' : '❌ Manquant') . "</li>";
        echo "<li><strong>Languages:</strong> " . (isset($data['languages']) ? '✅ ' . count($data['languages']) . ' langues' : '❌ Manquant') . "</li>";
        echo "<li><strong>Qualities:</strong> " . (isset($data['qualities']) ? '✅ ' . count($data['qualities']) . ' qualités' : '❌ Manquant') . "</li>";
        echo "<li><strong>Stats:</strong> " . (isset($data['stats']) ? '✅ ' . count($data['stats']) . ' stats' : '❌ Manquant') . "</li>";
        echo "<li><strong>Projects:</strong> " . (isset($data['projects']) ? '✅ ' . count($data['projects']) . ' projets' : '❌ Manquant') . "</li>";
        echo "<li><strong>Documents:</strong> " . (isset($data['documents']) ? '✅ ' . count($data['documents']) . ' documents' : '❌ Manquant') . "</li>";
        echo "</ul>";
        
        // Vérifier le lien TOEIC
        if (isset($data['languages'])) {
            foreach ($data['languages'] as $lang) {
                if ($lang['name'] === 'Anglais') {
                    echo "<h3>🔗 Lien TOEIC pour l'Anglais :</h3>";
                    if (isset($lang['toeic_url']) && !empty($lang['toeic_url'])) {
                        echo "<p class='success'>✅ Lien présent : <a href='{$lang['toeic_url']}' target='_blank' style='color: #3b82f6;'>{$lang['toeic_url']}</a></p>";
                    } else {
                        echo "<p class='error'>❌ Lien TOEIC manquant !</p>";
                    }
                }
            }
        }
        
        // Vérifier profile_picture
        if (isset($data['hero'])) {
            echo "<h3>📸 Photo de profil :</h3>";
            if (isset($data['hero']['profile_picture']) && !empty($data['hero']['profile_picture'])) {
                echo "<p class='success'>✅ Chemin : {$data['hero']['profile_picture']}</p>";
            } else {
                echo "<p class='error'>❌ Photo de profil manquante !</p>";
            }
        }
        
        // Compétences par catégorie
        if (isset($data['skills'])) {
            $byCategory = [];
            foreach ($data['skills'] as $skill) {
                $cat = $skill['category'] ?? 'Non catégorisé';
                $byCategory[$cat][] = $skill;
            }
            echo "<h3>📚 Compétences par catégorie :</h3>";
            foreach ($byCategory as $cat => $skills) {
                echo "<p><strong>$cat:</strong> " . count($skills) . " compétence(s)</p>";
            }
        }
        
        echo "<h3>📄 Données complètes (JSON) :</h3>";
        echo "<pre>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";
        
    } else {
        echo "<p class='error'>❌ Erreur de décodage JSON</p>";
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
    }
} else {
    echo "<p class='error'>❌ Erreur HTTP : $httpCode</p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}

echo "</div>";

echo "<hr>";
echo "<p><a href='update_all.php' style='color: #3b82f6; text-decoration: underline;'>🔄 Mettre à jour la base de données</a></p>";
echo "<p><a href='api/portfolio.php' style='color: #3b82f6; text-decoration: underline;'>🔗 Voir l'API directement</a></p>";

echo "</body></html>";
?>


