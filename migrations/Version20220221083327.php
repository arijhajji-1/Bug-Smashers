<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220221083327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD montage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADE44D83C3 FOREIGN KEY (montage_id) REFERENCES montage (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADE44D83C3 ON product (montage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADE44D83C3');
        $this->addSql('DROP INDEX IDX_D34A04ADE44D83C3 ON product');
        $this->addSql('ALTER TABLE product DROP montage_id');
    }
}
