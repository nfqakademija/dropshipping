<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190506103017 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD old_ebay_auth VARCHAR(2555) DEFAULT NULL, ADD old_expired_time VARCHAR(2555) DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE plan_id plan_id INT DEFAULT NULL, CHANGE plan_start_time plan_start_time DATETIME DEFAULT NULL, CHANGE plan_expire_time plan_expire_time DATETIME DEFAULT NULL, CHANGE o_auth_token_refresh o_auth_token_refresh VARCHAR(2555) DEFAULT NULL, CHANGE ebay_country ebay_country INT DEFAULT NULL, CHANGE token_expired token_expired VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP old_ebay_auth, DROP old_expired_time, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE plan_id plan_id INT DEFAULT NULL, CHANGE plan_start_time plan_start_time DATETIME DEFAULT \'NULL\', CHANGE plan_expire_time plan_expire_time DATETIME DEFAULT \'NULL\', CHANGE ebay_country ebay_country INT DEFAULT NULL, CHANGE o_auth_token_refresh o_auth_token_refresh VARCHAR(2555) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE token_expired token_expired VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
