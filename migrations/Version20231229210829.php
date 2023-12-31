<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231229210829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9FDF2B1FA');
        $this->addSql('DROP INDEX IDX_876E0D9FDF2B1FA ON photos');
        $this->addSql('ALTER TABLE photos CHANGE recipes_id recipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D959D8A214 FOREIGN KEY (recipe_id) REFERENCES recipes (id)');
        $this->addSql('CREATE INDEX IDX_876E0D959D8A214 ON photos (recipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D959D8A214');
        $this->addSql('DROP INDEX IDX_876E0D959D8A214 ON photos');
        $this->addSql('ALTER TABLE photos CHANGE recipe_id recipes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9FDF2B1FA FOREIGN KEY (recipes_id) REFERENCES recipes (id)');
        $this->addSql('CREATE INDEX IDX_876E0D9FDF2B1FA ON photos (recipes_id)');
    }
}
