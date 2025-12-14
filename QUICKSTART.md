# 🚀 Guide de Démarrage Rapide

## Installation

1. **Créer la base de données :**
   - Ouvrez phpMyAdmin : `http://localhost/phpmyadmin`
   - Importez le fichier `server/database.sql`
   - Vérifiez que la base `portfolio_db` est créée

2. **Installer les dépendances frontend :**
   ```bash
   cd client
   npm install
   ```

## Démarrage

3. **Vérifier le backend PHP :**
   - Assurez-vous que WAMP est démarré
   - Testez : `http://localhost/Portfolio/server/test_connection.php`
   - Testez l'API : `http://localhost/Portfolio/server/api/portfolio.php`

4. **Lancer le frontend :**
   ```bash
   cd client
   npm run dev
   ```

5. **Ouvrir votre navigateur :**
   - Allez sur `http://localhost:3000`

## 🎨 Personnalisation

### Modifier vos informations personnelles

Les données sont dans la base de données MySQL. Modifiez-les via phpMyAdmin ou SQL :

**Via phpMyAdmin :**
1. Connectez-vous à `http://localhost/phpmyadmin`
2. Sélectionnez la base `portfolio_db`
3. Modifiez les tables directement

**Via SQL :**
```sql
-- Modifier votre nom
UPDATE hero SET name = 'SALWANE ALAO' WHERE id = 1;

-- Ajouter une étape dans votre histoire
INSERT INTO story (year, title, description, icon, display_order) 
VALUES ('2025', 'Nouvelle Étape', 'Description...', '🎯', 7);

-- Modifier une compétence
UPDATE skills SET level = 95 WHERE name = 'Python';
```

### Modifier les couleurs

Éditez `client/tailwind.config.js` pour changer le thème de couleurs.

### Ajouter vos projets

```sql
INSERT INTO projects (title, description, technologies, image) VALUES
('Mon Nouveau Projet', 
 'Description du projet',
 JSON_ARRAY('Python', 'SQL', 'Power BI'),
 '/api/placeholder/600/400');
```

## 📝 Structure des Données

- **hero** : Informations principales (nom, titre, description)
- **story** : Timeline de votre parcours professionnel
- **skills** : Vos compétences avec niveaux (0-100)
- **projects** : Vos projets réalisés (technologies en JSON)
- **stats** : Statistiques à afficher (projets, expérience, etc.)

## 🛠️ Commandes Utiles

- `cd client && npm run dev` : Démarre le frontend
- `cd client && npm run build` : Build de production
- Tester la connexion : `http://localhost/Portfolio/server/test_connection.php`
- Tester l'API : `http://localhost/Portfolio/server/api/portfolio.php`

## ⚠️ Notes Importantes

- Le backend PHP fonctionne via WAMP (pas besoin de le démarrer séparément)
- Les données sont stockées dans MySQL
- Les animations sont optimisées pour une expérience fluide
- Le design est entièrement responsive

## 🎯 Prochaines Étapes

1. Personnalisez vos données dans la base de données
2. Ajoutez vos vraies images de projets
3. Modifiez les couleurs selon vos préférences
4. Ajoutez vos liens sociaux dans `client/components/Contact.tsx`

Bon développement ! 🚀

