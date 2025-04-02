<?php

// Start the session
session_start();

class Database {
    //private $host = 'localhost'; // Localhost for local development
    private $host = 'mysql'; // Docker container name for MySQL
    private $db = 'phpecommerce';
    private $user = 'admin';
    private $pass = 'admin@123';
    private $charset = 'utf8mb4';
    private $pdo;
    private $error;

    public function __construct() {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new \PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function testConnection() {
        if ($this->pdo) {
            return "Connection successful!";
        } else {
            return "Connection failed!";
        }
    }
}

// Create an instance of the Database class and call the testConnection method
$database = new Database();
echo $database->testConnection();
?>


