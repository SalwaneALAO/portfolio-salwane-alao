<?php
/**
 * Script de test de connexion à la base de données
 * Accès : http://localhost/Portfolio/server/test_connection.php
 */

require_once __DIR__ . '/config/database.php';

echo "<h1>Test de Connexion à la Base de Données</h1>";
echo "<hr>";

try {
    $pdo = getDBConnection();
    echo "<p style='color: green;'>✅ Connexion réussie à la base de données !</p>";
    
    // Test des tables
    echo "<h2>Vérification des tables :</h2>";
    $tables = ['hero', 'story', 'skills', 'projects', 'stats'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $result = $stmt->fetch();
        $count = $result['count'];
        
        if ($count > 0) {
            echo "<p style='color: green;'>✅ Table '$table' : $count enregistrement(s)</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Table '$table' : vide (0 enregistrement)</p>";
        }
    }
    
    // Test de récupération des données
    echo "<h2>Test de récupération des données :</h2>";
    
    $stmt = $pdo->query("SELECT * FROM hero LIMIT 1");
    $hero = $stmt->fetch();
    if ($hero) {
        echo "<p style='color: green;'>✅ Hero : " . htmlspecialchars($hero['name']) . "</p>";
    }
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM story");
    $storyCount = $stmt->fetch()['count'];
    echo "<p style='color: green;'>✅ Story : $storyCount étape(s)</p>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM skills");
    $skillsCount = $stmt->fetch()['count'];
    echo "<p style='color: green;'>✅ Skills : $skillsCount compétence(s)</p>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM projects");
    $projectsCount = $stmt->fetch()['count'];
    echo "<p style='color: green;'>✅ Projects : $projectsCount projet(s)</p>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM stats");
    $statsCount = $stmt->fetch()['count'];
    echo "<p style='color: green;'>✅ Stats : $statsCount statistique(s)</p>";
    
    echo "<hr>";
    echo "<p><strong>🎉 Tout fonctionne correctement !</strong></p>";
    echo "<p><a href='api/portfolio.php'>Tester l'API Portfolio</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Vérifiez :</strong></p>";
    echo "<ul>";
    echo "<li>Que MySQL est démarré dans WAMP</li>";
    echo "<li>Que la base de données 'portfolio_db' existe</li>";
    echo "<li>Les identifiants dans config/database.php</li>";
    echo "</ul>";
}
?>


