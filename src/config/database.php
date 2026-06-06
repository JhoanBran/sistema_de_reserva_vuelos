<?php
class Database {
    private string $host;
    private string $dbName;
    private string $username;
    private string $password;
    private ?PDO $pdo = null;

    public function __construct() {
        $this->host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
        $this->dbName = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'flight_reservation';
        $this->username = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'root';
        $this->password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? '';
    }

    public function getConnection(): PDO {
        if ($this->pdo === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        }

        return $this->pdo;
    }
}
?>