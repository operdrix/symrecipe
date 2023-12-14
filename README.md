# SymRecipe

Bienvenue dans le projet SymRecipe !

## Description

SymRecipe est un projet Symfony pour gérer et partager des recettes de cuisine. Il offre les fonctionnalités suivantes :

- Ajouter, modifier et supprimer des recettes
- Rechercher des recettes par ingrédients, catégories ou mots-clés
- Créer et gérer des listes de courses
- Partager des recettes avec d'autres utilisateurs

## Installation

Suivez les étapes ci-dessous pour installer et exécuter le projet SymRecipe :

1. Clonez le dépôt GitHub : `git clone https://github.com/operdrix/symrecipe.git`
2. Accédez au répertoire du projet : `cd symrecipe`
3. Installez les dépendances avec Composer : `composer install`
4. Configurez la base de données dans le fichier `.env` ou créer un fichier `.env.dev.local` et configurer l'accès à la bdd.
5. Créez la base de données : `php bin/console doctrine:database:create`
6. Appliquez les migrations : `php bin/console doctrine:migrations:migrate`
7. Appliquer les fixtures : `php bin/console doctrine:fixtures:load`
8. Lancez le serveur de développement : `symfony server:start`

## Utilisation

Une fois le projet installé et le serveur de développement lancé, vous pouvez accéder à l'application dans votre navigateur à l'adresse `http://localhost:8000`.

## Contribuer

Si vous souhaitez contribuer à SymRecipe, vous pouvez suivre les étapes ci-dessous :

1. Fork le dépôt GitHub
2. Créez une nouvelle branche : `git checkout -b ma-nouvelle-fonctionnalite`
3. Effectuez vos modifications
4. Validez vos modifications : `git commit -m "Ajouter ma nouvelle fonctionnalité"`
5. Poussez les modifications vers votre fork : `git push origin ma-nouvelle-fonctionnalite`
6. Ouvrez une pull request
