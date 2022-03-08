<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220223190754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis_produit (id INT AUTO_INCREMENT NOT NULL, produit_acheter_id INT DEFAULT NULL, produit_louer_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, email VARCHAR(150) NOT NULL, description VARCHAR(255) NOT NULL, rating SMALLINT NOT NULL, INDEX IDX_2A67C2152AE98C7 (produit_acheter_id), INDEX IDX_2A67C21E48C74F8 (produit_louer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis_reparation (id INT AUTO_INCREMENT NOT NULL, idrep_id INT NOT NULL, description VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_631A86A43078E446 (idrep_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, paiment VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, date_fact DATE NOT NULL, remise DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_commande (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_3170B74B82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE montage (id INT AUTO_INCREMENT NOT NULL, processeur VARCHAR(255) NOT NULL, carte_graphique VARCHAR(255) NOT NULL, carte_mere VARCHAR(255) NOT NULL, disque_systeme VARCHAR(255) NOT NULL, boitier VARCHAR(255) NOT NULL, stockage_supp VARCHAR(255) NOT NULL, montant INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_acheter (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, montage_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, prix NUMERIC(12, 2) NOT NULL, qte INT NOT NULL, image_path VARCHAR(255) NOT NULL, marque VARCHAR(50) NOT NULL, INDEX IDX_A8F51A6112469DE2 (category_id), INDEX IDX_A8F51A61E44D83C3 (montage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_louer (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, nom VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, prix NUMERIC(12, 2) NOT NULL, etat VARCHAR(50) NOT NULL, image_path VARCHAR(255) NOT NULL, marque VARCHAR(50) NOT NULL, dispo TINYINT(1) NOT NULL, INDEX IDX_3D82C8D912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reparation (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, reserver DATETIME DEFAULT NULL, category VARCHAR(255) NOT NULL, etat VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) NOT NULL, telephone INT NOT NULL, cin INT NOT NULL, date_naissance DATETIME DEFAULT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C2152AE98C7 FOREIGN KEY (produit_acheter_id) REFERENCES produit_acheter (id)');
        $this->addSql('ALTER TABLE avis_produit ADD CONSTRAINT FK_2A67C21E48C74F8 FOREIGN KEY (produit_louer_id) REFERENCES produit_louer (id)');
        $this->addSql('ALTER TABLE avis_reparation ADD CONSTRAINT FK_631A86A43078E446 FOREIGN KEY (idrep_id) REFERENCES reparation (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE produit_acheter ADD CONSTRAINT FK_A8F51A6112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_acheter ADD CONSTRAINT FK_A8F51A61E44D83C3 FOREIGN KEY (montage_id) REFERENCES montage (id)');
        $this->addSql('ALTER TABLE produit_louer ADD CONSTRAINT FK_3D82C8D912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_acheter DROP FOREIGN KEY FK_A8F51A6112469DE2');
        $this->addSql('ALTER TABLE produit_louer DROP FOREIGN KEY FK_3D82C8D912469DE2');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('ALTER TABLE produit_acheter DROP FOREIGN KEY FK_A8F51A61E44D83C3');
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C2152AE98C7');
        $this->addSql('ALTER TABLE avis_produit DROP FOREIGN KEY FK_2A67C21E48C74F8');
        $this->addSql('ALTER TABLE avis_reparation DROP FOREIGN KEY FK_631A86A43078E446');
        $this->addSql('DROP TABLE avis_produit');
        $this->addSql('DROP TABLE avis_reparation');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE montage');
        $this->addSql('DROP TABLE produit_acheter');
        $this->addSql('DROP TABLE produit_louer');
        $this->addSql('DROP TABLE reparation');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE actualite CHANGE titre titre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_name image_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE avis CHANGE nom nom VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(150) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE evenement CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE heure heure VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_name image_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
