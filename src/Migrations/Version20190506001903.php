<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190506001903 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(80) NOT NULL, category VARCHAR(90) NOT NULL, price DOUBLE PRECISION NOT NULL, sell_price DOUBLE PRECISION NOT NULL, qty INT NOT NULL, brand VARCHAR(160) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_1F1B251EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_for_import (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(80) NOT NULL, category VARCHAR(90) NOT NULL, price DOUBLE PRECISION NOT NULL, qty INT NOT NULL, brand VARCHAR(160) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD token_expired VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE plan_id plan_id INT DEFAULT NULL, CHANGE plan_start_time plan_start_time DATETIME DEFAULT NULL, CHANGE plan_expire_time plan_expire_time DATETIME DEFAULT NULL, CHANGE o_auth_token_refresh o_auth_token_refresh VARCHAR(2555) DEFAULT NULL, CHANGE ebay_country ebay_country INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_for_import');
        $this->addSql('ALTER TABLE user DROP token_expired, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE plan_id plan_id INT DEFAULT NULL, CHANGE plan_start_time plan_start_time DATETIME DEFAULT \'NULL\', CHANGE plan_expire_time plan_expire_time DATETIME DEFAULT \'NULL\', CHANGE ebay_country ebay_country INT DEFAULT NULL, CHANGE o_auth_token_refresh o_auth_token_refresh VARCHAR(2555) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
