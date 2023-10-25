<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231022175204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes ADD difficulty_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipes ADD CONSTRAINT FK_A369E2B5FCFA9DAE FOREIGN KEY (difficulty_id) REFERENCES difficulty (id)');
        $this->addSql('CREATE INDEX IDX_A369E2B5FCFA9DAE ON recipes (difficulty_id)');

        // Update existing data to set default value (e.g., 1)
        $this->addSql('UPDATE recipes SET difficulty_id = 1 WHERE difficulty_id IS 0');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes DROP FOREIGN KEY FK_A369E2B5FCFA9DAE');
        $this->addSql('DROP INDEX IDX_A369E2B5FCFA9DAE ON recipes');

        // Set difficulty_id to NULL for existing data
        $this->addSql('UPDATE recipes SET difficulty_id = NULL WHERE difficulty_id = 1');

        $this->addSql('ALTER TABLE recipes DROP difficulty_id');
    }
}
