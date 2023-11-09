<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231104190730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes CHANGE description description LONGTEXT NOT NULL, CHANGE instructions instructions LONGTEXT NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE cook_time cook_time INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipes CHANGE description description LONGTEXT DEFAULT NULL, CHANGE instructions instructions LONGTEXT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE cook_time cook_time INT DEFAULT NULL');
    }
}
