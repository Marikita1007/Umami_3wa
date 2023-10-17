<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231015171200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes ADD instructions LONGTEXT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD image VARCHAR(255) DEFAULT NULL, ADD prep_time INT DEFAULT NULL, ADD servings INT DEFAULT NULL, ADD cook_time INT DEFAULT NULL, ADD calories INT DEFAULT NULL, ADD difficulty VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes DROP instructions, DROP created_at, DROP updated_at, DROP image, DROP prep_time, DROP servings, DROP cook_time, DROP calories, DROP difficulty');
    }
}
