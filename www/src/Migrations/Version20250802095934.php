<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250802095934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'profile blocks migration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $tableSql = "CREATE TABLE `profile` ( 
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id int NOT NULL,
                fio_block int DEFAULT 0,
                birthdate_block int DEFAULT 0,
                facult_block int DEFAULT 0,
                sport_block int DEFAULT 0,
                olimpic_block int DEFAULT 0,
                chosen_predmet_block int DEFAULT 0,
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
