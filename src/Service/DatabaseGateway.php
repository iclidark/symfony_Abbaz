<?php

namespace App\Service;

use PDO;

class DatabaseGateway
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listDbs(): array
    {
        $statement = $this->pdo->query("SELECT datname FROM pg_database WHERE datistemplate = false;");
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }
}
