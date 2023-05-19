<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518043304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country_languages DROP FOREIGN KEY FK_B960E64082F1BAF4');
        $this->addSql('ALTER TABLE country_languages DROP FOREIGN KEY FK_B960E640F92F3E70');
        $this->addSql('DROP TABLE country_languages');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country_languages (country_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_B960E64082F1BAF4 (language_id), INDEX IDX_B960E640F92F3E70 (country_id), PRIMARY KEY(country_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE country_languages ADD CONSTRAINT FK_B960E64082F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE country_languages ADD CONSTRAINT FK_B960E640F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
