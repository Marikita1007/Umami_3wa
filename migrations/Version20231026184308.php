<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231026184308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredients (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredients_recipes (ingredients_id INT NOT NULL, recipes_id INT NOT NULL, INDEX IDX_C1E222F13EC4DCE (ingredients_id), INDEX IDX_C1E222F1FDF2B1FA (recipes_id), PRIMARY KEY(ingredients_id, recipes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredients_recipes ADD CONSTRAINT FK_C1E222F13EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredients_recipes ADD CONSTRAINT FK_C1E222F1FDF2B1FA FOREIGN KEY (recipes_id) REFERENCES recipes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredients_recipes DROP FOREIGN KEY FK_C1E222F13EC4DCE');
        $this->addSql('ALTER TABLE ingredients_recipes DROP FOREIGN KEY FK_C1E222F1FDF2B1FA');
        $this->addSql('DROP TABLE ingredients');
        $this->addSql('DROP TABLE ingredients_recipes');
    }
}
