<?php
require_once __DIR__ . '/../src/classes/Recette.php';

$difficultes = [1 => 'Facile', 2 => 'Moyen', 3 => 'Difficile'];

//Données de test
$pates = new Recette('Pâtes tomate', 1, 'Cuire les pâtes...', null, 20, 2, 1, null, 1);
$pates->setIngredients([
    ['id_ingredient' => 1, 'nom' => 'Pâtes', 'quantite' => '200 g'],
    ['id_ingredient' => 2, 'nom' => 'Tomate', 'quantite' => '3'],
]);

$soupe = new Recette('Soupe de potiron', 2, null, 'https://exemple.ch/soupe', null, 2, 1, null, 5);
$soupe->setIngredients([
    ['id_ingredient' => 3, 'nom' => 'Potiron', 'quantite' => null],
]);

$toutes = [1 => $pates, 5 => $soupe];

//Voir l'id dans l'URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$erreur = null;
$recette = null;

if ($id === null || $id === false) {
    $erreur = "Recette invalide.";
} elseif (!array_key_exists($id, $toutes)) {
    $erreur = "Cette recette n'existe pas.";
} else {
    $recette = $toutes[$id];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recette</title>
</head>

<body>
    <?php if ($erreur !== null) { ?>
        <p><?= htmlspecialchars($erreur) ?></p>
    <?php } else { ?>

        <h1><?= htmlspecialchars($recette->getTitre()) ?></h1>

        <?php if ($recette->getTempsPreparation() !== null) { ?>
            <p>Temps : <?= (int) $recette->getTempsPreparation() ?> min</p>
        <?php } ?>

        <?php if ($recette->getNbPersonnes() !== null) { ?>
            <p>Pour <?= (int) $recette->getNbPersonnes() ?> personne(s)</p>
        <?php } ?>

        <p>Difficulté : <?= htmlspecialchars($difficultes[$recette->getIdDifficulte()] ?? '') ?></p>

        <?php if ($recette->getNotes() !== null) { ?>
            <p><?= htmlspecialchars($recette->getNotes()) ?></p>
        <?php } ?>

        <?php if ($recette->getLien() !== null) { ?>
            <p><a href="<?= htmlspecialchars($recette->getLien()) ?>">Voir la recette</a></p>
        <?php } ?>

        <h2>Ingrédients</h2>
        <ul>
            <?php foreach ($recette->getIngredients() as $ingredient) { ?>
                <li>
                    <?= htmlspecialchars($ingredient['nom']) ?>
                    <?php if ($ingredient['quantite'] !== null) { ?>
                        : <?= htmlspecialchars($ingredient['quantite']) ?>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

    <p><a href="recettes.php">Voir les recettes</a></p>
</body>

</html>
