# ✅ Checklist de déploiement Vercel

## ✅ Configuration terminée

- [x] Root Directory configuré à `client`
- [x] `vercel.json` simplifié
- [x] API Routes Next.js créées
- [x] Code poussé sur GitHub

## 🚀 Prochaines étapes

### 1. Redéployer le projet

**Option A : Via l'interface Vercel**
1. Va dans **"Deployments"**
2. Clique sur les **"..."** du dernier déploiement
3. Sélectionne **"Redeploy"**
4. Confirme

**Option B : Via Git (recommandé)**
```bash
git commit --allow-empty -m "Trigger Vercel deployment"
git push
```

### 2. Vérifier le build

Après le redéploiement, vérifie les logs :
- ✅ `npm install` s'exécute dans `client/`
- ✅ Next.js est détecté
- ✅ `npm run build` réussit
- ✅ Le déploiement se termine sans erreur

### 3. Configurer les variables d'environnement

**IMPORTANT** : Pour que la base de données fonctionne :

1. **Settings** → **Environment Variables**
2. Ajoute ces variables :

```
DB_HOST = localhost (ou ton host MySQL en production)
DB_USER = root (ou ton user MySQL)
DB_PASS = root (ou ton password MySQL)
DB_NAME = portfolio_db
```

3. Sélectionne **Production**, **Preview**, et **Development**
4. **Save**

### 4. ⚠️ Note importante sur MySQL

**MySQL localhost ne fonctionnera PAS depuis Vercel.**

Tu as 2 options :

#### Option A : Base de données cloud (Recommandé)

Utilise un service gratuit :
- **[PlanetScale](https://planetscale.com)** - MySQL gratuit
- **[Railway](https://railway.app)** - MySQL gratuit
- **[Supabase](https://supabase.com)** - PostgreSQL gratuit
- **[Aiven](https://aiven.io)** - MySQL gratuit

Puis mets à jour les variables d'environnement avec les nouvelles credentials.

#### Option B : Utiliser des données statiques

Si tu veux juste montrer le portfolio sans base de données :
- Les API Routes retourneront les données par défaut
- Ça fonctionnera mais sans données dynamiques

### 5. Uploader les fichiers statiques

N'oublie pas d'uploader :
- Documents PDF dans `client/public/uploads/`
- Photo de profil dans `client/public/images/`

Voir `DEPLOYMENT_FILES.md` pour les détails.

## ✅ Vérification finale

Une fois déployé, vérifie :
- [ ] Le site se charge
- [ ] Les sections s'affichent
- [ ] Les images se chargent
- [ ] Les liens fonctionnent
- [ ] Les documents sont accessibles (si uploadés)

---

**Une fois le redéploiement terminé, dis-moi si ça fonctionne ! 🚀**

