<?php

require_once __DIR__ . '/Database.php';

abstract class Manager {
    protected PDO $pdo;

    public function __construct(Database $database) {
        $this->pdo = $database->getPdo();
    }
}
