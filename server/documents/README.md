# Documents du Portfolio

Ce dossier contient les documents téléchargeables du portfolio (CV, diplômes, certifications).

## Structure

Placez vos fichiers PDF dans ce dossier :

- `cv-salwane-alao.pdf` - Votre CV
- `diplome-esigelec.pdf` - Votre diplôme ESIGELEC
- `toeic-certification.pdf` - Votre certification TOEIC

## Configuration

Les chemins des fichiers sont configurés dans la base de données dans la table `documents`.

Pour mettre à jour les chemins, exécutez dans phpMyAdmin :

```sql
UPDATE documents SET file_path = '/Portfolio/server/documents/cv-salwane-alao.pdf' WHERE type = 'cv';
UPDATE documents SET file_path = '/Portfolio/server/documents/diplome-esigelec.pdf' WHERE type = 'diploma';
UPDATE documents SET file_path = '/Portfolio/server/documents/toeic-certification.pdf' WHERE type = 'certification';
```

## Accès via URL

Les documents seront accessibles via :
- `http://localhost/Portfolio/server/documents/cv-salwane-alao.pdf`
- `http://localhost/Portfolio/server/documents/diplome-esigelec.pdf`
- `http://localhost/Portfolio/server/documents/toeic-certification.pdf`

## Note

Assurez-vous que les fichiers sont en format PDF et que les permissions de lecture sont correctement configurées.


