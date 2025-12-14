# Images du Portfolio

## Photo de Profil

Placez votre photo de profil dans ce dossier avec le nom : `profile-picture.jpg`

### Spécifications recommandées :
- Format : JPG ou PNG
- Taille : 800x800 pixels minimum (carré)
- Poids : < 500 KB pour un chargement rapide
- Qualité : Haute résolution pour un rendu net

### Emplacement du fichier :
```
client/public/images/profile-picture.jpg
```

Le chemin dans la base de données est configuré pour pointer vers : `/images/profile-picture.jpg`

### Mise à jour dans la base de données :

Si vous changez le nom du fichier, mettez à jour la base de données :

```sql
UPDATE hero SET profile_picture = '/images/votre-nouveau-fichier.jpg' WHERE id = 1;
```


