### README en Français
<div id="readme-top-fr"></div>

<!-- LOGO DU PROJET -->
<br />
<div align="center">
<img src="public/images/umami-blue.png" alt="Logo" width="120" height="80">

<h3 align="center">Bienvenue sur le Projet UMAMI 🧑‍🍳</h3>

  <p align="center">
    UMAMI est un site de partage de recettes pour explorer les cuisines du monde
    <br />
    <a href="https://github.com/Marikita1007/Umami_3wa"><strong>Explorez la documentation UMAMI »</strong></a>
    <br />
  </p>
</div>

<!-- TABLE DES MATIÈRES -->
<details>
  <summary>Table des matières</summary>
  <ol>
    <li>
      <a href="#about-the-project">À propos du projet</a>
      <ul>
        <li><a href="#built-with">Réalisé avec</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Pour commencer</a>
      <ul>
        <li><a href="#prerequisites">Prérequis</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Utilisation</a></li>
    <li><a href="#roadmap">Feuille de route</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Remerciements</a></li>
  </ol>
</details>




<!-- À PROPOS DU PROJET -->
## À propos du projet

[![Capture d'écran du nom du produit][product-screenshot]](https://localhost:8000/)

UMAMI est une plateforme web conçue pour la communauté culinaire transcendant les barrières culturelles. Ce projet utilise les technologies de développement web les plus récentes pour offrir un espace où les passionnés de cuisine et les personnes peuvent efficacement partager et explorer une variété de recettes de différents pays, y compris leurs propres créations.

Le terme "UMAMI" trouve son origine dans le mot japonais "旨味" ou “うま味” (umami). L'umami est une sensation gustative distincte, au-delà des quatre goûts de base que sont le sucré, l'acide, le salé et l'amer. Il est particulièrement associé à la notion de délicatesse, de saveur et de profondeur de goût. UMAMI est ainsi le nom de cette plateforme, qui tire son inspiration de cette notion, permettant le partage de la délicatesse des différentes cuisines et encourageant la recherche du plaisir de cuisiner.

UMAMI vise à promouvoir le partage de la culture culinaire internationale et à connecter les passionnés de cuisine. Grâce à l'utilisation des technologies web les plus récentes, contribuant ainsi à partager la joie de cuisiner.

<p align="right">(<a href="#readme-top-fr">retour en haut</a>)</p>

### Réalisé avec

**Version Symfony**: 5.4.34 / **Version PHP**: 8.1.13

* ![Symfony][Symfony-url]
* ![Javascript][Javascript-url]
* [![JQuery][JQuery.com]][JQuery-url]

<p align="right">(<a href="#readme-top-fr">retour en haut</a>)</p>


<!-- POUR COMMENCER -->
## Pour commencer

Ce guide vous aidera à configurer et à exécuter le projet UMAMI localement sur votre machine.

### Prérequis

Avant de commencer, assurez-vous d'avoir les prérequis suivants installés sur votre système :

- **XAMPP :** XAMPP est une solution de pile logicielle de serveur web gratuite et open-source développée par Apache Friends. Elle inclut Apache, MySQL, PHP, et plus. Téléchargez et installez XAMPP depuis [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).

- **PHP :** Assurez-vous d'avoir PHP installé. Symfony 5.4 nécessite PHP 7.2.9 ou une version ultérieure. Vous pouvez télécharger PHP depuis [php.net](https://www.php.net/downloads.php) ou utiliser un gestionnaire de paquets comme [Homebrew](https://brew.sh/) pour macOS ou [Chocolatey](https://chocolatey.org/) pour Windows.

- **Composer :** Composer est un gestionnaire de dépendances pour PHP. Installez-le en suivant les instructions sur [getcomposer.org](https://getcomposer.org/download/).

- **Git :** Git est un système de gestion de version distribué. Installez-le en suivant les instructions sur [git-scm.com](https://git-scm.com/downloads).

### Installation

1. Obtenez une clé API gratuite pour les recettes sur [themealdb.com](https://www.themealdb.com/api.php)
2. Clonez le dépôt
   ```sh
   git clone https://github.com/Marikita1007/Umami_3wa.git
   ```
3. Installez les paquets YARN 
   ```sh
   yarn install
   ```
4. Installez les paquets NPM
   ```sh
   npm install
   ```
4. Entrez votre clé API dans `.env`
   ```js
   const API_KEY = 'ENTER YOUR API';
   ```
5. Installez toutes les dépendances nécessaires pour UMAMI
    ```sh
   composer install
   ```
6. Créez la base de données
    ```sh
   php bin/console doctrine:database:create
   ```
7. Exécutez les migrations
   ```sh
    php bin/console doctrine:migrations:migrate
   ```  
<p align="right">(<a href="#readme-top-fr">back to top</a>)</p>



<!-- EXEMPLES D'UTILISATION -->
## Utilisation

### Démarrage du serveur HTTP

   ```bash
   php symfony server:start
   ```
ou avec le raccourci :
   ```bash
   symfony serve
   ```

### Liste de toutes les commandes

   ```bash
  php bin/console list
   ```
***

### Création d'une Entité

Pour créer une nouvelle entité dans votre projet Symfony, vous pouvez utiliser la commande `make:entity` fournie par le MakerBundle de Symfony. Cette commande simplifie le processus de génération d'une nouvelle classe d'entité.

1. Ouvrez votre terminal et accédez au répertoire principal de votre projet Symfony.

2. Exécutez la commande suivante pour créer une nouvelle entité :

   ```bash
    php bin/console make:entity NomDeVotreEntite
    
      - Questions posées pour créer une entité
      - Question 1 : Nom de la classe de l'entité à créer ou à mettre à jour (par exemple, DeliciousElephant) :
      - Question 2 : Nouveau nom de propriété (appuyez sur <return> pour arrêter l'ajout de champs) :
      - Question 3 : Type de champ (entrez ? pour voir tous les types) [string] :
      - Question 4 : Ce champ peut-il être nul dans la base de données (nullable) (oui/non) [non] :
      - Question 5 : Ajouter une autre propriété ? Entrez le nom de la propriété (ou appuyez sur <return> pour arrêter l'ajout de champs) :
    ```

3. Une fois que vous avez fourni toutes les informations requises, la commande générera la classe d'entité et la classe de référentiel correspondante. Modifiez manuellement la classe d'entité si nécessaire.

### Création d'une Migration

Pour apporter des modifications à votre base de données, vous pouvez créer une migration à l'aide de la Console Symfony.

- ÉTAPE 1. Ouvrez votre terminal ou votre invite de commandes.

- ÉTAPE 2. Accédez au répertoire racine de votre projet où se trouve votre application Symfony.

- ÉTAPE 3. Exécutez la commande suivante pour créer un nouveau fichier de migration :

   ```bash
   php bin/console make:migration
   ``` 
### Exécution d'une Migration

1. Après avoir personnalisé le fichier de migration, vous pouvez appliquer les modifications à votre base de données en exécutant la commande suivante :
   ```bash
   php bin/console doctrine:migrations:migrate
   ```
Si vous préférez le raccourci :

   ```bash
   php bin/console d:m:m
   ```
Cette commande exécutera la migration et mettra à jour le schéma de votre base de données selon les modifications que vous avez définies

***

### Exécution d'une Version Spécifique de Migration

Si vous devez exécuter une version spécifique d'une migration dans Symfony, vous pouvez utiliser l'outil Doctrine Migrations. Voici comment procéder :

1. Ouvrez votre terminal ou votre invite de commandes.

2. Accédez au répertoire racine de votre projet où se trouve votre application Symfony.

3. Exécutez la commande suivante pour exécuter une version spécifique de migration :

   ```bash
   php bin/console doctrine:migrations:migrate 'DoctrineMigrations\Version123456'
   ```
4. Si vous souhaitez annuler la migration pour exécuter une version spécifique de migration

   ```bash
   php bin/console doctrine:migrations:migrate -down 'DoctrineMigrations\Version123456'
   ```
***

### Vérification des Versions des Packages Installés

- Vous pouvez facilement vérifier les versions des packages installés à l'aide de l'outil en ligne de commande Composer. Pour vérifier la version d'un package spécifique, utilisez la commande suivante :
  ```bash
  composer show nom-du-package
  ```
***

### Génération de Données Fictives

Ce guide vous aidera à générer des données fictives en utilisant les Fixtures de Doctrine de Symfony.

### Installer les Fixtures

```bash
composer require orm-fixtures --dev
```

### Créer des Fixtures

```bash
php bin/console make:fixtures
```

### Définir Votre Fixture

Créez une classe de fixture, par exemple `RecipeFixtures.php`, où vous définissez les données fictives à générer pour les recettes.

Implémentez l'interface `FixtureGroupInterface` dans votre classe de fixture pour l'assigner à un groupe. Exemple :

   ```php
   class RecipeFixtures extends Fixture implements FixtureGroupInterface
   {
       // Your fixture code here

       public static function getGroups(): array
       {
           return ['recipes'];
       }
   }
   ```

3. Pour charger les fausses données spécifiques aux recettes, exécutez la commande Symfony suivante dans la console :

  ```bash
  php bin/console doctrine:fixtures:load --group=recipes
  ```

***
## SCSS 

### Étape 1 : Créer un fichier SCSS

1. Créez un nouveau fichier SCSS (Sass) dans le répertoire `assets/styles/` de votre projet Symfony. Donnez-lui un nom approprié, par exemple, `footer.scss`.


  ```directry
project/
└── assets/
      └── styles/
         └── footer.scss
  ```

### Étape 2 : Inclure le SCSS dans le modèle Twig de base

2. Dans votre modèle Twig `base.html.twig`, ajoutez une balise <link> pour inclure le fichier CSS compilé. Utilisez la fonction asset() pour générer le chemin correct vers le fichier CSS.

```html
<link rel="stylesheet" href="{{ asset('build/footer-styles.css') }}">
```

## Étape 3 : Mettre à jour webpack.config.js

3. Ouvrez le fichier `webpack.config.js` dans le répertoire racine du projet. Ajoutez le code pour définir le point d'entrée SCSS et la sortie.

```code
.addStyleEntry('footer-styles', './assets/styles/footer.scss')
```

Ce code indique à Webpack Encore de compiler le fichier footer.scss en une sortie footer-styles.css.

## Étape 4 : Compiler le SCSS et surveiller les changements

4. Ouvrez votre ligne de commande ou terminal et exécutez la commande suivante pour compiler le SCSS et surveiller les changements :

```bash
yarn watch
```

5. Cette commande lancera le processus de construction de Webpack Encore et surveillera continuellement les changements dans votre fichier SCSS. Lorsque des changements sont détectés, le fichier CSS sera recompilé automatiquement.

```bash
yarn run build
```

6. Si vous mettez à jour le fichier `webpack.config.js`, n'oubliez pas d'exécuter la commande ci-dessus pour mettre à jour votre fichier webpack.

***

## Cache

Effacer le cache Symfony

```bash
symfony console cache:clear
``` 

Pour lister les pools de cache disponibles
```bash
symfony console cache:pool:list
``` 

Effacer le cache d'un pool
```bash
symfony console cache:pool:clear <pool_name>
```

***

## Debug

### Debug de l'autowiring

```bash
symfony console debug:autowiring
symfony console debug:autowiring <keyword>
```
L'autowiring est le système permettant le chargement automatique des classes et interfaces.

### Debug de la configuration

```bash
symfony console debug:config
```

### Liste des classes / interfaces disponibles via l'autowiring

```bash
️symfony console debug:container
``` 

### Liste des fichiers et variables dans .env

```bash
symfony console debug:dotenv
```

_Pour plus d'exemples, veuillez consulter la [Symfony Documentation](https://symfony.com/)_

<p align="right">(<a href="#readme-top-fr">retour en haut</a>)</p>

***

<!-- FEUILLE DE ROUTE -->
## Feuille de Route

- [x] Journal des modifications
- [x] Ajouter des liens de retour en haut
- [x] Opérations CRUD de base pour les recettes
    - [x] Créer une nouvelle recette
    - [x] Lire/afficher une recette
    - [x] Mettre à jour une recette existante
    - [x] Supprimer une recette
- [x] Authentification des Utilisateurs
    - [x] Enregistrer de nouveaux utilisateurs
    - [x] Fonctionnalité de connexion et déconnexion
- [x] Catégories de Recettes
    - [x] Implémenter des catégories pour les recettes
    - [x] Filtrer les recettes par catégorie
- [x] Fonctionnalité de Recherche
    - [x] Permettre aux utilisateurs de rechercher des recettes
    - [x] Implémenter des options de recherche avancées (catégories, cuisines, par mot-clé)
- [x] Profils Utilisateurs
    - [x] Permettre aux utilisateurs de créer et personnaliser leurs informations de confidentialité
- [x] Fonctionnalités Sociales
    - [x] Permettre aux utilisateurs d'aimer et de commenter les recettes
- [x] Design Responsive
    - [x] Assurer l'utilisabilité du site sur différents appareils (ordinateur de bureau, tablette, mobile)
- [ ] Tests et Assurance Qualité
    - [ ] Écrire et exécuter des tests unitaires
    - [ ] Effectuer des tests d'utilisabilité avec de vrais utilisateurs
- [x] Optimisation des Performances
    - [x] Optimiser les requêtes de base de données
    - [ ] Mettre en place la mise en cache pour les données fréquemment consultées
- [ ] Internationalisation (i18n)
    - [ ] Ajouter le support de la langue pour différentes régions
    - [ ] Fournir des traductions pour les éléments clés
- [x] Améliorations de Sécurité
    - [x] Implémenter une gestion sécurisée des mots de passe
    - [x] Protéger contre les vulnérabilités web courantes (injection SQL, XSS, CSRF)
- [x] Documentation
    - [x] Mettre à jour le README avec des instructions d'utilisation détaillées

Consultez les [problèmes ouverts](https://github.com/Marikita1007/Umami_3wa/issues) pour une liste complète des fonctionnalités proposées (et des problèmes connus).

<p align="right">(<a href="#readme-top-fr">retour en haut</a>)</p>

***

<!-- CONTACT -->
## Contact

Lien du projet : [Marikita1007/Umami_3wa](https://github.com/Marikita1007/Umami_3wa)

<p align="right">(<a href="#readme-top-fr">retour en haut</a>)</p>


<!-- REMERCIEMENTS -->
## Remerciements

Liste des ressources que j'ai utilisées pour créer ce projet.

* [Emojis 🌱☀️ - Copy & Paste Online 🕵️‍♀️](https://tools.picsart.com/text/emojis/)
* [Font Awesome](https://fontawesome.com)
* [Google Fonts](https://fonts.google.com/)

<p align="right">(<a href="#readme-top-fr">retour en haut</a>)</p>


***

### README in English
<div id="readme-top-eng"></div>

<!-- PROJECT LOGO -->
<br />
<div align="center">
<img src="public/images/umami-blue.png" alt="Logo" width="120" height="80">

<h3 align="center">Welcome to UMAMI Project 🧑‍🍳</h3>

  <p align="center">
    UMAMI is a recipe sharing site to explore world Cuisines
    <br />
    <a href="https://github.com/Marikita1007/Umami_3wa"><strong>Explore the docs UMAMI »</strong></a>
    <br />
  </p>
</div>



<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#prerequisites">Prerequisites</a></li>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#acknowledgments">Acknowledgments</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

[![Product Name Screen Shot][product-screenshot]](https://localhost:8000/)

UMAMI is a web platform designed for the culinary community transcending cultural barriers. This project uses the latest web development technologies to provide a space where food enthusiasts and individuals can effectively share and explore a variety of recipes from different countries, including their own creations.

The term “UMAMI” has its origins in the Japanese word “旨味” or “うま味” (umami). Umami is a distinct taste sensation beyond the four basic tastes of sweet, sour, salty and bitter. It is particularly associated with the notion of delicacy, flavor and depth of taste. UMAMI is thus the name of this platform, which draws its inspiration from this notion, allowing the sharing of the delicacy of different cuisines and encouraging the search for the pleasure of cooking.

UMAMI aims to promote the sharing of international culinary culture and connect food enthusiasts. Thanks to the use of the latest web technologies, helping to share the joy of cooking.

<p align="right">(<a href="#readme-top-eng">back to top</a>)</p>



### Built With

**Symfony Version**:5.4.34 / **PHP Version** 8.1.13

* ![Symfony][Symfony-url]
* ![Javascript][Javascript-url]
* [![JQuery][JQuery.com]][JQuery-url]

<p align="right">(<a href="#readme-top-eng">back to top</a>)</p>



<!-- GETTING STARTED -->
## Getting Started

This guide will help you set up and run the UMAMI project locally on your machine.

### Prerequisites

Before you begin, ensure you have the following prerequisites installed on your system:

- **XAMPP:** XAMPP is a free and open-source cross-platform web server solution stack package developed by Apache Friends. It includes Apache, MySQL, PHP, and more. Download and install XAMPP from [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).

- **PHP:** Make sure you have PHP installed. Symfony 5.4 requires PHP 7.2.9 or higher. You can download PHP from [php.net](https://www.php.net/downloads.php) or use a package manager like [Homebrew](https://brew.sh/) for macOS or [Chocolatey](https://chocolatey.org/) for Windows.

- **Composer:** Composer is a dependency manager for PHP. Install it by following the instructions on [getcomposer.org](https://getcomposer.org/download/).

- **Git:** Git is a distributed version control system. Install it by following the instructions on [git-scm.com](https://git-scm.com/downloads).

### Installation

1. Get a free API Key for Recipes at [themealdb.com](https://www.themealdb.com/api.php)
2. Clone the repository
   ```sh
   git clone https://github.com/Marikita1007/Umami_3wa.git
   ```
3. Install YARN packages
   ```sh
   yarn install
   ```
4. Install NPM packages
   ```sh
   npm install
   ```
4. Enter your API in `.env`
   ```js
   const API_KEY = 'ENTER YOUR API';
   ```
5. Install all dependencies needed for UMAMI
    ```sh
   composer install
   ```
6. Create the Database
    ```sh
   php bin/console doctrine:database:create
   ```
7. Run Migrations
   ```sh
    php bin/console doctrine:migrations:migrate
   ```  
<p align="right">(<a href="#readme-top-eng">back to top</a>)</p>



<!-- USAGE EXAMPLES -->
## Usage

### Starting Server HTTP

   ```bash
   php symfony server:start
   ```
or shortcut :
   ```bash
   symfony serve
   ```

### List All the commands

   ```bash
  php bin/console list
   ```
***

### Creating an Entity

To create a new entity in your Symfony project, you can use the `make:entity` command provided by Symfony's MakerBundle. This command simplifies the process of generating a new entity class.

1. Open your terminal and navigate to your Symfony project's root directory.

2. Run the following command to create a new entity:

   ```bash
   php bin/console make:entity YourEntityName

   - Questions asked to create an entity
   - Question 1 : Class name of the entity to create or update (e.g. DeliciousElephant):
   - Question 2 : New property name (press <return> to stop adding fields):
   - Question 3 : Field type (enter ? to see all types) [string]:
   - Question 4 : Can this field be null in the database (nullable) (yes/no) [no]:
   - Question 5 : Add another property? Enter the property name (or press <return> to stop adding fields):
   ```

3. Once you've provided all the required information, the command will generate the entity class and the corresponding repository class. Edit the entity class manually if it's necessary.

### Creating a Migration

To make changes to your database, you can create a migration using the Symfony Console.

- STEP 1. Open your terminal or command prompt.
- STEP 2. Navigate to your project's root directory where your Symfony application is located.
- STEP 3. Run the following command to create a new migration file:

   ```bash
   php bin/console make:migration
   ``` 
### Executing a Migration

1. After customizing the migration file, you can apply the changes to your database by running the following command:

   ```bash
   php bin/console doctrine:migrations:migrate
   ```
If you prefer shortcut :

   ```bash
   php bin/console d:m:m
   ```
This command will execute the migration and update your database schema according to the changes you defined.

***

### Executing a Specific Migration Version

If you need to execute a specific version of a migration in Symfony, you can use the Doctrine Migrations tool. Here's how to do it:

1. Open your terminal or command prompt.

2. Navigate to your project's root directory where your Symfony application is located.

3. Run the following command to execute a specific migration version:

   ```bash
   php bin/console doctrine:migrations:migrate 'DoctrineMigrations\Version123456'
   ```
4. If you want to down the migration to execute a specific migration version:

   ```bash
   php bin/console doctrine:migrations:migrate -down 'DoctrineMigrations\Version123456'
   ```
***

### Checking Installed Package Versions

- You can easily verify the installed package versions using the Composer command-line tool. To check the version of a specific package, use the following command:
  ```bash
  composer show package-name  
  ```
***

### Generating Fake Data

This guide will help you generate fake data using Symfony's Doctrine Fixtures.

### Install Fixtures

```bash
composer require orm-fixtures --dev
```

### Create Fixtures

```bash
php bin/console make:fixtures
```

### Define Your Fixture

1. Create a fixture class, e.g., `RecipeFixtures.php`, where you define the fake data to be generated for recipes.

2. Implement the `FixtureGroupInterface` in your fixture class to assign it to a group. Example:

   ```php
   class RecipeFixtures extends Fixture implements FixtureGroupInterface
   {
       // Your fixture code here

       public static function getGroups(): array
       {
           return ['recipes'];
       }
   }

3. To load the fake data specific to recipes, run the following Symfony console command:

  ```bash
  php bin/console doctrine:fixtures:load --group=recipes
  ```

***
## SCSS

### Step 1: Create an SCSS File

1. Create a new SCSS (Sass) file in the `assets/styles/` directory of your Symfony project. Name it appropriately, e.g., `footer.scss`.

  ```directry
project/
└── assets/
      └── styles/
         └── footer.scss
  ```

### Step 2: Include SCSS in Base Twig Template

2. In your base Twig template (`base.html.twig`), add a `<link>` tag to include the compiled CSS file. Use the `asset()` function to generate the correct path to the CSS file.

```html
<link rel="stylesheet" href="{{ asset('build/footer-styles.css') }}">
```

## Step 3: Update webpack.config.js

3. Open the `webpack.config.js` file in the project's root directory. Add the code to define your SCSS entry point and output.

```code
.addStyleEntry('footer-styles', './assets/styles/footer.scss')
```

This code tells Webpack Encore to compile the footer.scss file into a footer-styles.css output.

## Step 4: Compile SCSS and Watch for Changes

4. Open your command line or terminal and execute the following command to compile the SCSS and watch for changes:

```bash
yarn watch
```

5. This command will start the Webpack Encore build process and continuously watch for changes in your SCSS file. When changes are detected, the CSS file will be recompiled automatically.

```bash
yarn run build
```

6. If you update the `webpack.config.js` file, don't forget to run the command above to update your webpack file.

***

## Cache

Clear symfony cache

```bash
symfony console cache:clear
``` 

To list available pools cache
```bash
symfony console cache:pool:list
``` 

Clear a pool cache
```bash
symfony console cache:pool:clear <pool_name>
```

***

## Debug

### Debug autowiring

```bash
symfony console debug:autowiring
symfony console debug:autowiring <keyword>
```
Autowiring is the system allowing the automatic loading of classes and interfaces.

### Debug config

```bash
symfony console debug:config
```

### List of classes / available interfaces via autowiring

```bash
️symfony console debug:container
``` 

### List the files and variables in .env

```bash
symfony console debug:dotenv
```

_For more examples, please refer to the [Symfony Documentation](https://symfony.com/)_

<p align="right">(<a href="#readme-top-eng">back to top</a>)</p>

***

<!-- ROADMAP -->
## Roadmap

- [x] Changelog
- [x] Add back to top links
- [x] Basic Recipe CRUD Operations
    - [x] Create a new recipe
    - [x] Read/view a recipe
    - [x] Update an existing recipe
    - [x] Delete a recipe
- [x] User Authentication
    - [x] Register new users
    - [x] Login and Logout functionality
- [x] Recipe Categories
    - [x] Implement categories for recipes
    - [x] Filter recipes by category
- [x] Search Functionality
    - [x] Allow users to search for recipes
    - [x] Implement advanced search options (categories, cuisines, by word.)
- [x] User Profiles
    - [x] Enable users to create and customize their privacy informations
- [x] Social Features
    - [x] Allow users to like and comment on recipes
- [x] Responsive Design
    - [x] Ensure the site is usable on various devices (desktop, tablet, mobile)
- [ ] Testing and Quality Assurance
    - [ ] Write and run unit tests
    - [ ] Conduct usability testing with real users
- [x] Performance Optimization
    - [x] Optimize database queries
    - [ ] Implement caching for frequently accessed data
- [ ] Internationalization (i18n)
    - [ ] Add language support for different regions
    - [ ] Provide translations for key elements
- [x] Security Improvements
    - [x] Implement secure password handling
    - [x] Protect against common web vulnerabilities (SQL injection, XSS, CSRF)
- [x] Documentation
    - [x] Update README with detailed usage instructions

See the [open issues](https://github.com/Marikita1007/Umami_3wa/issues) for a full list of proposed features (and known issues).

<p align="right">(<a href="#readme-top-eng">back to top</a>)</p>

***

<!-- CONTACT -->
## Contact

Project Link: [Marikita1007/Umami_3wa](https://github.com/Marikita1007/Umami_3wa)

<p align="right">(<a href="#readme-top-eng">back to top</a>)</p>


<!-- ACKNOWLEDGMENTS -->
## Acknowledgments

List of resources I used to create this project.

* [Emojis 🌱☀️ - Copy & Paste Online 🕵️‍♀️](https://tools.picsart.com/text/emojis/)
* [Font Awesome](https://fontawesome.com)
* [Google Fonts](https://fonts.google.com/)

<p align="right">(<a href="#readme-top-eng">back to top</a>)</p>


<!-- MARKDOWN LINKS & IMAGES -->
[product-screenshot]: public/images/screen-shot-umami.png
[Bootstrap.com]: https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white
[Bootstrap-url]: https://getbootstrap.com
[Javascript_url]: https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E
[JQuery.com]: https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white
[JQuery-url]: https://jquery.com
[JavaScript-url]: https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E
[Symfony-url]: https://img.shields.io/badge/symfony-%23000000.svg?style=for-the-badge&logo=symfony&logoColor=white


