<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190421193711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE plan (
                            id INT AUTO_INCREMENT NOT NULL, 
                            name VARCHAR(255) NOT NULL, 
                            price NUMERIC(5, 2) NOT NULL, 
                            monitored_listings INT NOT NULL, 
                            trackings_uploaded INT NOT NULL, 
                            description LONGTEXT DEFAULT NULL, 
                            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('INSERT INTO plan (name, price, monitored_listings, trackings_uploaded) VALUES ("Bronze", 30.00, 250, 100), ("Silver", 40.00, 500, 300)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE plan');
    }
}