<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221030185822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question ADD announce_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E6F5DA3DE FOREIGN KEY (announce_id) REFERENCES announces (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E6F5DA3DE ON question (announce_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EA76ED395 ON question (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E6F5DA3DE');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EA76ED395');
        $this->addSql('DROP INDEX IDX_B6F7494E6F5DA3DE ON question');
        $this->addSql('DROP INDEX IDX_B6F7494EA76ED395 ON question');
        $this->addSql('ALTER TABLE question DROP announce_id, DROP user_id');
    }
}
