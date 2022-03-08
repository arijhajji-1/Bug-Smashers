<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220308205045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, pourcentage INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_acheter ADD promotion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_acheter ADD CONSTRAINT FK_A8F51A61139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('CREATE INDEX IDX_A8F51A61139DF194 ON produit_acheter (promotion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_acheter DROP FOREIGN KEY FK_A8F51A61139DF194');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP INDEX IDX_A8F51A61139DF194 ON produit_acheter');
        $this->addSql('ALTER TABLE produit_acheter DROP promotion_id');
    }
}
