# 📁 Fichiers à uploader manuellement après déploiement

## ⚠️ Important

Les fichiers suivants sont **exclus de GitHub** (pour des raisons de sécurité/privacy) mais **DOIVENT être uploadés manuellement** sur votre hébergeur pour que le portfolio fonctionne :

## 📄 Documents à uploader

### 1. Documents personnels (`client/public/uploads/`)
- `cv-salwane-alao.pdf`
- `diplome-esigelec.pdf`
- `cert-udemy-datascience-ml.pdf.jpeg`
- `cert-linkedin-excel.pdf.jpeg`
- `cert-kaggle-ml.pdf.jpeg`
- `cert-udemy-powerbi.pdf.jpeg`
- `cert-linkedin-powerbi-chatgpt.pdf.jpeg`
- `cert-kaggle-deeplearning.pdf.jpeg`
- `cert-goskills-excel.pdf.jpeg`
- `cert-google-analytics.pdf.jpeg`

### 2. Photo de profil (`client/public/images/`)
- `profile-picture.png` (ou `.jpg`)

### 3. Logos (optionnel, si vous voulez remplacer les placeholders)
- `logo-grdf.svg`
- `logo-boku.svg`
- `logo-esigelec.svg`
- `logo-bac-benin.svg`
- `logo-ministere-travail-benin.svg`

## 🚀 Solutions pour Vercel

### Option 1 : Upload via Vercel Dashboard (Recommandé)

1. **Après le déploiement sur Vercel** :
   - Va sur [vercel.com](https://vercel.com)
   - Sélectionne ton projet
   - Va dans l'onglet **"Settings"** → **"Environment Variables"**
   - Mais pour les fichiers statiques, utilise plutôt :

2. **Via Vercel CLI** :
```bash
# Installer Vercel CLI
npm i -g vercel

# Se connecter
vercel login

# Dans le dossier client/public/uploads
# Uploader les fichiers un par un
vercel --prod
```

### Option 2 : Utiliser un service de stockage cloud (Meilleure solution)

#### A. Cloudinary (Gratuit jusqu'à 25GB)
1. Crée un compte sur [cloudinary.com](https://cloudinary.com)
2. Upload tes fichiers
3. Modifie les chemins dans la base de données pour pointer vers Cloudinary

#### B. AWS S3 (Payant mais très fiable)
1. Crée un bucket S3
2. Upload tes fichiers
3. Configure les URLs publiques

#### C. GitHub Releases (Gratuit)
1. Crée une release sur GitHub
2. Attache tes fichiers en assets
3. Utilise les URLs directes

### Option 3 : Inclure dans le dépôt (Moins sécurisé)

Si tu veux que les fichiers soient automatiquement déployés, tu peux :

1. **Retirer du .gitignore** (mais attention, tes documents seront publics) :
```gitignore
# Commenter ou retirer ces lignes :
# client/public/uploads/*.pdf
# client/public/uploads/*.jpeg
# client/public/images/profile-picture.png
```

2. **Créer un dossier `uploads-example`** avec des fichiers placeholder
3. **Documenter** que les utilisateurs doivent remplacer par leurs propres fichiers

## 📝 Instructions pour Vercel

### Méthode manuelle (rapide)

1. **Après déploiement sur Vercel** :
   - Va sur ton projet Vercel
   - Clique sur **"Deployments"**
   - Clique sur le dernier déploiement
   - Va dans **"Source"** → **"Browse"**
   - Navigue vers `client/public/uploads/`
   - Upload tes fichiers via l'interface (si disponible)

2. **Ou via Git** :
   - Crée une branche `production-files`
   - Ajoute temporairement les fichiers (retire du .gitignore)
   - Push cette branche
   - Vercel déploiera automatiquement
   - Puis supprime cette branche

### Méthode recommandée : Cloudinary

1. **Setup Cloudinary** :
```bash
npm install cloudinary
```

2. **Créer un script d'upload** :
```javascript
// scripts/upload-to-cloudinary.js
const cloudinary = require('cloudinary').v2;

cloudinary.config({
  cloud_name: 'TON_CLOUD_NAME',
  api_key: 'TON_API_KEY',
  api_secret: 'TON_API_SECRET'
});

// Upload tous les fichiers du dossier uploads/
```

3. **Mettre à jour la base de données** avec les URLs Cloudinary

## ✅ Checklist avant déploiement

- [ ] Documents PDF uploadés sur l'hébergeur
- [ ] Photo de profil uploadée
- [ ] Logos uploadés (si personnalisés)
- [ ] Base de données configurée avec les bons chemins
- [ ] Test de tous les liens de téléchargement
- [ ] Vérification que les images s'affichent

## 🔗 Ressources

- [Vercel Documentation](https://vercel.com/docs)
- [Cloudinary Free Tier](https://cloudinary.com/pricing)
- [GitHub Releases](https://docs.github.com/en/repositories/releasing-projects-on-github)

---

**Note** : Pour un portfolio professionnel, je recommande **Cloudinary** ou un service similaire pour héberger les fichiers statiques. C'est plus propre et plus professionnel que d'inclure les fichiers dans le dépôt Git.

