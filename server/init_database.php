<?php
/**
 * Script d'initialisation de la base de données
 * Exécutez ce fichier une fois pour créer et remplir la base de données
 * Accès : http://localhost/Portfolio/server/init_database.php
 */

require_once __DIR__ . '/config/database.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Initialisation Base de Données</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #1e293b; color: #fff; }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .info { color: #3b82f6; }
        pre { background: #0f172a; padding: 15px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🚀 Initialisation de la Base de Données</h1>
    <hr>";

try {
    $pdo = getDBConnection();
    echo "<p class='success'>✅ Connexion à la base de données réussie !</p>";
    
    // Lire le fichier SQL
    $sqlFile = __DIR__ . '/database.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("Le fichier database.sql n'existe pas !");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Supprimer les commentaires et diviser en requêtes
    $sql = preg_replace('/--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    // Exécuter les requêtes
    $queries = explode(';', $sql);
    $executed = 0;
    $errors = [];
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (empty($query)) {
            continue;
        }
        
        try {
            $pdo->exec($query);
            $executed++;
        } catch (PDOException $e) {
            // Ignorer les erreurs de table déjà existante
            if (strpos($e->getMessage(), 'already exists') === false && 
                strpos($e->getMessage(), 'Duplicate entry') === false) {
                $errors[] = $e->getMessage();
            }
        }
    }
    
    echo "<p class='success'>✅ $executed requêtes exécutées avec succès !</p>";
    
    if (!empty($errors)) {
        echo "<p class='error'>⚠️ Quelques erreurs (peut être normal si les tables existent déjà) :</p>";
        echo "<pre>" . implode("\n", $errors) . "</pre>";
    }
    
    // Vérifier les données
    echo "<h2>📊 Vérification des données :</h2>";
    
    $tables = ['hero', 'story', 'skills', 'projects', 'stats', 'languages', 'documents', 'qualities'];
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $result = $stmt->fetch();
            $count = $result['count'];
            
            if ($count > 0) {
                echo "<p class='success'>✅ Table '$table' : $count enregistrement(s)</p>";
            } else {
                echo "<p class='error'>⚠️ Table '$table' : vide (0 enregistrement)</p>";
                
                // Si la table est vide, on la remplit
                fillTable($pdo, $table);
            }
        } catch (PDOException $e) {
            echo "<p class='error'>❌ Erreur avec la table '$table' : " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<hr>";
    echo "<p class='success'><strong>🎉 Initialisation terminée !</strong></p>";
    echo "<p><a href='api/portfolio.php' style='color: #3b82f6;'>Tester l'API Portfolio</a></p>";
    echo "<p><a href='test_connection.php' style='color: #3b82f6;'>Tester la connexion</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Vérifiez :</strong></p>";
    echo "<ul>";
    echo "<li>Que MySQL est démarré dans WAMP</li>";
    echo "<li>Les identifiants dans config/database.php</li>";
    echo "<li>Que vous avez les droits de création de base de données</li>";
    echo "</ul>";
}

function fillTable($pdo, $table) {
    try {
        switch ($table) {
            case 'hero':
                $pdo->exec("INSERT INTO hero (name, title, subtitle, description, profile_picture) VALUES
                    ('SALWANE ALAO', 'Data Analyst & Data Scientist', 'Data Visualisation & Big Data | En recherche active d\'un CDI ou CDD', 
                     'Passionné par l\'analyse de données et la visualisation, je transforme les informations brutes en décisions éclairées. Alternant chez GRDF depuis 2023, j\'ai contribué à améliorer la fiabilité des données de 25% et à accélérer la prise de décision stratégique. Spécialisé en Big Data, Machine Learning Operations (MLOps) et visualisation de données.',
                     '/images/profile-picture.jpg')
                    ON DUPLICATE KEY UPDATE name=name");
                break;
                
            case 'story':
                $pdo->exec("INSERT INTO story (year, title, description, icon, display_order) VALUES
                    ('2020', 'Baccalauréat Scientifique', 'Obtention du Baccalauréat Scientifique à Cotonou, Bénin. Première approche avec les mathématiques, physique et informatique.', '🎓', 1),
                    ('2020-2022', 'CPPA Père Aupiais', 'Formation en Mathématiques, Physique et Informatique. Développement des compétences en systèmes d\'exploitation et adaptation.', '📚', 2),
                    ('2021', 'Stage - Ministère du Travail', 'Stage au Ministère du Travail et de la Fonction Publique au Bénin. Gestion du parc informatique, assistance technique et maintenance. Réduction de 30% des incidents techniques.', '💼', 3),
                    ('2022-2025', 'ESIGELEC - BIG DATA', 'Formation d\'ingénieur en Génie Électrique spécialité BIG DATA à Rouen, France. Apprentissage du Machine Learning Operations (MLOps), Microsoft Dynamics et 62 compétences techniques.', '🚀', 4),
                    ('2023-2025', 'Alternance GRDF', 'Alternance de 2 ans chez GRDF (Gaz Réseau Distribution France) à Rouen. Data Analyst/Data Scientist/BI Analyst. Centralisation des données, développement d\'outils BI, amélioration de la fiabilité des données de 25%.', '⚡', 5),
                    ('2024', 'Mission Internationale - BOKU University', 'Mission à BOKU University à St. Pölten, Autriche. Data Analyst pour le monitoring des émissions de gaz à effet de serre. Analyse environnementale identifiant une réduction potentielle de 12% des émissions.', '🌍', 6),
                    ('2024', 'Certification TOEIC', 'Obtention du TOEIC (Test of English for International Communication) - Niveau B2 Professionnel. Certification valide jusqu\'en décembre 2026.', '🏆', 7),
                    ('2025', 'Aujourd\'hui', 'En recherche active d\'un CDI ou CDD en tant que Data Analyst, Data Engineer, Analyste gestion de données ou Analyste qualité des données. Continuant à évoluer et transformer les données en valeur.', '🎯', 8)
                    ON DUPLICATE KEY UPDATE year=year");
                break;
                
            case 'qualities':
                $pdo->exec("INSERT INTO qualities (name, icon) VALUES
                    ('Esprit coopératif', '🤝'),
                    ('Autonome', '🎯'),
                    ('Dynamique', '⚡'),
                    ('Analyse stratégique', '🧠')
                    ON DUPLICATE KEY UPDATE name=name");
                break;
                
            case 'stats':
                $pdo->exec("INSERT INTO stats (label, value, icon) VALUES
                    ('Projets Réalisés', 4, '📊'),
                    ('Années d\'Expérience', 4, '⏱️'),
                    ('Entreprises', 3, '🏢'),
                    ('Certifications', 1, '🎓')
                    ON DUPLICATE KEY UPDATE label=label");
                break;
                
            case 'languages':
                $pdo->exec("INSERT INTO languages (name, level, flag_emoji) VALUES
                    ('Français', 'Langue maternelle', '🇫🇷'),
                    ('Anglais', 'B2 - Professionnel (TOEIC)', '🇬🇧'),
                    ('Espagnol', 'Intermédiaire', '🇪🇸')
                    ON DUPLICATE KEY UPDATE name=name");
                break;
        }
        echo "<p class='info'>ℹ️ Données insérées dans la table '$table'</p>";
    } catch (PDOException $e) {
        // Ignorer les erreurs de doublons
    }
}

echo "</body></html>";
?>


