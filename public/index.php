<?php

require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/IngredientsManager.php';

$titrePage = "Catalogue d'ingrédients";
$erreur = null;
$ingredients = [];

try {
    $ingredientsManager = new IngredientsManager(new Database());
    $ingredients = $ingredientsManager->getIngredients();
} catch (Exception $e) {
    error_log($e->getMessage());
    $erreur = "Le catalogue est indisponible pour le moment.";
}
?>

<?php require __DIR__ . '/../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../components/header.php'; ?>

    <main>
        <h1>Catalogue d'ingrédients</h1>

        <?php if ($erreur !== null) { ?>
            <p><?= htmlspecialchars($erreur) ?></p>
        <?php } ?>

        <ul>
            <?php foreach ($ingredients as $ingredient) { ?>
                <li><?= htmlspecialchars($ingredient->getNom()) ?> (<?= htmlspecialchars($ingredient->getCategorie()) ?>)</li>
            <?php } ?>
        </ul>
    </main>

    <?php require __DIR__ . '/../components/footer.php'; ?>
</body>

</html>
