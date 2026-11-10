
<?php

class Database
{
    private string $host = 'localhost';
    private string $dbName = 'agenda_propria';
    private string $username = 'root';
    private string $password = '';
    private string $port = '3306';

    public function connect(): PDO
    {
        try {
            $pdo = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->dbName};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;

        } catch (PDOException $e) {
            throw new Exception(
                'Erro de conexão: ' . $e->getMessage()
            );
        }
    }
}
