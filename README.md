
# Application de Gestion de Tâches avec Symfony

Ce projet est une application web simple développée avec Symfony 7.4 pour gérer une liste de tâches. Elle intègre une fonctionnalité complète de suivi de l'historique pour chaque tâche, fournissant une piste d'audit détaillée.

Attention : Il vous faut PHP >=8.2 pour faire fonctionner ce projet.

## Fonctionnalités

- **Gestion des Tâches :** Créez, consultez, mettez à jour et supprimez des tâches.
- **Historique des Modifications :** Chaque tâche dispose d'un historique complet. Toutes les actions (création, modification, suppression) sont enregistrées et associées à un utilisateur.
- **Piste d'Audit :** L'historique fournit une piste d'audit claire, indiquant quel utilisateur a effectué une action et à quel moment.
- **Intégration Base de Données :** Les données sont stockées et gérées de manière persistante grâce à une base de données relationnelle (MariaDB/MySQL) et l'ORM Doctrine.

## Initialisation de votre IDE

### PHPStorm

1. Ouvrir le projet dans PHPStorm.
2. Installer les extensions recommandées pour Symfony : `File > Settings > Plugins` (Twig, Symfony Support, PHP Annotations).

### Visual Studio Code

1. Ouvrir le projet dans Visual Studio Code.
2. Installer les extensions recommandées depuis l'onglet "Extensions": `whatwedo.twig`, `TheNouillet.symfony-vscode`, `DEVSENSE.phptools-vscode`.

## Installation et Lancement

Suivez les étapes ci-dessous pour installer et lancer le projet, que ce soit sur IDX ou en local.

### 1. Installation des Dépendances

Assurez-vous que [Composer](https://getcomposer.org/) est installé sur votre machine, puis lancez la commande suivante à la racine du projet pour installer les dépendances PHP :

```bash
composer install
```

### 2. Configuration de la Base de Données

1.  Créez un fichier `.env.local` à la racine du projet en copiant `.env`.
2.  Modifiez la variable `DATABASE_URL` dans le fichier `.env.local` pour correspondre à la configuration de votre base de données. Exemple pour MariaDB/MySQL :

    ```
    DATABASE_URL="mysql://root:VOTRE_MOT_DE_PASSE@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
    ```

    *Remplacez `VOTRE_MOT_DE_PASSE` par le mot de passe de votre utilisateur `root` (ou laissez vide s'il n'y en a pas, comme c'est souvent le cas avec IDX ou XAMPP par défaut).*

### 3. Initialisation de la Base de Données

Exécutez les commandes suivantes pour créer la base de données et appliquer les migrations :

```bash
# Crée la base de données si elle n'existe pas
php bin/console doctrine:database:create

# Exécute les migrations pour créer le schéma (tables, colonnes, etc.)
php bin/console doctrine:migrations:migrate
```

### 4. Chargement des Données de Test (Fixtures)

Pour peupler la base de données avec des utilisateurs et des tâches de démonstration, exécutez la commande suivante :

```bash
php bin/console doctrine:fixtures:load
```

### 5. Lancement du Serveur

Vous pouvez maintenant lancer le serveur de développement intégré de Symfony :

```bash
symfony server:start
```

L'application sera accessible à l'adresse indiquée dans le terminal (généralement `https://127.0.0.1:8000`).

## Utilisation de l'Application

- **Liste des tâches :** Accédez à la route `/tasks` pour voir toutes les tâches.
- **Détails d'une tâche :** Cliquez sur une tâche pour accéder à sa page de détail (`/task/{id}`), où vous pourrez également consulter son historique de modifications.

N'hésitez pas à consulter la [documentation officielle de Symfony](https://symfony.com/doc/current/index.html) pour plus d'informations.
