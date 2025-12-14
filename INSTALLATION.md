# 📦 Guide d'Installation - Portfolio Data Analyst

## Étape 1 : Prérequis

Assurez-vous d'avoir :
- ✅ WAMP/XAMPP installé et démarré
- ✅ PHP 7.4+ avec extension PDO MySQL
- ✅ Node.js et npm installés (pour le frontend)

## Étape 2 : Base de Données

### Option A : Via phpMyAdmin (Recommandé)

1. Ouvrez phpMyAdmin : `http://localhost/phpmyadmin`
2. Cliquez sur "Importer" dans le menu supérieur
3. Sélectionnez le fichier `server/database.sql`
4. Cliquez sur "Exécuter"
5. Vérifiez que la base `portfolio_db` a été créée avec les tables

### Option B : Via ligne de commande MySQL

```bash
mysql -u root -proot < server/database.sql
```

## Étape 3 : Configuration

### Vérifier la configuration de la base de données

Ouvrez `server/config/database.php` et vérifiez :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio_db');
define('DB_USER', 'root');
define('DB_PASS', 'root');  // Votre mot de passe MySQL
```

Si votre mot de passe MySQL est différent de "root", modifiez `DB_PASS`.

## Étape 4 : Installation des dépendances Frontend

```bash
cd client
npm install
```

## Étape 5 : Tester le Backend PHP

1. Assurez-vous que WAMP est démarré
2. Ouvrez dans votre navigateur :
   - `http://localhost/Portfolio/server/api/portfolio.php`

Vous devriez voir du JSON avec toutes les données du portfolio.

## Étape 6 : Démarrer le Frontend

```bash
cd client
npm run dev
```

Le site sera accessible sur : `http://localhost:3000`

## Étape 7 : Personnaliser vos Données

### Via phpMyAdmin

1. Connectez-vous à phpMyAdmin
2. Sélectionnez la base `portfolio_db`
3. Modifiez les tables selon vos besoins :
   - **hero** : Votre nom, titre, description
   - **story** : Votre parcours professionnel
   - **skills** : Vos compétences
   - **projects** : Vos projets
   - **stats** : Vos statistiques

### Exemple : Modifier votre nom

```sql
UPDATE hero SET name = 'SALWANE ALAO' WHERE id = 1;
```

## ✅ Vérification

1. ✅ Base de données créée : `portfolio_db`
2. ✅ Tables créées : hero, story, skills, projects, stats
3. ✅ Données initiales insérées
4. ✅ API PHP accessible : `http://localhost/Portfolio/server/api/portfolio.php`
5. ✅ Frontend démarré : `http://localhost:3000`

## 🐛 Dépannage

### Erreur : "Erreur de connexion à la base de données"

- Vérifiez que MySQL est démarré dans WAMP
- Vérifiez les identifiants dans `server/config/database.php`
- Vérifiez que la base `portfolio_db` existe

### Erreur : "404 Not Found" sur les API

- Vérifiez le chemin : `http://localhost/Portfolio/server/api/portfolio.php`
- Vérifiez que le fichier `.htaccess` existe dans `server/`
- Vérifiez que `mod_rewrite` est activé dans Apache

### Le frontend ne charge pas les données

- Vérifiez que le backend PHP fonctionne (testez l'URL directement)
- Vérifiez la configuration dans `client/next.config.js`
- Vérifiez la console du navigateur pour les erreurs

## 🎉 C'est prêt !

Votre portfolio est maintenant opérationnel. Personnalisez vos données dans la base de données et profitez de votre site !


