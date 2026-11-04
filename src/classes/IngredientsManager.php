<?php

require_once __DIR__ . '/Manager.php';
require_once __DIR__ . '/Ingredient.php';
require_once __DIR__ . '/../interfaces/IngredientsManagerInterface.php';

class IngredientsManager extends Manager implements IngredientsManagerInterface {
    public function getIngredients(): array {
        $sql = "SELECT i.id, i.nom, c.libelle AS categorie
                FROM ingredients i
                JOIN categories_ingredients c ON c.id = i.id_categorie_ingredient
                ORDER BY c.libelle, i.nom";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $ingredients = [];

        foreach ($stmt->fetchAll() as $ligne) {
            $ingredients[] = new Ingredient($ligne['id'], $ligne['nom'], $ligne['categorie']);
        }

        return $ingredients;
    }
}
