<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209095434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country ALTER slug SET NOT NULL');
        $this->addSql('ALTER TABLE event ALTER slug SET NOT NULL');
        $this->addSql('ALTER TABLE place ALTER slug SET NOT NULL');
        $this->addSql('ALTER TABLE province ALTER slug SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE province ALTER slug DROP NOT NULL');
        $this->addSql('ALTER TABLE place ALTER slug DROP NOT NULL');
        $this->addSql('ALTER TABLE country ALTER slug DROP NOT NULL');
        $this->addSql('ALTER TABLE event ALTER slug DROP NOT NULL');
    }
}
