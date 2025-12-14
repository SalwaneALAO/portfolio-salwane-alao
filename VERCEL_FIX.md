# 🔧 Fix Vercel - Configuration Root Directory

## ⚠️ Problème

Vercel ne détecte pas Next.js car il cherche le `package.json` à la racine, alors que Next.js est dans `client/`.

## ✅ Solution

**Tu DOIS configurer le Root Directory dans l'interface Vercel :**

### Étapes :

1. **Va sur [vercel.com](https://vercel.com)** → Ton projet
2. **Settings** → **General**
3. Scroll jusqu'à **"Root Directory"**
4. Clique sur **"Edit"**
5. Tape : `client`
6. Clique sur **"Save"**

### Alternative : Via Vercel CLI

Si tu préfères utiliser la CLI :

```bash
# Installer Vercel CLI
npm i -g vercel

# Se connecter
vercel login

# Dans le dossier racine du projet
vercel link

# Configurer le root directory
vercel --prod
# Quand demandé, spécifie "client" comme root directory
```

## 📝 Note importante

Le fichier `vercel.json` a été simplifié pour fonctionner avec le Root Directory configuré à `client/`.

Une fois le Root Directory configuré à `client`, Vercel :
- ✅ Trouvera automatiquement `client/package.json`
- ✅ Détectera Next.js
- ✅ Exécutera `npm install` dans `client/`
- ✅ Exécutera `npm run build` dans `client/`

## 🚀 Après configuration

1. **Redéploie** le projet
2. Le build devrait maintenant fonctionner
3. Vérifie les logs pour confirmer

---

**Le Root Directory DOIT être configuré dans l'interface Vercel, pas seulement dans vercel.json !**

