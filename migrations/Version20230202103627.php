<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202103627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD province_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649E946114A FOREIGN KEY (province_id) REFERENCES province (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649F92F3E70 ON "user" (country_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E946114A ON "user" (province_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649F92F3E70');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649E946114A');
        $this->addSql('DROP INDEX IDX_8D93D649F92F3E70');
        $this->addSql('DROP INDEX IDX_8D93D649E946114A');
        $this->addSql('ALTER TABLE "user" DROP country_id');
        $this->addSql('ALTER TABLE "user" DROP province_id');
    }
}
