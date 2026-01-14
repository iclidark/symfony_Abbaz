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
    Ouvrez le fichier `.env.local` et modifiez la ligne `DATABASE_URL` avec vos identifiants de base de données.

    ```dotenv
    # .env.local
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8.0.32&charset=utf8mb4"
    ```

2.  **Créez la base de données et les tables** :

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

## ▶️ Lancement de l'application

Vous pouvez démarrer le serveur de développement Symfony très simplement :

```bash
symfony server:start -d
```

L'application sera alors accessible à l'adresse [http://127.0.0.1:8000](http://127.0.0.1:8000).

## ✅ Lancer les tests

Pour vous assurer que tout fonctionne comme prévu, lancez la suite de tests automatisés :

```bash
php bin/phpunit
```

---

*Projet réalisé dans le cadre d'un exercice de développement avec Symfony.*
