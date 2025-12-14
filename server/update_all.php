<?php
/**
 * Script pour mettre à jour TOUTES les données dans la base de données existante
 * Exécutez ce fichier pour appliquer tous les changements
 * Accès : http://localhost/Portfolio/server/update_all.php
 */

require_once __DIR__ . '/config/database.php';

// #region agent log helper
function agent_log(array $payload): void {
    $path = __DIR__ . '/../.cursor/debug.log';
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $payload['timestamp'] = $payload['timestamp'] ?? round(microtime(true) * 1000);
    file_put_contents($path, json_encode($payload) . "\n", FILE_APPEND);
}
// #endregion

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Mise à jour complète</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #1e293b; color: #fff; }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .info { color: #3b82f6; }
    </style>
</head>
<body>
    <h1>🔄 Mise à jour complète de la base de données</h1>
    <hr>";

try {
    // #region agent log
    agent_log([
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'H4',
        'location' => 'server/update_all.php:28',
        'message' => 'Init before DB connection',
        'data' => ['cwd' => getcwd()]
    ]);
    // #endregion

    $pdo = getDBConnection();
    echo "<p class='success'>✅ Connexion réussie !</p>";
    // #region agent log
    file_put_contents(
        'c:\\wamp64\\www\\Portfolio\\.cursor\\debug.log',
        json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'H4',
            'location' => 'server/update_all.php:30',
            'message' => 'DB connection established',
            'data' => ['dsn' => $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS)],
            'timestamp' => round(microtime(true) * 1000)
        ]) . "\n",
        FILE_APPEND
    );
    // #endregion
    
    // 0. S'assurer que la table qualities existe et la peupler si vide
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS qualities (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            icon VARCHAR(10),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        // #region agent log
        agent_log([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'H1',
            'location' => 'server/update_all.php:71',
            'message' => 'Qualities table ensured',
            'data' => []
        ]);
        // #endregion
        
        $countStmt = $pdo->query("SELECT COUNT(*) as count FROM qualities");
        $qualitiesCount = $countStmt->fetch()['count'];
        if ($qualitiesCount == 0) {
            $pdo->exec("INSERT INTO qualities (name, icon) VALUES
                ('Esprit coopératif', '🤝'),
                ('Autonome', '🧭'),
                ('Dynamique', '⚡'),
                ('Analyse stratégique', '📈')
            ");
            echo "<p class='success'>✅ Table qualities créée et peuplée</p>";
            // #region agent log
            agent_log([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'H1',
                'location' => 'server/update_all.php:89',
                'message' => 'Qualities seeded',
                'data' => ['countInserted' => 4]
            ]);
            // #endregion
        } else {
            echo "<p class='info'>ℹ️ Table qualities déjà présente avec $qualitiesCount enregistrement(s)</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='error'>⚠️ Erreur lors de la création/peuplement de qualities : " . $e->getMessage() . "</p>";
        // #region agent log
        agent_log([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'H1',
            'location' => 'server/update_all.php:98',
            'message' => 'Qualities error',
            'data' => ['error' => $e->getMessage()]
        ]);
        // #endregion
    }
    
    // 1. Ajouter la colonne toeic_url si elle n'existe pas
    try {
        $pdo->exec("ALTER TABLE languages ADD COLUMN toeic_url VARCHAR(500) AFTER flag_emoji");
        echo "<p class='success'>✅ Colonne toeic_url ajoutée à la table languages</p>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "<p class='info'>ℹ️ Colonne toeic_url existe déjà</p>";
        } else {
            throw $e;
        }
    }
    
    // 2. Mettre à jour le lien TOEIC pour l'Anglais
    $toeicUrl = 'https://www.etsglobal.org/fr/en/digital-score-report/F52F1F6398C5E176AC5C315AB1EF063A5F2568AA85AD8C6281F8971C0D62A500TUFqajdlTVBTLzZGdmpqZGhtZEx2RkM0Vy9VQmkyWkVoYWQrMGlkY2kyVUFGUjZX';
    $stmt = $pdo->prepare("UPDATE languages SET toeic_url = ? WHERE name = 'Anglais'");
    $stmt->execute([$toeicUrl]);
    echo "<p class='success'>✅ Lien TOEIC mis à jour pour l'Anglais</p>";
    
    // 2bis. Peupler les langues si vide et garantir le lien TOEIC
    try {
        $langCountStmt = $pdo->query("SELECT COUNT(*) as count FROM languages");
        $langCount = (int)$langCountStmt->fetch()['count'];
        if ($langCount === 0) {
            $pdo->exec("INSERT INTO languages (name, level, flag_emoji, toeic_url) VALUES
                ('Français', 'Langue maternelle', '🇫🇷', NULL),
                ('Anglais', 'B2 - Professionnel (TOEIC)', '🇬🇧', '$toeicUrl'),
                ('Espagnol', 'Intermédiaire', '🇪🇸', NULL)
            ");
            echo "<p class='success'>✅ Langues insérées (Français, Anglais, Espagnol)</p>";
            // #region agent log
            agent_log([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'H3',
                'location' => 'server/update_all.php:149',
                'message' => 'Languages seeded',
                'data' => ['countInserted' => 3]
            ]);
            // #endregion
        } else {
            // Mettre à jour le TOEIC pour Anglais au besoin
            $stmt = $pdo->prepare("UPDATE languages SET toeic_url = ? WHERE name = 'Anglais'");
            $stmt->execute([$toeicUrl]);
            echo "<p class='info'>ℹ️ Langues déjà présentes ($langCount), TOEIC mis à jour</p>";
            // #region agent log
            agent_log([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'H3',
                'location' => 'server/update_all.php:163',
                'message' => 'Languages already present',
                'data' => ['count' => $langCount]
            ]);
            // #endregion
        }
    } catch (PDOException $e) {
        echo "<p class='error'>⚠️ Erreur lors du peuplement des langues : " . $e->getMessage() . "</p>";
        // #region agent log
        agent_log([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'H3',
            'location' => 'server/update_all.php:172',
            'message' => 'Languages error',
            'data' => ['error' => $e->getMessage()]
        ]);
        // #endregion
    }
    
    // 3. Vérifier et ajouter la colonne logo_url à la table skills
    try {
        $pdo->exec("ALTER TABLE skills ADD COLUMN logo_url VARCHAR(500) AFTER category");
        echo "<p class='success'>✅ Colonne logo_url ajoutée à la table skills</p>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "<p class='info'>ℹ️ Colonne logo_url existe déjà dans skills</p>";
        } else {
            echo "<p class='error'>⚠️ Erreur avec logo_url : " . $e->getMessage() . "</p>";
        }
    }
    
    // 4. Ajouter les compétences manquantes
    $newSkills = [
        ['Matplotlib', 80, 'Outils', 'https://matplotlib.org/stable/_static/logo2_compressed.svg'],
        ['Seaborn', 75, 'Outils', 'https://seaborn.pydata.org/_static/logo-wide-lightbg.svg'],
        ['Pack Office', 85, 'Outils', 'https://upload.wikimedia.org/wikipedia/commons/5/5f/Microsoft_Office_logo_%282019%E2%80%93present%29.svg'],
        ['MySQL Server', 85, 'SGBD', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg']
    ];
    
    $added = 0;
    foreach ($newSkills as $skill) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM skills WHERE name = ?");
        $stmt->execute([$skill[0]]);
        if ($stmt->fetch()['count'] == 0) {
            try {
                $stmt = $pdo->prepare("INSERT INTO skills (name, level, category, logo_url) VALUES (?, ?, ?, ?)");
                $stmt->execute($skill);
                $added++;
                echo "<p class='success'>✅ Compétence ajoutée : {$skill[0]}</p>";
            } catch (PDOException $e) {
                echo "<p class='error'>❌ Erreur lors de l'ajout de {$skill[0]} : " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p class='info'>ℹ️ Compétence existe déjà : {$skill[0]}</p>";
        }
    }
    
    // 4bis. Garantir un logo pour chaque compétence connue
    $skillLogos = [
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
    
    foreach ($skillLogos as $skillName => $logoUrl) {
        $stmt = $pdo->prepare("UPDATE skills SET logo_url = :logo WHERE (logo_url IS NULL OR logo_url = '') AND name = :name");
        $stmt->execute(['logo' => $logoUrl, 'name' => $skillName]);
    }
    // #region agent log
    agent_log([
        'sessionId' => 'debug-session',
        'runId' => 'run1',
        'hypothesisId' => 'H2',
        'location' => 'server/update_all.php:208',
        'message' => 'Skills logos enforced',
        'data' => ['skillsWithDefaultLogos' => count($skillLogos)]
    ]);
    // #endregion
    
    if ($added > 0) {
        echo "<p class='success'>✅ {$added} nouvelle(s) compétence(s) ajoutée(s)</p>";
    }
    
    // 5. Vérifier et mettre à jour le hero avec profile_picture
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM hero");
    if ($stmt->fetch()['count'] > 0) {
        // Vérifier si la colonne profile_picture existe
        try {
            $pdo->exec("ALTER TABLE hero ADD COLUMN profile_picture VARCHAR(500) AFTER description");
            echo "<p class='success'>✅ Colonne profile_picture ajoutée à la table hero</p>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column') !== false) {
                echo "<p class='info'>ℹ️ Colonne profile_picture existe déjà</p>";
            }
        }
        
        // Mettre à jour le profile_picture en PNG (force la valeur pour éviter les anciens jpg)
        $stmt = $pdo->prepare("UPDATE hero SET profile_picture = '/images/profile-picture.png'");
        $stmt->execute();
        echo "<p class='success'>✅ Profile picture mis à jour dans hero</p>";
    }
    
    // 6. Mettre à jour story pour n'avoir que 4 vignettes (Alternance GRDF, Mission BOKU, ESIGELEC, Baccalauréat)
    try {
        // Vérifier et agrandir la colonne icon si nécessaire
        try {
            $pdo->exec("ALTER TABLE story MODIFY COLUMN icon VARCHAR(500) DEFAULT '📊'");
            echo "<p class='success'>✅ Colonne icon de story agrandie à 500 caractères</p>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate') === false && strpos($e->getMessage(), 'does not exist') === false) {
                echo "<p class='info'>ℹ️ Colonne icon déjà à la bonne taille ou erreur : " . $e->getMessage() . "</p>";
            }
        }
        // Supprimer toutes les entrées story
        $pdo->exec("DELETE FROM story");
        // Insérer les 4 vignettes sélectionnées avec prepared statements pour éviter les problèmes d'échappement
        $stmt = $pdo->prepare("INSERT INTO story (year, title, description, icon, display_order) VALUES (?, ?, ?, ?, ?)");
        $stories = [
            ['2020', 'Baccalauréat Scientifique', 'Obtention du Baccalauréat Scientifique à Cotonou, Bénin. Première approche avec les mathématiques, physique et informatique.', '/uploads/logo-bac-benin.svg', 1],
            ['2022-2025', 'ESIGELEC - BIG DATA', 'Formation d\'ingénieur en Génie Électrique spécialité BIG DATA à Rouen, France. Apprentissage du Machine Learning Operations (MLOps), Microsoft Dynamics et 62 compétences techniques.', '/uploads/logo-esigelec.svg', 2],
            ['2023-2025', 'Alternance GRDF', 'Alternance de 2 ans chez GRDF (Gaz Réseau Distribution France) à Rouen. Data Analyst/Data Scientist/BI Analyst. Centralisation des données, développement d\'outils BI, amélioration de la fiabilité des données de 25%.', '/uploads/logo-grdf.svg', 3],
            ['2024', 'Mission Internationale - BOKU University', 'Mission à BOKU University à St. Pölten, Autriche. Data Analyst pour le monitoring des émissions de gaz à effet de serre. Analyse environnementale identifiant une réduction potentielle de 12% des émissions.', '/uploads/logo-boku.svg', 4]
        ];
        foreach ($stories as $story) {
            $stmt->execute($story);
        }
        echo "<p class='success'>✅ Story mise à jour avec 4 vignettes</p>";
        // #region agent log
        agent_log([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'H-story',
            'location' => 'server/update_all.php:story-insert',
            'message' => 'Story inserted',
            'data' => ['count' => count($stories)]
        ]);
        // #endregion
    } catch (PDOException $e) {
        echo "<p class='error'>⚠️ Erreur lors de la mise à jour de story : " . $e->getMessage() . "</p>";
        // #region agent log
        agent_log([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'H-story',
            'location' => 'server/update_all.php:story-error',
            'message' => 'Story insert error',
            'data' => ['error' => $e->getMessage()]
        ]);
        // #endregion
    }
    
    // 6bis. Mettre à jour les documents et certifications (8 certifications + 2 documents)
    try {
        // Supprimer les anciens documents
        $pdo->exec("DELETE FROM documents");
        // Insérer CV et Diplôme
        $pdo->exec("INSERT INTO documents (type, title, file_path, file_url, description) VALUES
            ('cv', 'CV - SALWANE ALAO', '/uploads/cv-salwane-alao.pdf', NULL, 'Curriculum Vitae complet - Data Analyst & Data Scientist'),
            ('diploma', 'Diplôme Ingénieur ESIGELEC', '/uploads/diplome-esigelec.pdf', NULL, 'Diplôme d\'Ingénieur en Génie Électrique - Spécialité BIG DATA (2022-2025)')
        ");
        // Insérer 8 certifications réelles (noms de fichiers correspondant aux fichiers réels)
        $stmt = $pdo->prepare("INSERT INTO documents (type, title, file_path, file_url, description) VALUES (?, ?, ?, ?, ?)");
        $certifications = [
            ['certification', 'TOEIC - Niveau B2 Professionnel', NULL, 'https://www.etsglobal.org/fr/en/digital-score-report/F52F1F6398C5E176AC5C315AB1EF063A5F2568AA85AD8C6281F8971C0D62A500TUFqajdlTVBTLzZGdmpqZGhtZEx2RkM0Vy9VQmkyWkVoYWQrMGlkY2kyVUFGUjZX', 'TOEIC - Niveau B2 Professionnel (Déc. 2024 - Déc. 2026) - ID: 4829837618'],
            ['certification', 'Udemy - Fondamentaux de l\'Analyse de Données & Machine Learning', '/uploads/cert-udemy-datascience-ml.pdf.jpeg', NULL, 'Formation complète sur l\'analyse de données et le Machine Learning - 12 heures (Jan. 2025)'],
            ['certification', 'LinkedIn Learning - Excel : Analyse, gestion et validation de données', '/uploads/cert-linkedin-excel.pdf.jpeg', NULL, 'Certification Excel - Analyse, gestion et validation de données (Août 2025)'],
            ['certification', 'Kaggle - Intro to Machine Learning', '/uploads/cert-kaggle-ml.pdf.jpeg', NULL, 'Introduction au Machine Learning - Kaggle Learn (Jan. 2025)'],
            ['certification', 'Udemy - Power BI : la formation complète', '/uploads/cert-udemy-powerbi.pdf.jpeg', NULL, 'Formation complète Microsoft Power BI de A à Z - 7 heures (Avr. 2023)'],
            ['certification', 'LinkedIn Learning - Découvrir Power BI avec ChatGPT', '/uploads/cert-linkedin-powerbi-chatgpt.pdf.jpeg', NULL, 'Power BI avec ChatGPT - Intelligence artificielle pour les entreprises (Août 2025)'],
            ['certification', 'Kaggle - Intro to Deep Learning', '/uploads/cert-kaggle-deeplearning.pdf.jpeg', NULL, 'Introduction au Deep Learning - Kaggle Learn (Jan. 2025)'],
            ['certification', 'GoSkills - Microsoft Excel 365 - Basic', '/uploads/cert-goskills-excel.pdf.jpeg', NULL, 'Microsoft Excel 365 - Basic - 12h30 (Août 2025) - CPD Certified'],
            ['certification', 'Google Analytics Academy - Google Analytics pour les débutants', '/uploads/cert-google-analytics.pdf.jpeg', NULL, 'Google Analytics pour les débutants - Certificat valide jusqu\'en février 2027']
        ];
        foreach ($certifications as $cert) {
            $stmt->execute($cert);
        }
        echo "<p class='success'>✅ Documents et certifications mis à jour (2 documents + 8 certifications)</p>";
    } catch (PDOException $e) {
        echo "<p class='error'>⚠️ Erreur lors de la mise à jour des documents : " . $e->getMessage() . "</p>";
    }

    // 6ter. Mettre à jour les logos projets avec les fichiers uploads
    try {
        $pdo->exec("UPDATE projects SET image = '/uploads/logo-grdf.svg' WHERE title LIKE '%GRDF%'");
        $pdo->exec("UPDATE projects SET image = '/uploads/logo-boku.svg' WHERE title LIKE '%BOKU%'");
        $pdo->exec("UPDATE projects SET image = '/uploads/logo-ministere-travail-benin.svg' WHERE title LIKE '%Parc Informatique%' OR title LIKE '%Minist%'");
        echo "<p class='success'>✅ Logos projets mis à jour (GRDF, BOKU, Ministère) -> .svg</p>";
    } catch (PDOException $e) {
        echo "<p class='error'>⚠️ Erreur lors de la mise à jour des logos projets : " . $e->getMessage() . "</p>";
    }
    
    // 6ter. Vérifier les stats
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM stats");
    if ($stmt->fetch()['count'] > 0) {
        // Mettre à jour les stats avec les vraies valeurs (8 certifications)
        $pdo->exec("UPDATE stats SET value = 4 WHERE label = 'Projets Réalisés'");
        $pdo->exec("UPDATE stats SET value = 3 WHERE label = 'Entreprises'");
        $pdo->exec("UPDATE stats SET value = 8 WHERE label = 'Certifications'");
        echo "<p class='success'>✅ Stats mises à jour (8 certifications)</p>";
    }
    
    // 7. Vérifier que toutes les compétences ont des logos
    $stmt = $pdo->query("SELECT name, logo_url FROM skills WHERE logo_url IS NULL OR logo_url = ''");
    $skillsWithoutLogo = $stmt->fetchAll();
    if (count($skillsWithoutLogo) > 0) {
        echo "<p class='info'>ℹ️ " . count($skillsWithoutLogo) . " compétence(s) sans logo détectée(s)</p>";
    }
    
    // 8. Afficher un résumé
    echo "<hr>";
    echo "<h2>📊 Résumé :</h2>";
    
    $tables = ['hero', 'story', 'skills', 'projects', 'stats', 'languages', 'documents', 'qualities'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $count = $stmt->fetch()['count'];
            echo "<p class='info'>📋 Table '$table' : $count enregistrement(s)</p>";
            // #region agent log
            file_put_contents(
                'c:\\wamp64\\www\\Portfolio\\.cursor\\debug.log',
                json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'H1',
                    'location' => "server/update_all.php:136",
                    'message' => 'Table count success',
                    'data' => ['table' => $table, 'count' => $count],
                    'timestamp' => round(microtime(true) * 1000)
                ]) . "\n",
                FILE_APPEND
            );
            // #endregion
        } catch (PDOException $e) {
            echo "<p class='error'>❌ Erreur avec '$table' : " . $e->getMessage() . "</p>";
            // #region agent log
            file_put_contents(
                'c:\\wamp64\\www\\Portfolio\\.cursor\\debug.log',
                json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'H1',
                    'location' => "server/update_all.php:139",
                    'message' => 'Table count error',
                    'data' => ['table' => $table, 'error' => $e->getMessage()],
                    'timestamp' => round(microtime(true) * 1000)
                ]) . "\n",
                FILE_APPEND
            );
            // #endregion
        }
    }
    
    // Compter les compétences par catégorie
    $stmt = $pdo->query("SELECT category, COUNT(*) as count FROM skills GROUP BY category");
    $categories = $stmt->fetchAll();
    echo "<h3>Compétences par catégorie :</h3>";
    foreach ($categories as $cat) {
        echo "<p class='info'>- {$cat['category']} : {$cat['count']} compétence(s)</p>";
        // #region agent log
        file_put_contents(
            'c:\\wamp64\\www\\Portfolio\\.cursor\\debug.log',
            json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'H2',
                'location' => "server/update_all.php:148",
                'message' => 'Skill category count',
                'data' => ['category' => $cat['category'], 'count' => $cat['count']],
                'timestamp' => round(microtime(true) * 1000)
            ]) . "\n",
            FILE_APPEND
        );
        // #endregion
    }
    
    echo "<hr>";
    echo "<p class='success'><strong>🎉 Mise à jour terminée !</strong></p>";
    echo "<p><a href='api/portfolio.php' style='color: #3b82f6; text-decoration: underline;'>🔗 Tester l'API Portfolio</a></p>";
    echo "<p><a href='check_skills.php' style='color: #3b82f6; text-decoration: underline;'>🔍 Vérifier les compétences</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre style='background: #0f172a; padding: 15px; border-radius: 5px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
?>

