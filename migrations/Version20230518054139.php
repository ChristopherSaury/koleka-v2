<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518054139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country_languages (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_15325619F92F3E70 (country_id), INDEX IDX_1532561982F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE country_languages ADD CONSTRAINT FK_15325619F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE country_languages ADD CONSTRAINT FK_1532561982F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country_languages DROP FOREIGN KEY FK_15325619F92F3E70');
        $this->addSql('ALTER TABLE country_languages DROP FOREIGN KEY FK_1532561982F1BAF4');
        $this->addSql('DROP TABLE country_languages');
    }
}
