# Gestionnaire de Tâches

Bienvenue sur le Gestionnaire de Tâches, une application web simple et puissante conçue avec Symfony pour vous aider à organiser votre travail et votre vie.

## ✨ Fonctionnalités

*   Créez, modifiez, et supprimez des tâches en quelques clics.
*   Consultez la liste de vos tâches pour garder une vue d'ensemble.
*   Système d'authentification complet (inscription, connexion).
*   Interface utilisateur claire et intuitive basée sur Bootstrap.

## 🛠️ Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants sur votre machine :

*   [PHP 8.2](https://www.php.net/downloads.php) ou supérieur
*   [Composer](https://getcomposer.org/)
*   [Symfony CLI](https://symfony.com/download)

## 🚀 Installation

1.  **Clonez le projet** :

    '''bash
    git clone https://github.com/votre-nom-utilisateur/votre-projet.git
    cd votre-projet
    '''

2.  **Installez les dépendances** avec Composer :

    '''bash
    composer install
    '''

## ⚙️ Configuration

1.  **Variables d'environnement** :
    Créez votre fichier de configuration local en copiant le fichier d'exemple `.env`.

    '''bash
    cp .env .env.local
    '''

2.  **Base de données** :
    Modifiez la variable `DATABASE_URL` dans votre fichier `.env.local` pour correspondre aux accès de votre base de données (MySQL, PostgreSQL, etc.).

    *Exemple pour MySQL :*
    '''dotenv
    # .env.local
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8.0.32&charset=utf8mb4"
    '''

3.  **Créez la base de données et appliquez les migrations** :

    '''bash
    # Créez la base de données
    php bin/console doctrine:database:create

    # Appliquez les migrations pour créer les tables
    php bin/console doctrine:migrations:migrate
    '''

## 🏃 Démarrage

Pour lancer l'application en local, utilisez le serveur web de Symfony :

'''bash
symfony server:start
'''

Votre application sera accessible à l'adresse `http://localhost:8000`.

## 🔧 Commandes utiles

Ce projet inclut des commandes personnalisées pour gérer les tâches directement depuis votre terminal.

'''bash
# Lister toutes les tâches
php bin/console app:task list

# Créer une nouvelle tâche
php bin/console app:task create --title="Ma nouvelle tâche" --description="Description de la tâche"
'''

## 🏗️ Construit avec

*   **[Symfony](https://symfony.com/)** - Le framework PHP principal.
*   **[Doctrine](https://www.doctrine-project.org/)** - Pour la gestion de la base de données.
*   **[Twig](https://twig.symfony.com/)** - Le moteur de templates.
*   **[Bootstrap](https://getbootstrap.com/)** - Pour le style de l'interface utilisateur.

## 📄 Licence

Ce projet est distribué sous la licence MIT. Voir le fichier `LICENSE` pour plus d'informations.
