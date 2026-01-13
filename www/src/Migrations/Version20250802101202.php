<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250802101202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Faculty table migration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $tableSql = "CREATE TABLE `facultative_predmet` ( 
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            ) ENGINE=InnoDB  CHARACTER  SET utf8mb4 COLLATE utf8mb4_unicode_ci; 
            ";
        $this->addSql($tableSql);
        $this->addSql("INSERT INTO `facultative_predmet` (name) VALUES ('Физика'), ('Химия'), ('Биология'), ('История'), ('География'), ('Литература'), ('Математика'), ('Информатика');");
        $this->addSql("CREATE TABLE `facultative_user` ( 
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                facultative_predmet_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES user(id),
                FOREIGN KEY (facultative_predmet_id) REFERENCES facultative_predmet(id)
            ) ENGINE=InnoDB  CHARACTER  SET utf8mb4 COLLATE utf8mb4_unicode_ci; 
            ");
        // $this->addSql("INSERT INTO `facul_user` (user_id, facultative_predmet_id) 
        //     SELECT user.id, facultative_predmet.id FROM user, facultative_predmet 
        //     WHERE facultative_predmet.id IN (1, 2, 3, 4, 5, 6, 7, 8) 
        //     AND user.id IN (SELECT id FROM user WHERE role = 'ROLE_STUDENT');");

       
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
