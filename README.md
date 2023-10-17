# Umami_3wa
This is a recipe sharing site for my school project using Symfony **Version**:5.4

***

## Creating an Entity

To create a new entity in your Symfony project, you can use the `make:entity` command provided by Symfony's MakerBundle. This command simplifies the process of generating a new entity class.

1. Open your terminal and navigate to your Symfony project's root directory.

2. Run the following command to create a new entity:

   ```bash
   php bin/console make:entity

   - Questions asked to create an entity
   - Question 1 : Class name of the entity to create or update (e.g. DeliciousElephant):
   - Question 2 : New property name (press <return> to stop adding fields):
   - Question 3 : Field type (enter ? to see all types) [string]:
   - Question 4 : Can this field be null in the database (nullable) (yes/no) [no]:
   - Question 5 : Add another property? Enter the property name (or press <return> to stop adding fields):
   
3. Once you've provided all the required information, the command will generate the entity class and the corresponding repository class. Edit the entity class manually if it's necessary.

***

### Creating a Migration

To make changes to your database, you can create a migration using the Symfony Console. 

- STEP 1. Open your terminal or command prompt.
- STEP 2. Navigate to your project's root directory where your Symfony application is located.
- STEP 3. Run the following command to create a new migration file:

   ```bash
   php bin/console make:migration

### Executing a Migration

1. After customizing the migration file, you can apply the changes to your database by running the following command:

   ```bash
   php bin/console doctrine:migrations:migrate
   ```
if you prefer shortcut :

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
   
4. If you want to down the migration to execute a specific migration version:

   ```bash
   php bin/console doctrine:migrations:migrate -down 'DoctrineMigrations\Version123456'

***

### Generating Faker Data with Foundry 

You can use Symfony's `bin/console make:factory` command to quickly generate Faker data for your application. This is useful for populating your database with sample data for testing or development purposes. Here's how to use it:

1. Open your terminal or command prompt.

2. Navigate to your Symfony project's root directory.

3. Run the following command to create a new factory class:

   ```bash
   php bin/console make:factory

***

## Installed Packages for Building an API

### jms/serializer-bundle
- **Description**: This package allows you to easily serialize (Turn objects into a specific format (XML, JSON, YAML, ...)) and deserialize data of any complexity.
- **Version**: 5.3.1

### friendsofsymfony/rest-bundle
- **Description**: The `friendsofsymfony/rest-bundle` is a powerful toolset for rapidly developing RESTful APIs with Symfony. It provides various tools and features to simplify the process.
- **Version**: 3.6.0

### symfony/maker-bundle
- **Description**: Symfony Maker Bundle is a handy tool that assists you in creating empty commands, controllers, form classes, tests, and more. It helps eliminate the need for writing boilerplate code, making your development process more efficient.
- **Version**: v1.43.0

#### Installing symfony/maker-bundle (Development)

To install `symfony/maker-bundle` for development purposes, you can use Composer with the `--dev` flag. This package provides various helpful tools for generating code and speeding up development.

        composer require --dev symfony/maker-bundle

### symfony/orm-pack

- **Description**: The `symfony/orm-pack` is a pack that includes the Doctrine Object-Relational Mapping (ORM) system. It provides the necessary tools for working with databases and managing your data.

[//]: # (- **Version**: M.N.O)

## Additional Information

- Don't forget to update the package versions as needed. Keeping your packages up-to-date is important for security and bug fixes.

- For installation instructions, please refer to the official documentation of each package. You can install these packages using Composer. For example:
  ```bash
  composer require jms/serializer-bundle

## Checking Installed Package Versions

- You can easily verify the installed package versions using the Composer command-line tool. To check the version of a specific package, use the following command:
  ```bash
  composer show package-name  

***
# Generating Fake Data with Symfony

This guide will help you generate fake data using Symfony's Doctrine Fixtures. In this example, we'll use the `php bin/console doctrine:fixtures:load` command with the `--group=recipes` option to load a specific set of fake data related to recipes.

## Prerequisites

Before you start, make sure you have the following installed:

- Symfony Framework
- Doctrine Fixtures Bundle

## Define Your Fixture

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

