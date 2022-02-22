<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222004421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBF347EFB');
        $this->addSql('DROP INDEX IDX_5E9E89CBF347EFB ON location');
        $this->addSql('ALTER TABLE location ADD produit VARCHAR(255) NOT NULL, DROP produit_id');
        $this->addSql('ALTER TABLE produit ADD location VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location ADD produit_id INT DEFAULT NULL, DROP produit');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CBF347EFB ON location (produit_id)');
        $this->addSql('ALTER TABLE produit DROP location');
    }
}
