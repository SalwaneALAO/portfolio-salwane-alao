-- Création de la base de données
CREATE DATABASE IF NOT EXISTS portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE portfolio_db;

-- Table Hero
CREATE TABLE IF NOT EXISTS hero (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255) NOT NULL,
    description TEXT,
    profile_picture VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Qualities
CREATE TABLE IF NOT EXISTS qualities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    icon VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Story (Timeline)
CREATE TABLE IF NOT EXISTS story (
    id INT PRIMARY KEY AUTO_INCREMENT,
    year VARCHAR(10) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(500) DEFAULT '📊',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Skills
CREATE TABLE IF NOT EXISTS skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    level INT NOT NULL CHECK (level >= 0 AND level <= 100),
    category VARCHAR(100) NOT NULL,
    logo_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Languages
CREATE TABLE IF NOT EXISTS languages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    level VARCHAR(50) NOT NULL,
    flag_emoji VARCHAR(10),
    toeic_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Documents
CREATE TABLE IF NOT EXISTS documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('cv', 'diploma', 'certification') NOT NULL,
    title VARCHAR(255) NOT NULL,
    file_path VARCHAR(500),
    file_url VARCHAR(500),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Projects
CREATE TABLE IF NOT EXISTS projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    technologies JSON,
    image VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Stats
CREATE TABLE IF NOT EXISTS stats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    label VARCHAR(255) NOT NULL,
    value INT NOT NULL,
    icon VARCHAR(10) DEFAULT '📊',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des données initiales
INSERT INTO hero (name, title, subtitle, description, profile_picture) VALUES
('SALWANE ALAO', 'Data Analyst & Data Scientist', 'Data Visualisation & Big Data | En recherche active d\'un CDI ou CDD', 
 'Passionné par l\'analyse de données et la visualisation, je transforme les informations brutes en décisions éclairées. Alternant chez GRDF depuis 2023, j\'ai contribué à améliorer la fiabilité des données de 25% et à accélérer la prise de décision stratégique. Spécialisé en Big Data, Machine Learning Operations (MLOps) et visualisation de données.',
 '/images/profile-picture.jpg');

INSERT INTO story (year, title, description, icon, display_order) VALUES
('2020', 'Baccalauréat Scientifique', 'Obtention du Baccalauréat Scientifique à Cotonou, Bénin. Première approche avec les mathématiques, physique et informatique.', '/uploads/logo-bac-benin.svg', 1),
('2020-2022', 'CPPA Père Aupiais', 'Formation en Mathématiques, Physique et Informatique. Développement des compétences en systèmes d\'exploitation et adaptation.', '/uploads/logo-bac-benin.svg', 2),
('2021', 'Stage - Ministère du Travail', 'Stage au Ministère du Travail et de la Fonction Publique au Bénin. Gestion du parc informatique, assistance technique et maintenance. Réduction de 30% des incidents techniques.', '/uploads/logo-ministere-travail-benin.svg', 3),
('2022-2025', 'ESIGELEC - BIG DATA', 'Formation d\'ingénieur en Génie Électrique spécialité BIG DATA à Rouen, France. Apprentissage du Machine Learning Operations (MLOps), Microsoft Dynamics et 62 compétences techniques.', '/uploads/logo-esigelec.svg', 4),
('2023-2025', 'Alternance GRDF', 'Alternance de 2 ans chez GRDF (Gaz Réseau Distribution France) à Rouen. Data Analyst/Data Scientist/BI Analyst. Centralisation des données, développement d\'outils BI, amélioration de la fiabilité des données de 25%.', '/uploads/logo-grdf.svg', 5),
('2024', 'Mission Internationale - BOKU University', 'Mission à BOKU University à St. Pölten, Autriche. Data Analyst pour le monitoring des émissions de gaz à effet de serre. Analyse environnementale identifiant une réduction potentielle de 12% des émissions.', '/uploads/logo-boku.svg', 6),
('2024', 'Certification TOEIC', 'Obtention du TOEIC (Test of English for International Communication) - Niveau B2 Professionnel. Certification valide jusqu\'en décembre 2026.', '/uploads/logo-toeic.svg', 7),
('2025', 'Aujourd\'hui', 'En recherche active d\'un CDI ou CDD en tant que Data Analyst, Data Engineer, Analyste gestion de données ou Analyste qualité des données. Continuant à évoluer et transformer les données en valeur.', '/uploads/logo-today.svg', 8);

INSERT INTO skills (name, level, category, logo_url) VALUES
-- Langages
('Python', 90, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg'),
('SQL', 85, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg'),
('R', 75, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/r/r-original.svg'),
('DAX', 80, 'Langages', 'https://powerbi.microsoft.com/pictures/application-logos/svg/powerbi.svg'),
('Java', 70, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg'),
('HTML', 75, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg'),
('CSS', 75, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg'),
('C', 70, 'Langages', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/c/c-original.svg'),
-- Visualisation
('Tableau', 88, 'Visualisation', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tableau/tableau-original.svg'),
('Power BI', 92, 'Visualisation', 'https://powerbi.microsoft.com/pictures/application-logos/svg/powerbi.svg'),
('Excel', 95, 'Visualisation', 'https://upload.wikimedia.org/wikipedia/commons/3/34/Microsoft_Office_Excel_%282019%E2%80%93present%29.svg'),
('QGIS', 75, 'Visualisation', 'https://qgis.org/en/_static/images/logo.png'),
('Arcgis Pro', 70, 'Visualisation', 'https://www.esri.com/content/dam/esrisites/en-us/arcgis/products/arcgis-pro/overview/arcgis-pro-logo.png'),
('Looker', 75, 'Visualisation', 'https://www.gstatic.com/images/branding/product/1x/looker_48dp.png'),
-- Analyse
('Machine Learning', 75, 'Analyse', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tensorflow/tensorflow-original.svg'),
('Statistiques', 85, 'Analyse', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/r/r-original.svg'),
-- Outils
('Pandas', 90, 'Outils', 'https://pandas.pydata.org/static/img/pandas_mark.svg'),
('NumPy', 85, 'Outils', 'https://numpy.org/images/logo.svg'),
('Scikit-learn', 80, 'Outils', 'https://scikit-learn.org/stable/_static/scikit-learn-logo-small.png'),
('Jupyter Notebook', 90, 'Outils', 'https://jupyter.org/assets/homepage/main-logo.svg'),
('GitLab', 75, 'Outils', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/gitlab/gitlab-original.svg'),
('Visual Studio', 80, 'Outils', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/visualstudio/visualstudio-plain.svg'),
('Hadoop', 70, 'Outils', 'https://hadoop.apache.org/hadoop-logo.jpg'),
('Matplotlib', 80, 'Outils', 'https://matplotlib.org/stable/_static/logo2_compressed.svg'),
('Seaborn', 75, 'Outils', 'https://seaborn.pydata.org/_static/logo-wide-lightbg.svg'),
('Pack Office', 85, 'Outils', 'https://upload.wikimedia.org/wikipedia/commons/5/5f/Microsoft_Office_logo_%282019%E2%80%93present%29.svg'),
-- SGBD
('Oracle', 80, 'SGBD', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/oracle/oracle-original.svg'),
('MySQL', 85, 'SGBD', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg'),
('MySQL Server', 85, 'SGBD', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg'),
('MongoDB', 70, 'SGBD', 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mongodb/mongodb-original.svg'),
('phpMyAdmin', 85, 'SGBD', 'https://www.phpmyadmin.net/static/images/logo-right.png');

INSERT INTO projects (title, description, technologies, image) VALUES
('Datawarehouse GRDF - Prévisions 5 ans', 
 'Conception et développement d\'un Datawarehouse intégrant des données historiques pour des prévisions sur 5 ans. Amélioration de la fiabilité des données de 25%.',
 JSON_ARRAY('Python', 'Power BI', 'SQL', 'Power Query'),
 '/uploads/logo-grdf.svg'),
('Analyse des émissions de GES - BOKU University', 
 'Monitoring des émissions de gaz à effet de serre avec systèmes automatisés. Analyse environnementale identifiant une réduction potentielle de 12% des émissions.',
 JSON_ARRAY('Python', 'SQL', 'Visualisation'),
 '/uploads/logo-boku.svg'),
('Automatisation BI - GRDF', 
 'Développement d\'outils Python pour l\'extraction et le traitement de données. Scripts intégrés à Power BI et flux Power Automate.',
 JSON_ARRAY('Python', 'Power BI', 'Power Automate', 'SharePoint'),
 '/uploads/logo-grdf.svg'),
('Optimisation Parc Informatique', 
 'Optimisation du parc informatique et réduction de 30% des incidents techniques grâce à un accès sécurisé et une maintenance proactive.',
 JSON_ARRAY('Gestion IT', 'Sécurité', 'Maintenance'),
 '/uploads/logo-ministere-travail-benin.svg');

INSERT INTO languages (name, level, flag_emoji, toeic_url) VALUES
('Français', 'Langue maternelle', '🇫🇷', NULL),
('Anglais', 'B2 - Professionnel (TOEIC)', '🇬🇧', 'https://www.etsglobal.org/fr/en/digital-score-report/F52F1F6398C5E176AC5C315AB1EF063A5F2568AA85AD8C6281F8971C0D62A500TUFqajdlTVBTLzZGdmpqZGhtZEx2RkM0Vy9VQmkyWkVoYWQrMGlkY2kyVUFGUjZX'),
('Espagnol', 'Intermédiaire', '🇪🇸', NULL);

INSERT INTO stats (label, value, icon) VALUES
('Projets Réalisés', 4, '📊'),
('Années d\'Expérience', 4, '⏱️'),
('Entreprises', 3, '🏢'),
('Certifications', 1, '🎓');

INSERT INTO qualities (name, icon) VALUES
('Esprit coopératif', '🤝'),
('Autonome', '🧭'),
('Dynamique', '⚡'),
('Analyse stratégique', '📈');

INSERT INTO documents (type, title, file_path, file_url, description) VALUES
('cv', 'CV - SALWANE ALAO', '/uploads/cv-salwane-alao.pdf', NULL, 'Curriculum Vitae complet - Data Analyst & Data Scientist'),
('diploma', 'Diplôme Ingénieur ESIGELEC', '/uploads/diplome-esigelec.pdf', NULL, 'Diplôme d\'Ingénieur en Génie Électrique - Spécialité BIG DATA (2022-2025)'),
('certification', 'Certification TOEIC', '/uploads/toeic-certification.pdf', NULL, 'TOEIC - Niveau B2 Professionnel (Déc. 2024 - Déc. 2026) - ID: 4829837618');

