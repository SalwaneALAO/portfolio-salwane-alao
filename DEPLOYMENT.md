# Guide de déploiement sur GitHub

## 📋 Prérequis

1. Compte GitHub
2. Git installé sur votre machine
3. Node.js et npm installés

## 🚀 Étapes pour héberger sur GitHub

### 1. Créer un dépôt GitHub

1. Allez sur [GitHub.com](https://github.com)
2. Cliquez sur le bouton **"+"** en haut à droite → **"New repository"**
3. Nommez votre dépôt (ex: `portfolio-salwane-alao`)
4. Choisissez **Public** ou **Private**
5. **Ne cochez PAS** "Initialize with README" (on a déjà des fichiers)
6. Cliquez sur **"Create repository"**

### 2. Initialiser Git dans votre projet

Ouvrez un terminal dans le dossier `C:\wamp64\www\Portfolio` et exécutez :

```bash
# Initialiser Git (si pas déjà fait)
git init

# Ajouter tous les fichiers
git add .

# Créer le premier commit
git commit -m "Initial commit: Portfolio Data Analyst"

# Ajouter le dépôt distant (remplacez USERNAME et REPO_NAME)
git remote add origin https://github.com/VOTRE_USERNAME/VOTRE_REPO.git

# Pousser vers GitHub
git branch -M main
git push -u origin main
```

### 3. Vérifier les fichiers sensibles

**IMPORTANT** : Assurez-vous que ces fichiers ne sont PAS dans le dépôt :

- ✅ `server/config/database.php` (contient vos mots de passe)
- ✅ `client/public/uploads/*.pdf` (vos documents personnels)
- ✅ `client/public/images/profile-picture.png` (votre photo)

Ces fichiers sont déjà dans `.gitignore` et ne seront pas uploadés.

### 4. Créer un fichier README.md

Créez un fichier `README.md` à la racine avec :

```markdown
# Portfolio - Data Analyst & Data Scientist

Portfolio professionnel de SALWANE ALAO

## 🛠️ Technologies

- **Frontend**: Next.js 14, React, TypeScript, Tailwind CSS
- **Backend**: PHP, MySQL
- **Animations**: Framer Motion

## 📦 Installation

### Prérequis
- Node.js 18+
- PHP 8.0+
- MySQL/MariaDB
- WAMP/XAMPP (pour le développement local)

### Installation

1. Cloner le dépôt
```bash
git clone https://github.com/VOTRE_USERNAME/VOTRE_REPO.git
cd Portfolio
```

2. Installer les dépendances frontend
```bash
cd client
npm install
```

3. Configurer la base de données
```bash
cd ../server
cp config/config.example.php config/database.php
# Éditer config/database.php avec vos paramètres
```

4. Initialiser la base de données
```bash
# Importer database.sql dans MySQL
mysql -u root -p portfolio_db < database.sql

# Ou via phpMyAdmin : importer database.sql
```

5. Lancer l'application
```bash
# Terminal 1 : Backend PHP (WAMP doit être démarré)
# Accéder à http://localhost/Portfolio/server/update_all.php

# Terminal 2 : Frontend Next.js
cd client
npm run dev
# Accéder à http://localhost:3000
```

## 🌐 Déploiement

### Option 1 : Vercel (Recommandé pour Next.js)

1. Allez sur [Vercel.com](https://vercel.com)
2. Connectez votre compte GitHub
3. Importez votre dépôt
4. Configurez :
   - **Framework Preset**: Next.js
   - **Root Directory**: `client`
   - **Build Command**: `npm run build`
   - **Output Directory**: `.next`
5. Ajoutez les variables d'environnement si nécessaire
6. Déployez !

### Option 2 : GitHub Pages (Statique)

Pour GitHub Pages, vous devez exporter Next.js en statique :

```bash
cd client
npm run build
npm run export
# Puis suivez les instructions GitHub Pages
```

### Option 3 : Hébergement traditionnel

1. Uploader les fichiers via FTP
2. Configurer la base de données sur le serveur
3. Configurer les chemins dans `next.config.js`

## 📝 Notes

- Les fichiers sensibles (config DB, documents personnels) ne sont pas inclus dans le dépôt
- Utilisez `config.example.php` comme modèle pour la configuration
- Les documents doivent être uploadés manuellement sur le serveur de production

## 📧 Contact

SALWANE ALAO - Data Analyst & Data Scientist
```

## 🔒 Sécurité

**NE COMMITEZ JAMAIS :**
- ❌ `server/config/database.php` (mots de passe)
- ❌ Vos documents PDF personnels
- ❌ Vos photos de profil
- ❌ Fichiers `.env` avec des secrets

Ces fichiers sont déjà dans `.gitignore`.

## 📚 Commandes Git utiles

```bash
# Voir les fichiers qui seront commités
git status

# Ajouter des fichiers spécifiques
git add fichier.txt

# Créer un commit
git commit -m "Description des changements"

# Pousser vers GitHub
git push origin main

# Récupérer les dernières modifications
git pull origin main
```

## 🎯 Prochaines étapes après le push

1. **Vérifier sur GitHub** que tous les fichiers sont bien là
2. **Configurer GitHub Pages** ou **Vercel** pour l'hébergement
3. **Ajouter un fichier README.md** avec les instructions
4. **Créer un fichier LICENSE** si vous voulez partager votre code

---

**Bon déploiement ! 🚀**

