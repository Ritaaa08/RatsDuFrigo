<?php

class Database {
    private const FICHIER_CONFIGURATION = __DIR__ . '/../../config/database.ini';

    private PDO $pdo;

    public function __construct() {
        if (!is_file(self::FICHIER_CONFIGURATION)) {
            throw new RuntimeException('Fichier de configuration introuvable.');
        }

        $config = parse_ini_file(self::FICHIER_CONFIGURATION);

        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Le détail de l'erreur va dans les logs du serveur, jamais à l'écran
            error_log($e->getMessage());
            throw new RuntimeException('Connexion à la base de données impossible.');
        }
    }

    public function getPdo(): PDO {
        return $this->pdo;
    }
}
