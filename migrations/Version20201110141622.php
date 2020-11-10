<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201110141622 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, opening_date DATE NOT NULL, type VARCHAR(100) NOT NULL, INDEX IDX_7D3656A4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, type VARCHAR(50) NOT NULL, registering_date DATETIME NOT NULL, label VARCHAR(100) DEFAULT NULL, INDEX IDX_1981A66D9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(50) NOT NULL, firstname VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, city_code VARCHAR(20) NOT NULL, sex VARCHAR(1) NOT NULL, birth_date DATE NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D9B6B5FBA');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A4A76ED395');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE user');
    }
}
