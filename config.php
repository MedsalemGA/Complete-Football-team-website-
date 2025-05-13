<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'football_db';
    private $username = 'root';
    private $password = 'root';
    private $charset = 'utf8mb4';
    private $conn;

    public function connect() {
        if ($this->conn === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            } catch (PDOException $e) {
                error_log("Erreur de connexion à la BDD : " . $e->getMessage());
                die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
            }
        }

        return $this->conn;
    }
}

?>