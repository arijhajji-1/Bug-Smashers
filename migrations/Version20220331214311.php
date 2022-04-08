<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220331214311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_louer ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_louer ADD CONSTRAINT FK_3D82C8D964D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_3D82C8D964D218E ON produit_louer (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_louer DROP FOREIGN KEY FK_3D82C8D964D218E');
        $this->addSql('DROP INDEX IDX_3D82C8D964D218E ON produit_louer');
        $this->addSql('ALTER TABLE produit_louer DROP location_id');
    }
}
