<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309130307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD telephone INT NOT NULL, ADD iduser INT NOT NULL');
        $this->addSql('ALTER TABLE facture ADD commande_id INT NOT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641082EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_FE86641082EA2E54 ON facture (commande_id)');
        $this->addSql('ALTER TABLE location ADD iduser INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP telephone, DROP iduser');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641082EA2E54');
        $this->addSql('DROP INDEX IDX_FE86641082EA2E54 ON facture');
        $this->addSql('ALTER TABLE facture DROP commande_id');
        $this->addSql('ALTER TABLE location DROP iduser');
    }
}
