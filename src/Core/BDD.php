<?php

namespace App\Core;

use PDO;
use stdClass;

class BDD
{
    /**
     * @var PDO
     */
    private $pdo = null;

    /**
     * @var bool
     */
    private $bdd_exist = false;

    /**
     * @var stdClass
     */
    private $config;

    /**
     * BDD constructor.
     * @param ApplicationContext $context
     */
    public function __construct($context)
    {
        $this->config = $context->getConfig()->getDatabaseConfig();

        $this->__createIfNotExistDatabaseEnvironment($context);

        if($this->bdd_exist)
            $this->pdo = new PDO("mysql:dbname={$this->config->database};host={$this->config->host};port={$this->config->port}", $this->config->user, $this->config->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
    }

    /**
     * @return PDO|null
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * @param ApplicationContext $context
     */
    private function __createIfNotExistDatabaseEnvironment(ApplicationContext $context) {
        $pdo = new PDO("mysql:host={$this->config->host};port={$this->config->port}", $this->config->user, $this->config->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $query = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :database");
        $query->bindValue("database", $this->config->database);
        $query->execute();

        if(!$query->fetch()) {
            if($context->getConfig()->getDatabaseConfig()->create_if_not_exist) {
                $this->__createDatabase($pdo);
                $this->__createTablePetImages($pdo);
                $this->__createTablePets($pdo);
                $this->__createTableUsers($pdo);

                $pass = $context->auth()->hashPassword("demo");
                $res = $pdo->query("INSERT INTO users (name, email, password) VALUES ('demo', 'demo@demo.com', '$pass')");

                if(!$res) {
                    die("Error: Create user demo failed.");
                }

                $context->request()->flash()->push("success", "Database created successfully [user: demo@demo.com, pass: demo]");
                $this->bdd_exist = true;
            } else {
                $context->request()->flash()->push("warnings", "The database does not exist, you must create it manually or activate the configuration option: database:create_if_not_exist.");
            }

            $pdo = null;
        } else {
            $this->bdd_exist = true;
        }
    }

    /**
     * @param PDO $pdo
     */
    private function __createDatabase($pdo) {
        $res = $pdo->query("CREATE DATABASE IF NOT EXISTS {$this->config->database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

        if(!$res) {
            die("Error: Database creation error, check if you have access rights.");
        }

        // Select Database
        $res = $pdo->query("use {$this->config->database}");

        if(!$res) {
            die("Error: Select Database failed.");
        }
    }

    /**
     * @param PDO $pdo
     */
    private function __createTableUsers($pdo) {
        $res = $pdo->query("CREATE TABLE IF NOT EXISTS users ( 
                id   INT AUTO_INCREMENT,
                name  VARCHAR(100) NOT NULL, 
                email VARCHAR(100) NOT NULL, 
                password LONGTEXT NOT NULL,
                PRIMARY KEY(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        if(!$res) {
            die("Error: Create table users failed.");
        }
    }

    /**
     * @param PDO $pdo
     */
    private function __createTablePets($pdo) {
        $res = $pdo->query("CREATE TABLE IF NOT EXISTS pets ( 
                id   INT AUTO_INCREMENT,
                user_id INT NOT NULL, 
                name  VARCHAR(100) NOT NULL, 
                type VARCHAR(100) NOT NULL,
                race VARCHAR(100) NOT NULL,
                PRIMARY KEY(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        if(!$res) {
            die("Error: Create table pets failed.");
        }
    }

    /**
     * @param PDO $pdo
     */
    private function __createTablePetImages($pdo) {
        $res = $pdo->query("CREATE TABLE IF NOT EXISTS pet_images ( 
                id   INT AUTO_INCREMENT,
                pet_id INT NOT NULL, 
                name  VARCHAR(100) NOT NULL, 
                web_uri LONGTEXT NOT NULL,
                PRIMARY KEY(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        if(!$res) {
            die("Error: Create table pet_images failed.");
        }
    }
}
