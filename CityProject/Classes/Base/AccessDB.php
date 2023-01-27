<?php
namespace Classes\Base;

use PDO;

class AccessDB
{
    protected PDO $pdoRecords;

    public function __construct()
    {
        try {
            $this->pdoRecords = new PDO('mysql:dbname=homework;host=mysql_db', 'root', 'root');
        } catch (Throwable $t) {
            echo 'database is not accessible';
            throw $t;
        }
    }

    public function execute(string $sql): bool
    {
        $query = $this->pdoRecords->prepare($sql);
        return $query->execute();
    }

    public function query(string $sql, array $data): array|false
    {
        $query1 = $this->pdoRecords->prepare($sql);
        $query1->execute($data);
        return $query1->fetchAll(PDO::FETCH_ASSOC);
    }
}