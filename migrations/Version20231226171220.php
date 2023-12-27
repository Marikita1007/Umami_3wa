<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231226171220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes ADD cuisine_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipes ADD CONSTRAINT FK_A369E2B5ED4BAC14 FOREIGN KEY (cuisine_id) REFERENCES cuisines (id)');
        $this->addSql('CREATE INDEX IDX_A369E2B5ED4BAC14 ON recipes (cuisine_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes DROP FOREIGN KEY FK_A369E2B5ED4BAC14');
        $this->addSql('DROP INDEX IDX_A369E2B5ED4BAC14 ON recipes');
        $this->addSql('ALTER TABLE recipes DROP cuisine_id');
    }
}
