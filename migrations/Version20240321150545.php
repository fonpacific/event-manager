<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321150545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA771F7E88B ON event (event_id)');
        $this->addSql('ALTER TABLE media__media ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media__media ADD CONSTRAINT FK_5C6DD74E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_5C6DD74E71F7E88B ON media__media (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA771F7E88B');
        $this->addSql('DROP INDEX IDX_3BAE0AA771F7E88B ON event');
        $this->addSql('ALTER TABLE event DROP event_id');
        $this->addSql('ALTER TABLE media__media DROP FOREIGN KEY FK_5C6DD74E71F7E88B');
        $this->addSql('DROP INDEX IDX_5C6DD74E71F7E88B ON media__media');
        $this->addSql('ALTER TABLE media__media DROP event_id');
    }
}
