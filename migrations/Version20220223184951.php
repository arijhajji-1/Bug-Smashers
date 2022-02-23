<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220223184951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actualite (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, image_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, email VARCHAR(150) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_8F91ABF0FD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis_produit (id INT AUTO_INCREMENT NOT NULL, produit_acheter_id INT DEFAULT NULL, produit_louer_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, email VARCHAR(150) NOT NULL, description VARCHAR(255) NOT NULL, rating SMALLINT NOT NULL, INDEX IDX_2A67C2152AE98C7 (produit_acheter_id), INDEX IDX_2A67C21E48C74F8 (produit_louer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date DATE NOT NULL, heure VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_acheter (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, montage_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, prix NUMERIC(12, 2) NOT NULL, qte INT NOT NULL, image_path VARCHAR(255) NOT NULL, marque VARCHAR(50) NOT NULL, INDEX IDX_A8F51A6112469DE2 (category_id), INDEX IDX_A8F51A61E44D83C3 (montage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_louer (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, nom VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, prix NUMERIC(12, 2) NOT NULL, etat VARCHAR(50) NOT NULL, image_path VARCHAR(255) NOT NULL, marque VARCHAR(50) NOT NULL, dispo TINYINT(1) NOT NULL, INDEX IDX_3D82C8D912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C2152AE98C7 FOREIGN KEY (produit_acheter_id) REFERENCES produit_acheter (id)');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C21E48C74F8 FOREIGN KEY (produit_louer_id) REFERENCES produit_louer (id)');
        $this->addSql('ALTER TABLE produit_acheter ADD CONSTRAINT FK_A8F51A6112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_acheter ADD CONSTRAINT FK_A8F51A61E44D83C3 FOREIGN KEY (montage_id) REFERENCES montage (id)');
        $this->addSql('ALTER TABLE produit_louer ADD CONSTRAINT FK_3D82C8D912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0FD02F13');
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C2152AE98C7');
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C21E48C74F8');
        $this->addSql('DROP TABLE actualite');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE avis_produit');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE produit_acheter');
        $this->addSql('DROP TABLE produit_louer');
    }
}
