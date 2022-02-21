<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220220231652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparation DROP FOREIGN KEY FK_8FDF219D12469DE2');
        $this->addSql('DROP INDEX IDX_8FDF219D12469DE2 ON reparation');
        $this->addSql('ALTER TABLE reparation ADD category VARCHAR(255) NOT NULL, DROP category_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparation ADD category_id INT NOT NULL, DROP category');
        $this->addSql('ALTER TABLE reparation ADD CONSTRAINT FK_8FDF219D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_8FDF219D12469DE2 ON reparation (category_id)');
    }
}
