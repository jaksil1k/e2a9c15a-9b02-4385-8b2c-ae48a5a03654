<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250801103203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'user table migration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $tableSql = "CREATE TABLE `user` ( 
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                surname VARCHAR(100) NOT NULL,
                lastname VARCHAR(100) NOT NULL,
                email VARCHAR(150) NULL ,
                birthdate DATE DEFAULT NULL,
                class int DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            ) ENGINE=InnoDB  CHARACTER  SET utf8mb4 COLLATE utf8mb4_unicode_ci; 
            ";
        $this->addSql($tableSql);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
