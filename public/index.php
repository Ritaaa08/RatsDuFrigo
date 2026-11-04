<?php

require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/IngredientsManager.php';

$erreur = null;
$ingredients = [];

try {
    $ingredientsManager = new IngredientsManager(new Database());
    $ingredients = $ingredientsManager->getIngredients();
} catch (Exception $e) {
    $erreur = "Le catalogue est indisponible pour le moment.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rats du Frigo</title>
</head>

<body>
    <h1>Rats du Frigo</h1>

    <h2>Catalogue d'ingrédients</h2>

    <?php if ($erreur !== null) { ?>
        <p><?= htmlspecialchars($erreur) ?></p>
    <?php } ?>

    <ul>
        <?php foreach ($ingredients as $ingredient) { ?>
            <li><?= htmlspecialchars($ingredient->getNom()) ?> (<?= htmlspecialchars($ingredient->getCategorie()) ?>)</li>
        <?php } ?>
    </ul>
</body>

</html>
