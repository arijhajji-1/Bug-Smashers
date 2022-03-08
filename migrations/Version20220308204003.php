<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220308204003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wishlist (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_9CE12A3167B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist_produit_acheter (wishlist_id INT NOT NULL, produit_acheter_id INT NOT NULL, INDEX IDX_79E2D1FCFB8E54CD (wishlist_id), INDEX IDX_79E2D1FC52AE98C7 (produit_acheter_id), PRIMARY KEY(wishlist_id, produit_acheter_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A3167B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wishlist_produit_acheter ADD CONSTRAINT FK_79E2D1FCFB8E54CD FOREIGN KEY (wishlist_id) REFERENCES wishlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist_produit_acheter ADD CONSTRAINT FK_79E2D1FC52AE98C7 FOREIGN KEY (produit_acheter_id) REFERENCES produit_acheter (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wishlist_produit_acheter DROP FOREIGN KEY FK_79E2D1FCFB8E54CD');
        $this->addSql('DROP TABLE wishlist');
        $this->addSql('DROP TABLE wishlist_produit_acheter');
    }
}
