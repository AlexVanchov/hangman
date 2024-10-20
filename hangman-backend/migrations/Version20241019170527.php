<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019170527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_attempts (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, letter VARCHAR(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_14831842E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games (id INT AUTO_INCREMENT NOT NULL, word_id INT NOT NULL, win TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_FF232B31E357438D (word_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE words (id INT AUTO_INCREMENT NOT NULL, word VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_attempts ADD CONSTRAINT FK_14831842E48FD905 FOREIGN KEY (game_id) REFERENCES games (id)');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B31E357438D FOREIGN KEY (word_id) REFERENCES words (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_attempts DROP FOREIGN KEY FK_14831842E48FD905');
        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B31E357438D');
        $this->addSql('DROP TABLE game_attempts');
        $this->addSql('DROP TABLE games');
        $this->addSql('DROP TABLE words');
    }
}
