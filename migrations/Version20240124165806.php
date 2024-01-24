<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124165806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipes_user (recipes_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B6CBB700FDF2B1FA (recipes_id), INDEX IDX_B6CBB700A76ED395 (user_id), PRIMARY KEY(recipes_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipes_user ADD CONSTRAINT FK_B6CBB700FDF2B1FA FOREIGN KEY (recipes_id) REFERENCES recipes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipes_user ADD CONSTRAINT FK_B6CBB700A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_likes DROP FOREIGN KEY FK_AB08B525A76ED395');
        $this->addSql('ALTER TABLE user_likes DROP FOREIGN KEY FK_AB08B52559D8A214');
        $this->addSql('DROP TABLE user_likes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_likes (user_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_AB08B525A76ED395 (user_id), INDEX IDX_AB08B52559D8A214 (recipe_id), PRIMARY KEY(user_id, recipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT FK_AB08B525A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_likes ADD CONSTRAINT FK_AB08B52559D8A214 FOREIGN KEY (recipe_id) REFERENCES recipes (id)');
        $this->addSql('ALTER TABLE recipes_user DROP FOREIGN KEY FK_B6CBB700FDF2B1FA');
        $this->addSql('ALTER TABLE recipes_user DROP FOREIGN KEY FK_B6CBB700A76ED395');
        $this->addSql('DROP TABLE recipes_user');
    }
}
