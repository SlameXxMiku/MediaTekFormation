# MediatekFormation — Back Office

> Le dépôt d'origine est disponible ici : [MediatekFormation](https://github.com/CNED-SLAM/mediatekformation).<br>
> Son readme contient la présentation complète de l'application d'origine (front office).

---

## Présentation

Ce dépôt est un fork du projet MediatekFormation, développé avec Symfony 6.4.<br>
Il complète l'application d'origine en apportant les éléments suivants :
- Corrections du code existant (bonnes pratiques SonarLint)
- Fonctionnalité de tri par nombre de formations sur la page des playlists
- Un back office complet pour gérer le contenu de la base de données
- Des tests unitaires, d'intégration et fonctionnels
- Une documentation technique générée automatiquement

---

## Fonctionnalités ajoutées

### Front office

**Nombre de formations par playlist**<br>
Le nombre de formations est désormais affiché dans la page des playlists ainsi que dans la page de détail d'une playlist.

**Tri par nombre de formations**<br>
Sur la page des playlists, deux boutons permettent de trier la liste par nombre de formations (ordre croissant ou décroissant).

### Back office

L'accès au back office est sécurisé par une page de connexion avec authentification.<br>
Les identifiants ne sont pas communiqués ici ; ils sont fournis séparément dans la fiche rendue.

**Gestion des formations**
- Ajouter une nouvelle formation (titre, date de parution, identifiant vidéo YouTube, description, playlist, catégories)
- Modifier une formation existante
- Supprimer une formation

**Gestion des playlists**
- Ajouter une nouvelle playlist (nom, description, catégories)
- Modifier une playlist existante
- Supprimer une playlist

**Gestion des catégories**
- Ajouter une nouvelle catégorie
- Supprimer une catégorie

---

## Tests réalisés

- **Test unitaire** : méthode retournant la date au format string
- **Tests d'intégration** : règles de validation
- **Tests d'intégration** : Repository
- **Tests fonctionnels** : accès à l'accueil, tris, filtres
- **Tests de compatibilité** : Chrome et Firefox

---

## Documentation technique

La documentation technique a été générée automatiquement pour l'ensemble de l'application (hors code généré par Symfony).<br>
Elle est disponible dans le dossier `docs/` à la racine du projet.

---

## Installation en local

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- Git
- WampServer (ou équivalent)
- MySQL

### Étapes

**1. Cloner ou télécharger le dépôt**<br>
Télécharger le code et le dézipper dans le dossier `www` de WampServer, puis renommer le dossier en `mediatekformation`.

**2. Installer les dépendances**<br>
Ouvrir une fenêtre de commandes en mode administrateur, se positionner dans le dossier du projet et exécuter :
```bash
composer install
```

**3. Configurer la base de données**<br>
Dans phpMyAdmin, se connecter à MySQL en root sans mot de passe et créer la base de données `mediatekformation`.<br>
Récupérer le fichier `mediatekformation.sql` à la racine du projet et l'importer pour remplir la base de données.<br>
Si un login/mot de passe est nécessaire, créer un utilisateur, lui donner les droits sur la BDD et renseigner le fichier `.env` :
```
DATABASE_URL="mysql://LOGIN:MOT_DE_PASSE@127.0.0.1:3306/mediatekformation"
```

**4. Lancer l'application**<br>
L'adresse pour accéder au front office est :
```
http://localhost/mediatekformation/public/index.php
```

Pour accéder au back office :
```
http://localhost/mediatekformation/public/index.php/login
```

> Les identifiants de connexion au back office sont fournis séparément dans la fiche rendue.

---

## Lancer les tests

```bash
php bin/phpunit
```

Pour lancer un type de test spécifique :
```bash
# Test unitaire
php bin/phpunit tests/Entity/

# Tests de validation
php bin/phpunit tests/Validations/

# Tests des Repository
php bin/phpunit tests/Repositories/

# Tests fonctionnels
php bin/phpunit tests/Functional/
```
