<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309190252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reply (id INT AUTO_INCREMENT NOT NULL, avis_id INT NOT NULL, nom VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_FDA8C6E0197E709F (avis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reply ADD CONSTRAINT FK_FDA8C6E0197E709F FOREIGN KEY (avis_id) REFERENCES avis (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCAFFB3979 FOREIGN KEY (publications_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE montage ADD email VARCHAR(255) NOT NULL, ADD iduser INT NOT NULL');
        $this->addSql('ALTER TABLE reparation ADD email VARCHAR(255) NOT NULL, ADD telephone VARCHAR(20) NOT NULL, ADD iduser INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE telephone telephone VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reply');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC67B3B43D');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCAFFB3979');
        $this->addSql('ALTER TABLE montage DROP email, DROP iduser');
        $this->addSql('ALTER TABLE reparation DROP email, DROP telephone, DROP iduser');
        $this->addSql('ALTER TABLE user CHANGE telephone telephone INT NOT NULL');
    }
}
