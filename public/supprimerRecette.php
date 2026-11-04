<?php

require_once __DIR__ . '/../src/constants.php';
require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/RecettesManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id !== null && $id !== false) {
        try {
            $manager = new RecettesManager(new Database());
            $manager->supprimerRecette($id, ID_UTILISATEUR_DEMO);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}

header('Location: recettes.php?rubrique=carnet');
exit;
