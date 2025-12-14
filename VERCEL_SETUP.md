# Configuration Vercel - Guide étape par étape

## ⚠️ Erreur corrigée

Le fichier `vercel.json` a été corrigé (propriété `rootDirectory` retirée).

## 🔧 Configuration dans Vercel Dashboard

### Option 1 : Via l'interface (Recommandé)

1. **Va sur [vercel.com](https://vercel.com)** → Ton projet
2. **Settings** → **General**
3. Dans la section **"Root Directory"** :
   - Clique sur **"Edit"**
   - Sélectionne ou tape : `client`
   - Clique sur **"Save"**

### Option 2 : Via vercel.json (Déjà fait)

Le fichier `vercel.json` est déjà configuré avec :
- `buildCommand`: `cd client && npm install && npm run build`
- `outputDirectory`: `client/.next`
- `installCommand`: `cd client && npm install`

## 🔐 Variables d'environnement

**IMPORTANT** : Configure les variables d'environnement pour la base de données :

1. **Settings** → **Environment Variables**
2. Ajoute ces variables :

```
DB_HOST = localhost (ou ton host MySQL en production)
DB_USER = root (ou ton user MySQL)
DB_PASS = root (ou ton password MySQL)
DB_NAME = portfolio_db
```

3. Sélectionne **Production**, **Preview**, et **Development**
4. Clique sur **"Save"**

## 🚀 Déploiement

1. **Redéploie** ton projet :
   - Va dans **"Deployments"**
   - Clique sur les **"..."** du dernier déploiement
   - **"Redeploy"**

2. Ou **push un nouveau commit** :
```bash
git commit --allow-empty -m "Trigger Vercel deployment"
git push
```

## ✅ Vérification

Après le déploiement, vérifie :
- ✅ Le build passe sans erreur
- ✅ L'application se charge
- ✅ Les données s'affichent (si MySQL est accessible depuis Vercel)

## ⚠️ Note importante

**Pour que MySQL fonctionne depuis Vercel**, tu dois :

1. **Héberger MySQL en ligne** (pas localhost) :
   - [PlanetScale](https://planetscale.com) (gratuit)
   - [Railway](https://railway.app) (gratuit)
   - [Supabase](https://supabase.com) (gratuit)
   - [Aiven](https://aiven.io) (gratuit)

2. **OU utiliser une base de données cloud** :
   - [MongoDB Atlas](https://www.mongodb.com/cloud/atlas) (gratuit)
   - [Supabase](https://supabase.com) (PostgreSQL gratuit)

3. **Mettre à jour les variables d'environnement** avec les nouvelles credentials

## 📝 Alternative : Base de données locale

Si tu veux garder MySQL local, tu peux :
- Utiliser [ngrok](https://ngrok.com) pour exposer ton MySQL local
- Ou héberger le backend PHP séparément et utiliser les API Routes Next.js uniquement pour le frontend

---

**Une fois configuré, redéploie et ça devrait fonctionner ! 🚀**

