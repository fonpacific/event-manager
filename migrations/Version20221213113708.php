<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221213113708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE event (id INT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, start_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, max_attendees_number INT DEFAULT NULL, status VARCHAR(255) NOT NULL, registration_start_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, registration_end_date TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7727ACA70 ON event (parent_id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7727ACA70 FOREIGN KEY (parent_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE event_id_seq CASCADE');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7727ACA70');
        $this->addSql('DROP TABLE event');
    }
}
