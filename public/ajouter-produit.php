<?php

require_once __DIR__ . '/../src/constants.php';
require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/IngredientsManager.php';
require_once __DIR__ . '/../src/classes/ProduitsManager.php';

$titrePage = 'Ajouter un produit';
$erreurs = [];
$ingredients = [];

// Valeurs réaffichées dans le formulaire
$idIngredient = '';
$quantite = '1';
$datePeremption = '';

try {
    $database = new Database();
    $ingredientsManager = new IngredientsManager($database);
    $produitsManager = new ProduitsManager($database);
    $ingredients = $ingredientsManager->getIngredients();
} catch (Exception $e) {
    error_log($e->getMessage());
    $erreurs[] = "Le formulaire est indisponible pour le moment.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && count($erreurs) === 0) {
    $idIngredient = trim((string) filter_input(INPUT_POST, 'id_ingredient'));
    $quantite = trim((string) filter_input(INPUT_POST, 'quantite'));
    $datePeremption = trim((string) filter_input(INPUT_POST, 'date_peremption'));

    $produit = new Produit((int) $idIngredient, (int) $quantite, $datePeremption);
    $erreurs = $produit->valider();

    // L'ingrédient doit exister dans la base
    $idsExistants = [];
    foreach ($ingredients as $ingredient) {
        $idsExistants[] = $ingredient->getId();
    }
    if (!in_array((int) $idIngredient, $idsExistants, true)) {
        $erreurs[] = "Choisissez un ingrédient dans la liste.";
    }

    if (count($erreurs) === 0) {
        try {
            $produitsManager->ajouterProduit($produit, ID_UTILISATEUR_DEMO);
            header('Location: frigo.php');
            exit;
        } catch (Exception $e) {
            error_log($e->getMessage());
            $erreurs[] = "Le produit n'a pas pu être ajouté. Réessayez plus tard.";
        }
    }
}
?>

<?php require __DIR__ . '/../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../components/header.php'; ?>

    <main>
        <h1>Ajouter un produit</h1>

        <?php if (count($erreurs) > 0) { ?>
            <ul>
                <?php foreach ($erreurs as $erreur) { ?>
                    <li><?= htmlspecialchars($erreur) ?></li>
                <?php } ?>
            </ul>
        <?php } ?>

        <form action="ajouter-produit.php" method="POST">
            <label for="id_ingredient">Ingrédient</label><br>
            <select id="id_ingredient" name="id_ingredient" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($ingredients as $ingredient) { ?>
                    <option value="<?= htmlspecialchars($ingredient->getId()) ?>" <?= $idIngredient === (string) $ingredient->getId() ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ingredient->getNom()) ?>
                    </option>
                <?php } ?>
            </select><br>

            <label for="quantite">Quantité</label><br>
            <input type="number" id="quantite" name="quantite" min="1" max="999" required value="<?= htmlspecialchars($quantite) ?>"><br>

            <label for="date_peremption">Date de péremption</label><br>
            <input type="date" id="date_peremption" name="date_peremption" required value="<?= htmlspecialchars($datePeremption) ?>"><br>

            <button type="submit">Ajouter</button>
        </form>

        <p><a href="frigo.php">Retour au frigo</a></p>
    </main>

    <?php require __DIR__ . '/../components/footer.php'; ?>
</body>

</html>
