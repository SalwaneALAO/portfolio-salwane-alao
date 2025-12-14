# Backend PHP - Portfolio Data Analyst

## 📋 Prérequis

- WAMP/XAMPP installé et démarré
- PHP 7.4 ou supérieur
- MySQL/MariaDB
- Extension PDO MySQL activée

## 🗄️ Configuration de la Base de Données

### 1. Créer la base de données

Ouvrez phpMyAdmin (`http://localhost/phpmyadmin`) et :

1. Importez le fichier `database.sql` :
   - Cliquez sur "Importer"
   - Sélectionnez le fichier `server/database.sql`
   - Cliquez sur "Exécuter"

OU exécutez manuellement le script SQL dans phpMyAdmin.

### 2. Vérifier la configuration

Le fichier `config/database.php` est configuré avec :
- **Host:** localhost
- **Database:** portfolio_db
- **User:** root
- **Password:** root

Si vos paramètres sont différents, modifiez `server/config/database.php`.

## 🚀 Utilisation

### Accès aux API

Les endpoints sont accessibles via :
- `http://localhost/Portfolio/server/api/portfolio.php`
- `http://localhost/Portfolio/server/api/story.php`
- `http://localhost/Portfolio/server/api/skills.php`
- `http://localhost/Portfolio/server/api/projects.php`
- `http://localhost/Portfolio/server/api/stats.php`

### Structure des fichiers

```
server/
├── api/
│   ├── portfolio.php    # Endpoint principal (toutes les données)
│   ├── story.php        # Timeline / Histoire
│   ├── skills.php       # Compétences
│   ├── projects.php     # Projets
│   └── stats.php        # Statistiques
├── config/
│   └── database.php     # Configuration BDD
├── database.sql         # Script de création BDD
└── .htaccess           # Configuration Apache
```

## 📝 Modification des Données

### Via phpMyAdmin

1. Connectez-vous à phpMyAdmin
2. Sélectionnez la base `portfolio_db`
3. Modifiez les tables directement :
   - `hero` : Informations principales
   - `story` : Timeline de votre parcours
   - `skills` : Compétences avec niveaux (0-100)
   - `projects` : Projets (technologies en JSON)
   - `stats` : Statistiques

### Via SQL

Exécutez des requêtes SQL directement dans phpMyAdmin :

```sql
-- Modifier le hero
UPDATE hero SET 
    name = 'Votre Nom',
    title = 'Votre Titre',
    subtitle = 'Votre Sous-titre',
    description = 'Votre Description'
WHERE id = 1;

-- Ajouter une étape dans la timeline
INSERT INTO story (year, title, description, icon, display_order) 
VALUES ('2025', 'Nouvelle Étape', 'Description...', '🎯', 7);

-- Modifier une compétence
UPDATE skills SET level = 95 WHERE name = 'Python';
```

## 🔧 Dépannage

### Erreur de connexion à la base de données

1. Vérifiez que MySQL est démarré dans WAMP
2. Vérifiez les identifiants dans `config/database.php`
3. Vérifiez que la base `portfolio_db` existe

### Erreur 404 sur les API

1. Vérifiez que le module `mod_rewrite` est activé dans Apache
2. Vérifiez le chemin dans `.htaccess`
3. Testez directement : `http://localhost/Portfolio/server/api/portfolio.php`

### Erreur CORS

Les headers CORS sont configurés dans chaque fichier API. Si vous avez des problèmes :
- Vérifiez que les headers sont bien envoyés
- Vérifiez la configuration Apache pour les headers

## 📚 Structure des Tables

### Table `hero`
- `id` (INT, PRIMARY KEY)
- `name` (VARCHAR)
- `title` (VARCHAR)
- `subtitle` (VARCHAR)
- `description` (TEXT)

### Table `story`
- `id` (INT, PRIMARY KEY)
- `year` (VARCHAR)
- `title` (VARCHAR)
- `description` (TEXT)
- `icon` (VARCHAR)
- `display_order` (INT)

### Table `skills`
- `id` (INT, PRIMARY KEY)
- `name` (VARCHAR)
- `level` (INT, 0-100)
- `category` (VARCHAR)

### Table `projects`
- `id` (INT, PRIMARY KEY)
- `title` (VARCHAR)
- `description` (TEXT)
- `technologies` (JSON)
- `image` (VARCHAR)

### Table `stats`
- `id` (INT, PRIMARY KEY)
- `label` (VARCHAR)
- `value` (INT)
- `icon` (VARCHAR)


