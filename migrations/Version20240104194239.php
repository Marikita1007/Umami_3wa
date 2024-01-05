<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240104194239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_recipes (categories_id INT NOT NULL, recipes_id INT NOT NULL, INDEX IDX_8B633315A21214B7 (categories_id), INDEX IDX_8B633315FDF2B1FA (recipes_id), PRIMARY KEY(categories_id, recipes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories_recipes ADD CONSTRAINT FK_8B633315A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_recipes ADD CONSTRAINT FK_8B633315FDF2B1FA FOREIGN KEY (recipes_id) REFERENCES recipes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_recipes DROP FOREIGN KEY FK_8B633315A21214B7');
        $this->addSql('ALTER TABLE categories_recipes DROP FOREIGN KEY FK_8B633315FDF2B1FA');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE categories_recipes');
    }
}
