<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222175656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis_reparation DROP FOREIGN KEY FK_631A86A497934BA');
        $this->addSql('DROP INDEX UNIQ_631A86A497934BA ON avis_reparation');
        $this->addSql('ALTER TABLE avis_reparation CHANGE reparation_id idrep_id INT NOT NULL');
        $this->addSql('ALTER TABLE avis_reparation ADD CONSTRAINT FK_631A86A43078E446 FOREIGN KEY (idrep_id) REFERENCES reparation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_631A86A43078E446 ON avis_reparation (idrep_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis_reparation DROP FOREIGN KEY FK_631A86A43078E446');
        $this->addSql('DROP INDEX UNIQ_631A86A43078E446 ON avis_reparation');
        $this->addSql('ALTER TABLE avis_reparation CHANGE idrep_id reparation_id INT NOT NULL');
        $this->addSql('ALTER TABLE avis_reparation ADD CONSTRAINT FK_631A86A497934BA FOREIGN KEY (reparation_id) REFERENCES reparation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_631A86A497934BA ON avis_reparation (reparation_id)');
    }
}
