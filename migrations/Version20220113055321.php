<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220113055321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comentarios (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, posts_id INT DEFAULT NULL, comentario VARCHAR(255) NOT NULL, fecha_publicacion DATETIME NOT NULL, INDEX IDX_F54B3FC0A76ED395 (user_id), INDEX IDX_F54B3FC0D5E258C5 (posts_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, titulo VARCHAR(255) NOT NULL, likes VARCHAR(1000) DEFAULT NULL, foto VARCHAR(255) NOT NULL, fecha_publicacion DATETIME NOT NULL, contenido MEDIUMTEXT NOT NULL, INDEX IDX_885DBAFAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profesion (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_7CBDAD0AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comentarios ADD CONSTRAINT FK_F54B3FC0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comentarios ADD CONSTRAINT FK_F54B3FC0D5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE profesion ADD CONSTRAINT FK_7CBDAD0AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD baneado TINYINT(1) NOT NULL, ADD nombre VARCHAR(255) NOT NULL, ADD is_verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentarios DROP FOREIGN KEY FK_F54B3FC0D5E258C5');
        $this->addSql('DROP TABLE comentarios');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE profesion');
        $this->addSql('ALTER TABLE user DROP baneado, DROP nombre, DROP is_verified');
    }
}
