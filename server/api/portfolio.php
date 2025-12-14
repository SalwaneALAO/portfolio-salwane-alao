<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/database.php';

try {
    $pdo = getDBConnection();
    
    // Récupérer toutes les données du portfolio
    $portfolio = [];
    
    // Hero
    $stmt = $pdo->query("SELECT * FROM hero LIMIT 1");
    $hero = $stmt->fetch();
    $portfolio['hero'] = $hero ?: [
        'name' => 'SALWANE ALAO',
        'title' => 'Data Analyst & Data Scientist',
        'subtitle' => 'Data Visualisation & Big Data | En recherche active d\'un CDI ou CDD',
        'description' => 'Passionné par l\'analyse de données et la visualisation, je transforme les informations brutes en décisions éclairées. Alternant chez GRDF depuis 2023, j\'ai contribué à améliorer la fiabilité des données de 25% et à accélérer la prise de décision stratégique.',
        'profile_picture' => '/images/profile-picture.png'
    ];
    
    // Story
    $stmt = $pdo->query("SELECT * FROM story ORDER BY display_order ASC, year ASC");
    $portfolio['story'] = $stmt->fetchAll() ?: [];
    
    // Skills
    $stmt = $pdo->query("SELECT * FROM skills ORDER BY category, name");
    $portfolio['skills'] = $stmt->fetchAll() ?: [];
    
    // Projects
    $stmt = $pdo->query("SELECT * FROM projects ORDER BY id");
    $portfolio['projects'] = $stmt->fetchAll() ?: [];
    
    // Stats
    $stmt = $pdo->query("SELECT * FROM stats ORDER BY id");
    $portfolio['stats'] = $stmt->fetchAll() ?: [];
    
    // Languages
    $stmt = $pdo->query("SELECT * FROM languages ORDER BY id");
    $portfolio['languages'] = $stmt->fetchAll() ?: [];
    
    // S'assurer que toeic_url est inclus même si la colonne n'existe pas encore
    foreach ($portfolio['languages'] as &$lang) {
        if (!isset($lang['toeic_url'])) {
            $lang['toeic_url'] = null;
        }
    }
    
    // Documents
    $stmt = $pdo->query("SELECT * FROM documents ORDER BY type, id");
    $portfolio['documents'] = $stmt->fetchAll() ?: [];
    
    // Qualities
    $stmt = $pdo->query("SELECT * FROM qualities ORDER BY id");
    $portfolio['qualities'] = $stmt->fetchAll() ?: [];
    
    // Convertir les technologies en tableau pour les projets
    foreach ($portfolio['projects'] as &$project) {
        if (isset($project['technologies']) && is_string($project['technologies'])) {
            $project['technologies'] = json_decode($project['technologies'], true) ?: [];
        }
    }
    
    echo json_encode($portfolio, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    // En cas d'erreur, retourner une structure valide avec des données par défaut
    http_response_code(200);
    
    // Données par défaut complètes
    $defaultData = [
        'hero' => [
            'name' => 'SALWANE ALAO',
            'title' => 'Data Analyst & Data Scientist',
            'subtitle' => 'Data Visualisation & Big Data | En recherche active d\'un CDI ou CDD',
            'description' => 'Passionné par l\'analyse de données et la visualisation, je transforme les informations brutes en décisions éclairées. Alternant chez GRDF depuis 2023, j\'ai contribué à améliorer la fiabilité des données de 25% et à accélérer la prise de décision stratégique.',
            'profile_picture' => '/images/profile-picture.jpg'
        ],
        'story' => [
            ['id' => 1, 'year' => '2020', 'title' => 'Baccalauréat Scientifique', 'description' => 'Obtention du Baccalauréat Scientifique à Cotonou, Bénin.', 'icon' => '🎓', 'display_order' => 1],
            ['id' => 2, 'year' => '2022-2025', 'title' => 'ESIGELEC - BIG DATA', 'description' => 'Formation d\'ingénieur en Génie Électrique spécialité BIG DATA.', 'icon' => '🚀', 'display_order' => 2],
            ['id' => 3, 'year' => '2023-2025', 'title' => 'Alternance GRDF', 'description' => 'Data Analyst/Data Scientist/BI Analyst chez GRDF.', 'icon' => '⚡', 'display_order' => 3]
        ],
        'skills' => [
            ['id' => 1, 'name' => 'Python', 'level' => 90, 'category' => 'Langages', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg'],
            ['id' => 2, 'name' => 'SQL', 'level' => 85, 'category' => 'Langages', 'logo_url' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg'],
            ['id' => 3, 'name' => 'Power BI', 'level' => 92, 'category' => 'Visualisation', 'logo_url' => 'https://powerbi.microsoft.com/pictures/application-logos/svg/powerbi.svg']
        ],
        'projects' => [
            ['id' => 1, 'title' => 'Datawarehouse GRDF', 'description' => 'Conception et développement d\'un Datawarehouse intégrant des données historiques.', 'technologies' => ['Python', 'Power BI', 'SQL'], 'image' => '/api/placeholder/600/400']
        ],
        'stats' => [
            ['id' => 1, 'label' => 'Projets Réalisés', 'value' => 4, 'icon' => '📊'],
            ['id' => 2, 'label' => 'Années d\'Expérience', 'value' => 4, 'icon' => '⏱️'],
            ['id' => 3, 'label' => 'Entreprises', 'value' => 3, 'icon' => '🏢'],
            ['id' => 4, 'label' => 'Certifications', 'value' => 1, 'icon' => '🎓']
        ],
        'languages' => [
            ['id' => 1, 'name' => 'Français', 'level' => 'Langue maternelle', 'flag_emoji' => '🇫🇷'],
            ['id' => 2, 'name' => 'Anglais', 'level' => 'B2 - Professionnel (TOEIC)', 'flag_emoji' => '🇬🇧'],
            ['id' => 3, 'name' => 'Espagnol', 'level' => 'Intermédiaire', 'flag_emoji' => '🇪🇸']
        ],
        'qualities' => [
            ['id' => 1, 'name' => 'Esprit coopératif', 'icon' => '🤝'],
            ['id' => 2, 'name' => 'Autonome', 'icon' => '🎯'],
            ['id' => 3, 'name' => 'Dynamique', 'icon' => '⚡'],
            ['id' => 4, 'name' => 'Analyse stratégique', 'icon' => '🧠']
        ],
        'documents' => [],
        'error' => $e->getMessage(),
        'warning' => 'Données par défaut - Vérifiez la connexion à la base de données'
    ];
    
    echo json_encode($defaultData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>

