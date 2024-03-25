<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240322090814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD immagine_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA777AAE81 FOREIGN KEY (immagine_id) REFERENCES media__media (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA777AAE81 ON event (immagine_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA777AAE81');
        $this->addSql('DROP INDEX IDX_3BAE0AA777AAE81 ON event');
        $this->addSql('ALTER TABLE event DROP immagine_id');
    }
}
