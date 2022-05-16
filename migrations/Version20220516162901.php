<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516162901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation ADD id_commande_id INT DEFAULT NULL, DROP id_commande');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064049AF8E3A3 FOREIGN KEY (id_commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CE6064049AF8E3A3 ON reclamation (id_commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064049AF8E3A3');
        $this->addSql('DROP INDEX UNIQ_CE6064049AF8E3A3 ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD id_commande VARCHAR(50) NOT NULL, DROP id_commande_id');
    }
}
