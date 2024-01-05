<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240104200736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_recipes DROP FOREIGN KEY FK_8B633315FDF2B1FA');
        $this->addSql('ALTER TABLE categories_recipes DROP FOREIGN KEY FK_8B633315A21214B7');
        $this->addSql('DROP INDEX IDX_8B633315FDF2B1FA ON categories_recipes');
        $this->addSql('DROP INDEX `primary` ON categories_recipes');
        $this->addSql('DROP INDEX IDX_8B633315A21214B7 ON categories_recipes');
        $this->addSql('ALTER TABLE categories_recipes ADD categories_id INT NOT NULL, ADD recipes_id INT NOT NULL, DROP category_id, DROP recipe_id');
        $this->addSql('ALTER TABLE categories_recipes ADD CONSTRAINT FK_8B633315FDF2B1FA FOREIGN KEY (recipes_id) REFERENCES recipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_recipes ADD CONSTRAINT FK_8B633315A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_8B633315FDF2B1FA ON categories_recipes (recipes_id)');
        $this->addSql('ALTER TABLE categories_recipes ADD PRIMARY KEY (categories_id, recipes_id)');
        $this->addSql('CREATE INDEX IDX_8B633315A21214B7 ON categories_recipes (categories_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_recipes DROP FOREIGN KEY FK_8B633315A21214B7');
        $this->addSql('ALTER TABLE categories_recipes DROP FOREIGN KEY FK_8B633315FDF2B1FA');
        $this->addSql('DROP INDEX IDX_8B633315A21214B7 ON categories_recipes');
        $this->addSql('DROP INDEX IDX_8B633315FDF2B1FA ON categories_recipes');
        $this->addSql('DROP INDEX `PRIMARY` ON categories_recipes');
        $this->addSql('ALTER TABLE categories_recipes ADD category_id INT NOT NULL, ADD recipe_id INT NOT NULL, DROP categories_id, DROP recipes_id');
        $this->addSql('ALTER TABLE categories_recipes ADD CONSTRAINT FK_8B633315A21214B7 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_recipes ADD CONSTRAINT FK_8B633315FDF2B1FA FOREIGN KEY (recipe_id) REFERENCES recipes (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_8B633315A21214B7 ON categories_recipes (category_id)');
        $this->addSql('CREATE INDEX IDX_8B633315FDF2B1FA ON categories_recipes (recipe_id)');
        $this->addSql('ALTER TABLE categories_recipes ADD PRIMARY KEY (category_id, recipe_id)');
    }
}
