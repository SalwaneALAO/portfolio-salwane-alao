<?php
/**
 * Script pour vérifier toutes les compétences dans la base de données
 * Accès : http://localhost/Portfolio/server/check_skills.php
 */

require_once __DIR__ . '/config/database.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Vérification Compétences</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #1e293b; color: #fff; }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .info { color: #3b82f6; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #475569; padding: 10px; text-align: left; }
        th { background: #334155; }
        tr:nth-child(even) { background: #1e293b; }
    </style>
</head>
<body>
    <h1>🔍 Vérification des Compétences</h1>
    <hr>";

try {
    $pdo = getDBConnection();
    
    // Compétences attendues selon votre CV
    $expectedSkills = [
        'Langages' => ['Python', 'C', 'Java', 'HTML', 'CSS', 'SQL', 'R', 'DAX'],
        'SGBD' => ['Oracle', 'phpMyAdmin', 'MySQL Server', 'MongoDB'],
        'Outils' => ['Jupyter Notebook', 'Visual Studio', 'Hadoop', 'Pack Office', 'Pandas', 'Numpy', 'Matplotlib', 'Seaborn', 'Gitlab'],
        'Visualisation' => ['Power BI', 'Arcgis Pro', 'QGIS', 'Tableau', 'Looker']
    ];
    
    // Récupérer toutes les compétences de la BDD
    $stmt = $pdo->query("SELECT name, level, category, logo_url FROM skills ORDER BY category, name");
    $skills = $stmt->fetchAll();
    
    echo "<h2>📊 Compétences dans la base de données : " . count($skills) . "</h2>";
    
    echo "<table>";
    echo "<tr><th>Catégorie</th><th>Nom</th><th>Niveau</th><th>Logo</th></tr>";
    
    $byCategory = [];
    foreach ($skills as $skill) {
        $byCategory[$skill['category']][] = $skill;
        $logoStatus = !empty($skill['logo_url']) ? '✅' : '❌';
        echo "<tr>";
        echo "<td>{$skill['category']}</td>";
        echo "<td>{$skill['name']}</td>";
        echo "<td>{$skill['level']}%</td>";
        echo "<td>{$logoStatus}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<h2>📋 Vérification par catégorie :</h2>";
    
    foreach ($expectedSkills as $category => $expected) {
        $found = isset($byCategory[$category]) ? array_column($byCategory[$category], 'name') : [];
        $missing = array_diff($expected, $found);
        
        echo "<h3>{$category}</h3>";
        echo "<p class='success'>✅ Trouvées : " . count($found) . " / " . count($expected) . "</p>";
        
        if (!empty($missing)) {
            echo "<p class='error'>❌ Manquantes : " . implode(', ', $missing) . "</p>";
        }
    }
    
    echo "<hr>";
    echo "<p><a href='api/portfolio.php' style='color: #3b82f6;'>Vérifier l'API Portfolio</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</body></html>";
?>


