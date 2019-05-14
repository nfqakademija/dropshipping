<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190509205405 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ali_express_item (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(1000) NOT NULL, stock INT NOT NULL, price NUMERIC(10, 2) NOT NULL, description LONGTEXT NOT NULL, product_id BIGINT NOT NULL, INDEX IDX_E0055612A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, ali_express_product_id_id INT DEFAULT NULL, image_link LONGTEXT NOT NULL, INDEX IDX_C53D045FC9D6CC33 (ali_express_product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ali_express_item ADD CONSTRAINT FK_E0055612A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FC9D6CC33 FOREIGN KEY (ali_express_product_id_id) REFERENCES ali_express_item (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE plan_id plan_id INT DEFAULT NULL, CHANGE plan_start_time plan_start_time DATETIME DEFAULT NULL, CHANGE plan_expire_time plan_expire_time DATETIME DEFAULT NULL, CHANGE ebay_country ebay_country INT DEFAULT NULL, CHANGE o_auth_token_refresh o_auth_token_refresh VARCHAR(2555) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FC9D6CC33');
        $this->addSql('DROP TABLE ali_express_item');
        $this->addSql('DROP TABLE image');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE plan_id plan_id INT DEFAULT NULL, CHANGE plan_start_time plan_start_time DATETIME DEFAULT \'NULL\', CHANGE plan_expire_time plan_expire_time DATETIME DEFAULT \'NULL\', CHANGE ebay_country ebay_country INT DEFAULT NULL, CHANGE o_auth_token_refresh o_auth_token_refresh VARCHAR(2555) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
