# Migration API PHP → Next.js API Routes

## ✅ Conversion terminée

Toutes les API PHP ont été converties en API Routes Next.js.

## 📁 Structure créée

```
client/
├── lib/
│   └── db.ts                    # Connexion MySQL avec mysql2
├── app/
│   └── api/
│       ├── portfolio/
│       │   └── route.ts         # Endpoint principal (GET)
│       ├── languages/
│       │   └── route.ts         # GET /api/languages
│       ├── skills/
│       │   └── route.ts         # GET /api/skills
│       ├── projects/
│       │   └── route.ts         # GET /api/projects
│       ├── stats/
│       │   └── route.ts         # GET /api/stats
│       ├── story/
│       │   └── route.ts         # GET /api/story
│       ├── qualities/
│       │   └── route.ts         # GET /api/qualities
│       └── documents/
│           └── route.ts         # GET /api/documents
```

## 🔧 Configuration

### Variables d'environnement

Créez un fichier `client/.env.local` avec :

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=root
DB_NAME=portfolio_db
```

### En production (Vercel)

1. Va dans **Settings** → **Environment Variables**
2. Ajoute :
   - `DB_HOST` = ton host MySQL
   - `DB_USER` = ton user MySQL
   - `DB_PASS` = ton password MySQL
   - `DB_NAME` = portfolio_db

## 📦 Dépendances installées

- `mysql2` : Client MySQL pour Node.js

## 🚀 Avantages

1. **Tout en Next.js** : Plus besoin de serveur PHP séparé
2. **Déploiement simplifié** : Tout sur Vercel
3. **Performance** : API Routes Next.js sont optimisées
4. **TypeScript** : Type-safe API routes

## 🔄 Changements

### Avant (PHP)
- Backend : `server/api/portfolio.php`
- Frontend : `fetch('/api/portfolio')` → rewrites vers PHP

### Après (Next.js)
- Backend : `client/app/api/portfolio/route.ts`
- Frontend : `fetch('/api/portfolio')` → API Route Next.js directe

## ✅ Tests

Le frontend utilise déjà `/api/portfolio`, donc **aucun changement nécessaire** dans les composants React.

## 🐛 Dépannage

### Erreur de connexion MySQL

1. Vérifiez que MySQL est démarré (WAMP/XAMPP)
2. Vérifiez les variables d'environnement dans `.env.local`
3. Vérifiez que la base de données `portfolio_db` existe

### Erreur "mysql2 not found"

```bash
cd client
npm install mysql2
```

### En production (Vercel)

Assurez-vous d'avoir configuré les variables d'environnement dans Vercel Dashboard.

## 📝 Notes

- Les API Routes Next.js sont accessibles uniquement en GET pour l'instant
- Pour ajouter POST/PUT/DELETE, modifiez les fichiers `route.ts`
- La connexion MySQL utilise un pool de connexions pour la performance

