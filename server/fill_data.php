<?php
/**
 * Script pour remplir la base de données avec les données
 * Utilisez ce script si les tables existent mais sont vides
 * Accès : http://localhost/Portfolio/server/fill_data.php
 */

require_once __DIR__ . '/config/database.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Remplissage Base de Données</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #1e293b; color: #fff; }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .info { color: #3b82f6; }
    </style>
</head>
<body>
    <h1>📝 Remplissage de la Base de Données</h1>
    <hr>";

try {
    $pdo = getDBConnection();
    echo "<p class='success'>✅ Connexion réussie !</p>";
    
    // Hero
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM hero");
    if ($stmt->fetch()['count'] == 0) {
        $pdo->exec("INSERT INTO hero (name, title, subtitle, description, profile_picture) VALUES
            ('SALWANE ALAO', 'Data Analyst & Data Scientist', 'Data Visualisation & Big Data | En recherche active d\'un CDI ou CDD', 
             'Passionné par l\'analyse de données et la visualisation, je transforme les informations brutes en décisions éclairées. Alternant chez GRDF depuis 2023, j\'ai contribué à améliorer la fiabilité des données de 25% et à accélérer la prise de décision stratégique. Spécialisé en Big Data, Machine Learning Operations (MLOps) et visualisation de données.',
             '/images/profile-picture.jpg')");
        echo "<p class='success'>✅ Hero inséré</p>";
    } else {
        echo "<p class='info'>ℹ️ Hero existe déjà</p>";
    }
    
    // Story
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM story");
    if ($stmt->fetch()['count'] == 0) {
        $pdo->exec("INSERT INTO story (year, title, description, icon, display_order) VALUES
            ('2020', 'Baccalauréat Scientifique', 'Obtention du Baccalauréat Scientifique à Cotonou, Bénin. Première approche avec les mathématiques, physique et informatique.', '🎓', 1),
            ('2020-2022', 'CPPA Père Aupiais', 'Formation en Mathématiques, Physique et Informatique. Développement des compétences en systèmes d\'exploitation et adaptation.', '📚', 2),
            ('2021', 'Stage - Ministère du Travail', 'Stage au Ministère du Travail et de la Fonction Publique au Bénin. Gestion du parc informatique, assistance technique et maintenance. Réduction de 30% des incidents techniques.', '💼', 3),
            ('2022-2025', 'ESIGELEC - BIG DATA', 'Formation d\'ingénieur en Génie Électrique spécialité BIG DATA à Rouen, France. Apprentissage du Machine Learning Operations (MLOps), Microsoft Dynamics et 62 compétences techniques.', '🚀', 4),
            ('2023-2025', 'Alternance GRDF', 'Alternance de 2 ans chez GRDF (Gaz Réseau Distribution France) à Rouen. Data Analyst/Data Scientist/BI Analyst. Centralisation des données, développement d\'outils BI, amélioration de la fiabilité des données de 25%.', '⚡', 5),
            ('2024', 'Mission Internationale - BOKU University', 'Mission à BOKU University à St. Pölten, Autriche. Data Analyst pour le monitoring des émissions de gaz à effet de serre. Analyse environnementale identifiant une réduction potentielle de 12% des émissions.', '🌍', 6),
            ('2024', 'Certification TOEIC', 'Obtention du TOEIC (Test of English for International Communication) - Niveau B2 Professionnel. Certification valide jusqu\'en décembre 2026.', '🏆', 7),
            ('2025', 'Aujourd\'hui', 'En recherche active d\'un CDI ou CDD en tant que Data Analyst, Data Engineer, Analyste gestion de données ou Analyste qualité des données. Continuant à évoluer et transformer les données en valeur.', '🎯', 8)");
        echo "<p class='success'>✅ Story insérée (8 étapes)</p>";
    } else {
        echo "<p class='info'>ℹ️ Story existe déjà</p>";
    }
    
    // Qualities
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM qualities");
    if ($stmt->fetch()['count'] == 0) {
        $pdo->exec("INSERT INTO qualities (name, icon) VALUES
            ('Esprit coopératif', '🤝'),
            ('Autonome', '🎯'),
            ('Dynamique', '⚡'),
            ('Analyse stratégique', '🧠')");
        echo "<p class='success'>✅ Qualities insérées (4 qualités)</p>";
    } else {
        echo "<p class='info'>ℹ️ Qualities existe déjà</p>";
    }
    
    // Stats
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM stats");
    if ($stmt->fetch()['count'] == 0) {
        $pdo->exec("INSERT INTO stats (label, value, icon) VALUES
            ('Projets Réalisés', 4, '📊'),
            ('Années d\'Expérience', 4, '⏱️'),
            ('Entreprises', 3, '🏢'),
            ('Certifications', 1, '🎓')");
        echo "<p class='success'>✅ Stats insérées (4 stats)</p>";
    } else {
        echo "<p class='info'>ℹ️ Stats existe déjà</p>";
    }
    
    // Languages
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM languages");
    if ($stmt->fetch()['count'] == 0) {
        $pdo->exec("INSERT INTO languages (name, level, flag_emoji, toeic_url) VALUES
            ('Français', 'Langue maternelle', '🇫🇷', NULL),
            ('Anglais', 'B2 - Professionnel (TOEIC)', '🇬🇧', 'https://www.etsglobal.org/fr/en/digital-score-report/F52F1F6398C5E176AC5C315AB1EF063A5F2568AA85AD8C6281F8971C0D62A500TUFqajdlTVBTLzZGdmpqZGhtZEx2RkM0Vy9VQmkyWkVoYWQrMGlkY2kyVUFGUjZX'),
            ('Espagnol', 'Intermédiaire', '🇪🇸', NULL)");
        echo "<p class='success'>✅ Languages insérées (3 langues avec lien TOEIC)</p>";
    } else {
        // Mettre à jour le lien TOEIC si la langue existe déjà
        $pdo->exec("UPDATE languages SET toeic_url = 'https://www.etsglobal.org/fr/en/digital-score-report/F52F1F6398C5E176AC5C315AB1EF063A5F2568AA85AD8C6281F8971C0D62A500TUFqajdlTVBTLzZGdmpqZGhtZEx2RkM0Vy9VQmkyWkVoYWQrMGlkY2kyVUFGUjZX' WHERE name = 'Anglais'");
        echo "<p class='info'>ℹ️ Languages existe déjà - Lien TOEIC mis à jour</p>";
    }
    
    // Skills (uniquement si vide)
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM skills");
    if ($stmt->fetch()['count'] == 0) {
        $sql = file_get_contents(__DIR__ . '/database.sql');
        preg_match('/INSERT INTO skills.*?;/s', $sql, $matches);
        if (!empty($matches[0])) {
            $pdo->exec($matches[0]);
            echo "<p class='success'>✅ Skills insérées</p>";
        }
    } else {
        echo "<p class='info'>ℹ️ Skills existe déjà</p>";
    }
    
    // Projects (uniquement si vide)
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM projects");
    if ($stmt->fetch()['count'] == 0) {
        $sql = file_get_contents(__DIR__ . '/database.sql');
        preg_match('/INSERT INTO projects.*?;/s', $sql, $matches);
        if (!empty($matches[0])) {
            $pdo->exec($matches[0]);
            echo "<p class='success'>✅ Projects insérés</p>";
        }
    } else {
        echo "<p class='info'>ℹ️ Projects existe déjà</p>";
    }
    
    echo "<hr>";
    echo "<p class='success'><strong>🎉 Remplissage terminé !</strong></p>";
    echo "<p><a href='api/portfolio.php' style='color: #3b82f6;'>Tester l'API Portfolio</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</body></html>";
?>

