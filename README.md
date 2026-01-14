# Gestionnaire de Tâches

Bienvenue sur le Gestionnaire de Tâches, une application web simple et puissante conçue avec Symfony pour vous aider à organiser votre travail et votre vie.

## ✨ Fonctionnalités

*   Créez, modifiez, et supprimez des tâches en quelques clics.
*   Consultez la liste de vos tâches pour garder une vue d'ensemble.
*   Gérez vos tâches directement depuis la ligne de commande.
*   Interface utilisateur claire et intuitive.

## 🛠️ Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants sur votre machine :

*   [PHP 8.2](https://www.php.net/downloads.php) ou supérieur
*   [Composer](https://getcomposer.org/)
*   [Symfony CLI](https://symfony.com/download)

## 🚀 Installation

1.  **Clonez le projet** :

    ```bash
    git clone https://github.com/votre-nom-utilisateur/votre-projet.git
    cd votre-projet
    ```

2.  **Installez les dépendances** avec Composer :

    ```bash
    composer install
    ```

## ⚙️ Configuration

1.  **Configurez la base de données** :
    Copiez le fichier `.env` vers `.env.local` et mettez à jour la variable `DATABASE_URL` avec vos identifiants de base de données.

    ```
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8.0.32&charset=utf8mb4"
    ```

2.  **Créez la base de données** :
    Exécutez la commande suivante pour créer la base de données :

    ```bash
    php bin/console doctrine:database:create
    ```

3.  **Appliquez les migrations** :
    Mettez à jour le schéma de la base de données en appliquant les migrations.

    ```bash
    php bin/console doctrine:migrations:migrate
    ```

## ▶️ Démarrage

Lancez le serveur de développement local avec la commande :

```bash
symfony server:start
```

L'application sera accessible à l'adresse [http://127.0.0.1:8000](http://127.0.0.1:8000).

##  ligne de commande

Vous pouvez également interagir avec l'application via la ligne de commande pour gérer vos tâches :

```bash
# Lister toutes les tâches
php bin/console app:task list

# Créer une nouvelle tâche
php bin/console app:task create --title="Ma nouvelle tâche" --description="Description de la tâche"
```
