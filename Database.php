<?php
class Database {
    private $pdo;

    public function __construct() {
        $config = include('db_config.php');
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8"; // Исправлено
        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>
