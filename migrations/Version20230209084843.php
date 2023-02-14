<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209084843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE place ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE province ADD slug VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place DROP slug');
        $this->addSql('ALTER TABLE event DROP slug');
        $this->addSql('ALTER TABLE province DROP slug');
        $this->addSql('ALTER TABLE country DROP slug');
    }
}
