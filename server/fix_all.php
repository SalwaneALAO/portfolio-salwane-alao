<?php
/**
 * Script COMPLET pour corriger TOUT d'un coup
 * Exécutez ce fichier pour tout réparer
 * Accès : http://localhost/Portfolio/server/fix_all.php
 */

require_once __DIR__ . '/config/database.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Correction complète</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #1e293b; color: #fff; }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .info { color: #3b82f6; }
        .warning { color: #f59e0b; }
    </style>
</head>
<body>
    <h1>🔧 Correction complète de la base de données</h1>
    <hr>";

try {
    $pdo = getDBConnection();
    echo "<p class='success'>✅ Connexion réussie !</p>";
    
    // 0. Créer la table qualities si elle n'existe pas (erreur observée : 'portfolio_db.qualities' doesn't exist)
    echo "<h2>0. Vérification des tables</h2>";
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS qualities (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            icon VARCHAR(10),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        echo "<p class='success'>✅ Table qualities vérifiée/créée</p>";
    } catch (PDOException $e) {
        echo "<p class='error'>❌ Impossible de créer/verifier qualities : " . $e->getMessage() . "</p>";
    }
    // Créer la table documents si elle n'existe pas (par sécurité)
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS documents (
            id INT PRIMARY KEY AUTO_INCREMENT,
            type ENUM('cv','diploma','certification') NOT NULL,
            title VARCHAR(255) NOT NULL,
            file_path VARCHAR(500),
            file_url VARCHAR(500),
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        echo "<p class='success'>✅ Table documents vérifiée/créée</p>";
    } catch (PDOException $e) {
        echo "<p class='warning'>⚠️ Table documents : " . $e->getMessage() . "</p>";
    }
    
    // 1. Ajouter toutes les colonnes manquantes
    echo "<h2>1. Ajout des colonnes manquantes</h2>";
    
    $columns = [
        ['languages', 'toeic_url', 'VARCHAR(500)', 'flag_emoji'],
        ['hero', 'profile_picture', 'VARCHAR(500)', 'description'],
        ['skills', 'logo_url', 'VARCHAR(500)', 'category']
    ];
    
    foreach ($columns as $col) {
        try {
            $pdo->exec("ALTER TABLE {$col[0]} ADD COLUMN {$col[1]} {$col[2]} AFTER {$col[3]}");
            echo "<p class='success'>✅ Colonne {$col[1]} ajoutée à {$col[0]}</p>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column') !== false) {
                echo "<p class='info'>ℹ️ Colonne {$col[1]} existe déjà dans {$col[0]}</p>";
            } else {
                echo "<p class='warning'>⚠️ {$col[0]}.{$col[1]} : " . $e->getMessage() . "</p>";
            }
        }
    }
    
    // 2. Mettre à jour le lien TOEIC
    echo "<h2>2. Mise à jour du lien TOEIC</h2>";
    $toeicUrl = 'https://www.etsglobal.org/fr/en/digital-score-report/F52F1F6398C5E176AC5C315AB1EF063A5F2568AA85AD8C6281F8971C0D62A500TUFqajdlTVBTLzZGdmpqZGhtZEx2RkM0Vy9VQmkyWkVoYWQrMGlkY2kyVUFGUjZX';
    $stmt = $pdo->prepare("UPDATE languages SET toeic_url = ? WHERE name = 'Anglais'");
    $stmt->execute([$toeicUrl]);
    $affected = $stmt->rowCount();
    echo "<p class='success'>✅ Lien TOEIC mis à jour ($affected ligne(s))</p>";
    
    // 3. Mettre à jour profile_picture
    echo "<h2>3. Mise à jour de la photo de profil</h2>";
    $stmt = $pdo->prepare("UPDATE hero SET profile_picture = '/images/profile-picture.jpg' WHERE profile_picture IS NULL OR profile_picture = ''");
    $stmt->execute();
    $affected = $stmt->rowCount();
    echo "<p class='success'>✅ Photo de profil configurée ($affected ligne(s))</p>";
    
    // 4. Ajouter TOUTES les compétences manquantes
    echo "<h2>4. Ajout des compétences manquantes</h2>";
    
    $allSkills = [
        // Langages
        ['Python', 90, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg'],
        ['SQL', 85, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg'],
        ['R', 75, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/r/r-original.svg'],
        ['DAX', 80, 'Langages', 'https://powerbi.microsoft.com/pictures/application-logos/svg/powerbi.svg'],
        ['Java', 70, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg'],
        ['HTML', 75, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg'],
        ['CSS', 75, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg'],
        ['C', 70, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/c/c-original.svg'],
        // Visualisation
        ['Tableau', 88, 'Visualisation', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tableau/tableau-original.svg'],
        ['Power BI', 92, 'Visualisation', 'https://powerbi.microsoft.com/pictures/application-logos/svg/powerbi.svg'],
        ['Excel', 95, 'Visualisation', 'https://upload.wikimedia.org/wikipedia/commons/3/34/Microsoft_Office_Excel_%282019%E2%80%93present%29.svg'],
        ['QGIS', 75, 'Visualisation', 'https://qgis.org/en/_static/images/logo.png'],
        ['Arcgis Pro', 70, 'Visualisation', 'https://www.esri.com/content/dam/esrisites/en-us/arcgis/products/arcgis-pro/overview/arcgis-pro-logo.png'],
        ['Looker', 75, 'Visualisation', 'https://www.gstatic.com/images/branding/product/1x/looker_48dp.png'],
        // Analyse
        ['Machine Learning', 75, 'Analyse', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tensorflow/tensorflow-original.svg'],
        ['Statistiques', 85, 'Analyse', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/r/r-original.svg'],
        // Outils
        ['Pandas', 90, 'Outils', 'https://pandas.pydata.org/static/img/pandas_mark.svg'],
        ['NumPy', 85, 'Outils', 'https://numpy.org/images/logo.svg'],
        ['Scikit-learn', 80, 'Outils', 'https://scikit-learn.org/stable/_static/scikit-learn-logo-small.png'],
        ['Jupyter Notebook', 90, 'Outils', 'https://jupyter.org/assets/homepage/main-logo.svg'],
        ['GitLab', 75, 'Outils', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/gitlab/gitlab-original.svg'],
        ['Visual Studio', 80, 'Outils', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/visualstudio/visualstudio-plain.svg'],
        ['Hadoop', 70, 'Outils', 'https://hadoop.apache.org/hadoop-logo.jpg'],
        ['Matplotlib', 80, 'Outils', 'https://matplotlib.org/stable/_static/logo2_compressed.svg'],
        ['Seaborn', 75, 'Outils', 'https://seaborn.pydata.org/_static/logo-wide-lightbg.svg'],
        ['Pack Office', 85, 'Outils', 'https://upload.wikimedia.org/wikipedia/commons/5/5f/Microsoft_Office_logo_%282019%E2%80%93present%29.svg'],
        // SGBD
        ['Oracle', 80, 'SGBD', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/oracle/oracle-original.svg'],
        ['MySQL', 85, 'SGBD', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg'],
        ['MySQL Server', 85, 'SGBD', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg'],
        ['MongoDB', 70, 'SGBD', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mongodb/mongodb-original.svg'],
        ['phpMyAdmin', 85, 'SGBD', 'https://www.phpmyadmin.net/static/images/logo-right.png']
    ];
    
    $added = 0;
    $updated = 0;
    foreach ($allSkills as $skill) {
        $stmt = $pdo->prepare("SELECT id, logo_url FROM skills WHERE name = ?");
        $stmt->execute([$skill[0]]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Mettre à jour si le logo est manquant
            if (empty($existing['logo_url'])) {
                $stmt = $pdo->prepare("UPDATE skills SET level = ?, category = ?, logo_url = ? WHERE id = ?");
                $stmt->execute([$skill[1], $skill[2], $skill[3], $existing['id']]);
                $updated++;
                echo "<p class='info'>🔄 Compétence mise à jour : {$skill[0]}</p>";
            }
        } else {
            // Insérer si n'existe pas
            try {
                $stmt = $pdo->prepare("INSERT INTO skills (name, level, category, logo_url) VALUES (?, ?, ?, ?)");
                $stmt->execute($skill);
                $added++;
                echo "<p class='success'>✅ Compétence ajoutée : {$skill[0]}</p>";
            } catch (PDOException $e) {
                echo "<p class='error'>❌ Erreur avec {$skill[0]} : " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "<p class='success'>✅ {$added} compétence(s) ajoutée(s), {$updated} mise(s) à jour</p>";
    
    // 5. Résumé final
    echo "<hr><h2>📊 Résumé final</h2>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM skills");
    $totalSkills = $stmt->fetch()['count'];
    echo "<p class='success'>✅ Total compétences : {$totalSkills}</p>";
    
    $stmt = $pdo->query("SELECT category, COUNT(*) as count FROM skills GROUP BY category");
    $categories = $stmt->fetchAll();
    foreach ($categories as $cat) {
        echo "<p class='info'>- {$cat['category']} : {$cat['count']} compétence(s)</p>";
    }
    
    // Vérifier le lien TOEIC
    $stmt = $pdo->query("SELECT name, toeic_url FROM languages WHERE name = 'Anglais'");
    $english = $stmt->fetch();
    if ($english && !empty($english['toeic_url'])) {
        echo "<p class='success'>✅ Lien TOEIC configuré pour l'Anglais</p>";
    } else {
        echo "<p class='error'>❌ Lien TOEIC manquant pour l'Anglais</p>";
    }
    
    // Vérifier la photo
    $stmt = $pdo->query("SELECT name, profile_picture FROM hero LIMIT 1");
    $hero = $stmt->fetch();
    if ($hero && !empty($hero['profile_picture'])) {
        echo "<p class='success'>✅ Photo de profil configurée : {$hero['profile_picture']}</p>";
        echo "<p class='info'>ℹ️ Placez votre photo dans : client/public/images/profile-picture.jpg</p>";
    } else {
        echo "<p class='error'>❌ Photo de profil non configurée</p>";
    }
    
    // 6. FORCER la mise à jour de toutes les compétences existantes avec leurs logos
    echo "<h2>6. Mise à jour des logos pour toutes les compétences</h2>";
    
    $logosMap = [
        'Python' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg',
        'SQL' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg',
        'R' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/r/r-original.svg',
        'DAX' => 'https://powerbi.microsoft.com/pictures/application-logos/svg/powerbi.svg',
        'Java' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg',
        'HTML' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg',
        'CSS' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg',
        'C' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/c/c-original.svg',
        'Tableau' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tableau/tableau-original.svg',
        'Power BI' => 'https://powerbi.microsoft.com/pictures/application-logos/svg/powerbi.svg',
        'Excel' => 'https://upload.wikimedia.org/wikipedia/commons/3/34/Microsoft_Office_Excel_%282019%E2%80%93present%29.svg',
        'QGIS' => 'https://qgis.org/en/_static/images/logo.png',
        'Arcgis Pro' => 'https://www.esri.com/content/dam/esrisites/en-us/arcgis/products/arcgis-pro/overview/arcgis-pro-logo.png',
        'Looker' => 'https://www.gstatic.com/images/branding/product/1x/looker_48dp.png',
        'Machine Learning' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tensorflow/tensorflow-original.svg',
        'Statistiques' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/r/r-original.svg',
        'Pandas' => 'https://pandas.pydata.org/static/img/pandas_mark.svg',
        'NumPy' => 'https://numpy.org/images/logo.svg',
        'Scikit-learn' => 'https://scikit-learn.org/stable/_static/scikit-learn-logo-small.png',
        'Jupyter Notebook' => 'https://jupyter.org/assets/homepage/main-logo.svg',
        'GitLab' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/gitlab/gitlab-original.svg',
        'Visual Studio' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/visualstudio/visualstudio-plain.svg',
        'Hadoop' => 'https://hadoop.apache.org/hadoop-logo.jpg',
        'Matplotlib' => 'https://matplotlib.org/stable/_static/logo2_compressed.svg',
        'Seaborn' => 'https://seaborn.pydata.org/_static/logo-wide-lightbg.svg',
        'Pack Office' => 'https://upload.wikimedia.org/wikipedia/commons/5/5f/Microsoft_Office_logo_%282019%E2%80%93present%29.svg',
        'Oracle' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/oracle/oracle-original.svg',
        'MySQL' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg',
        'MySQL Server' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg',
        'MongoDB' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mongodb/mongodb-original.svg',
        'phpMyAdmin' => 'https://www.phpmyadmin.net/static/images/logo-right.png'
    ];
    
    $updatedLogos = 0;
    foreach ($logosMap as $name => $logo) {
        $stmt = $pdo->prepare("UPDATE skills SET logo_url = ? WHERE name = ?");
        $stmt->execute([$logo, $name]);
        if ($stmt->rowCount() > 0) {
            $updatedLogos++;
        }
    }
    echo "<p class='success'>✅ {$updatedLogos} logo(s) mis à jour</p>";
    
    echo "<hr>";
    echo "<p class='success'><strong>🎉 Correction terminée !</strong></p>";
    echo "<p><strong>⚠️ IMPORTANT :</strong></p>";
    echo "<ol>";
    echo "<li>Videz le cache du navigateur (Ctrl+F5)</li>";
    echo "<li>Vérifiez l'API : <a href='api/portfolio.php' style='color: #3b82f6;'>🔗 Tester l'API</a></li>";
    echo "<li>Redémarrez Next.js si nécessaire</li>";
    echo "</ol>";
    echo "<p><a href='diagnostic.php' style='color: #3b82f6; text-decoration: underline;'>🔍 Vérifier avec le diagnostic</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre style='background: #0f172a; padding: 15px; border-radius: 5px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
?>

