<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119110116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD cover_image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD cover_image_size INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD cover_image_original_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP cover_image_name');
        $this->addSql('ALTER TABLE event DROP cover_image_size');
        $this->addSql('ALTER TABLE event DROP cover_image_original_name');
    }
}
