# Instameme - Réseau Social PHP 🎭

## Description
Réseau social simple développé en PHP avec base de données MySQL locale pour partager des mèmes. Les utilisateurs peuvent s'inscrire, se connecter et publier des posts avec système de likes et commentaires.

## Fonctionnalités
- **Inscription/Connexion** - Créer un compte et se connecter
- **Publier des posts** - Partager du contenu texte
- **Liker** - Aimer les publications
- **Commenter** - Répondre aux posts
- **Profil utilisateur** - Profil user

## Technologies
- **PHP** 
- **MySQL** 
- **HTML/CSS** 



## Structure
```
INSTAMEME/
├── demo_php/
│   ├── composants/
│   │   ├── header.php
│   │   └── footer.php
│   ├── css/
│   │   ├── accueil.css
│   │   ├── style.css
│   │   └── wallpaperflare.com_wallpaper.jpg
│   ├── images/
│   ├── index.php
│   ├── connexion.php
│   ├── inscription.php
│   ├── profil.php
│   ├── action.php
│   ├── affichage.php
│   ├── créer.php
│   ├── deconnexion.php
│   ├── traitement_client.php
│   ├── traitement_connexion.php
│   ├── db.php
│   └── config.php
```

## Base de données
- **users** - Comptes utilisateurs
- **posts** - Publications de mèmes
- **likes** - Likes sur les posts
- **comments** - Commentaires

## Fichiers principaux
- **index.php** - Page d'accueil
- **connexion.php** - Formulaire de connexion
- **inscription.php** - Formulaire d'inscription
- **profil.php** - Page de profil utilisateur
- **créer.php** - Création de nouveaux posts
- **action.php** - Traitement des actions (like, comment)
- **db.php** - Connexion à la base de données

