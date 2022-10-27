<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221027171553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE announces CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE announces ADD CONSTRAINT FK_3B879C659D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3B879C659D86650F ON announces (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE announces DROP FOREIGN KEY FK_3B879C659D86650F');
        $this->addSql('DROP INDEX IDX_3B879C659D86650F ON announces');
        $this->addSql('ALTER TABLE announces CHANGE user_id_id user_id INT NOT NULL');
    }
}
