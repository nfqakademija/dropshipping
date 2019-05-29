<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190529155814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE amazon_image (id INT AUTO_INCREMENT NOT NULL, amazon_product_id_id INT DEFAULT NULL, image_link LONGTEXT NOT NULL, INDEX IDX_244FD7FDFF055E87 (amazon_product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE amazon_image ADD CONSTRAINT FK_244FD7FDFF055E87 FOREIGN KEY (amazon_product_id_id) REFERENCES amazon_item (id)');
        $this->addSql('ALTER TABLE image CHANGE ali_express_product_id_id ali_express_product_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amazon_item ADD images_id INT DEFAULT NULL, CHANGE stock stock VARCHAR(255) DEFAULT NULL, CHANGE price price VARCHAR(255) DEFAULT NULL, CHANGE category category VARCHAR(255) DEFAULT NULL, CHANGE image_url image_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE amazon_item ADD CONSTRAINT FK_63509F55D44F05E5 FOREIGN KEY (images_id) REFERENCES amazon_image (id)');
        $this->addSql('CREATE INDEX IDX_63509F55D44F05E5 ON amazon_item (images_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE plan_id plan_id INT DEFAULT NULL, CHANGE plan_start_time plan_start_time DATETIME DEFAULT NULL, CHANGE plan_expire_time plan_expire_time DATETIME DEFAULT NULL, CHANGE ebay_country ebay_country INT DEFAULT NULL, CHANGE o_auth_token_refresh o_auth_token_refresh VARCHAR(2555) DEFAULT NULL, CHANGE token_expired token_expired VARCHAR(255) DEFAULT NULL, CHANGE old_ebay_auth old_ebay_auth VARCHAR(2555) DEFAULT NULL, CHANGE old_expired_time old_expired_time VARCHAR(2555) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE amazon_item DROP FOREIGN KEY FK_63509F55D44F05E5');
        $this->addSql('DROP TABLE amazon_image');
        $this->addSql('DROP INDEX IDX_63509F55D44F05E5 ON amazon_item');
        $this->addSql('ALTER TABLE amazon_item DROP images_id, CHANGE stock stock VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE price price VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE category category VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE image_url image_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE image CHANGE ali_express_product_id_id ali_express_product_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE plan_id plan_id INT DEFAULT NULL, CHANGE plan_start_time plan_start_time DATETIME DEFAULT \'NULL\', CHANGE plan_expire_time plan_expire_time DATETIME DEFAULT \'NULL\', CHANGE ebay_country ebay_country INT DEFAULT NULL, CHANGE o_auth_token_refresh o_auth_token_refresh VARCHAR(2555) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE token_expired token_expired VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE old_ebay_auth old_ebay_auth VARCHAR(2555) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE old_expired_time old_expired_time VARCHAR(2555) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
