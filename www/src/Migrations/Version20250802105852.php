<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250802105852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'olimpic table migration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $tableSql = "CREATE TABLE `olimpic_predmet` ( 
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            ) ENGINE=InnoDB  CHARACTER  SET utf8mb4 COLLATE utf8mb4_unicode_ci; 
            ";
        $this->addSql($tableSql);
        $this->addSql("INSERT INTO `olimpic_predmet` (name) VALUES ('Математика'), ('Английский язык'), ('Биогогия'), ('География'), ('История'), ('Физика'), ('Химия'), ('Литература');");
        $this->addSql("CREATE TABLE `olimpic_user` ( 
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                olimpic_predmet_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES user(id),
                FOREIGN KEY (olimpic_predmet_id) REFERENCES olimpic_predmet(id)
            ) ENGINE=InnoDB  CHARACTER  SET utf8mb4 COLLATE utf8mb4_unicode_ci; 
            ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
