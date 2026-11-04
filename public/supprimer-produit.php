<?php

require_once __DIR__ . '/../src/constants.php';
require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/ProduitsManager.php';

// Supprimer modifie la base : seulement en POST, jamais avec un simple lien
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id !== null && $id !== false) {
        try {
            $produitsManager = new ProduitsManager(new Database());
            $produitsManager->supprimerProduit($id, ID_UTILISATEUR_DEMO);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}

header('Location: frigo.php');
exit;
