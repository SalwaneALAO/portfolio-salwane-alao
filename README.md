# Portfolio - Data Analyst & Data Scientist

Portfolio professionnel de **SALWANE ALAO**

## 🛠️ Technologies

- **Frontend**: Next.js 14, React, TypeScript, Tailwind CSS, Framer Motion
- **Backend**: PHP 8.3+, MySQL/MariaDB
- **Base de données**: MySQL avec API REST

## 📦 Installation locale

### Prérequis
- Node.js 18+
- PHP 8.0+
- MySQL/MariaDB
- WAMP/XAMPP (pour le développement local)

### Étapes

1. **Cloner le dépôt**
```bash
git clone https://github.com/VOTRE_USERNAME/VOTRE_REPO.git
cd Portfolio
```

2. **Installer les dépendances frontend**
```bash
cd client
npm install
```

3. **Configurer la base de données**
```bash
cd ../server
cp config/config.example.php config/database.php
# Éditer config/database.php avec vos paramètres MySQL
```

4. **Initialiser la base de données**
- Importer `server/database.sql` dans MySQL via phpMyAdmin
- Ou exécuter : `mysql -u root -p portfolio_db < server/database.sql`
- Exécuter `server/update_all.php` pour peupler les données

5. **Lancer l'application**
```bash
# Terminal 1 : Démarrer WAMP (serveur PHP/MySQL)
# Accéder à http://localhost/Portfolio/server/update_all.php pour initialiser

# Terminal 2 : Frontend Next.js
cd client
npm run dev
# Accéder à http://localhost:3000
```

## 🌐 Déploiement

### Option 1 : Vercel (Recommandé)

1. Allez sur [Vercel.com](https://vercel.com)
2. Connectez votre compte GitHub
3. Importez votre dépôt
4. Configurez :
   - **Framework Preset**: Next.js
   - **Root Directory**: `client`
5. Déployez !

### Option 2 : Hébergement traditionnel

1. Build de production :
```bash
cd client
npm run build
```

2. Uploader les fichiers sur votre serveur
3. Configurer la base de données
4. Configurer les chemins dans `next.config.js`

## 📁 Structure du projet

```
Portfolio/
├── client/                 # Frontend Next.js
│   ├── app/               # Pages Next.js
│   ├── components/        # Composants React
│   └── public/            # Fichiers statiques
├── server/                # Backend PHP
│   ├── api/              # API REST
│   ├── config/           # Configuration DB
│   └── update_all.php    # Script de mise à jour
└── README.md
```

## 🔒 Sécurité

Les fichiers sensibles sont exclus du dépôt :
- `server/config/database.php` (utilisez `config.example.php`)
- Documents personnels dans `client/public/uploads/`
- Photos de profil

## 📧 Contact

**SALWANE ALAO**  
Data Analyst & Data Scientist  
En recherche active d'un CDI ou CDD

---

⭐ N'hésitez pas à forker et adapter ce portfolio à vos besoins !
