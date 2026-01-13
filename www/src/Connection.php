<?php

namespace App;

use PDO;

class Connection
{
    static private $instance;
    private $pdo;

    private function __construct()
    {

    }

    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function pdo(): PDO
    {
        if (is_null($this->pdo)) {
            $this->pdo = new PDO(
                sprintf(
                    'mysql:host=%s;dbname=%s;charset=utf8mb4',
                    $_ENV['DB_HOST'] ?? 'assignment_backend_0825_db',
                    $_ENV['DB_NAME'] ?? 'assignment_backend_0825'
                ),
                $_ENV['DB_USER'] ?? 'root',
                $_ENV['DB_PASS'] ?? 'password',
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                ]
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        return $this->pdo; 
    } 
}


