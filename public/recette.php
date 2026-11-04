<?php
require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/RecettesManager.php';
require_once __DIR__ . '/../src/constants.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$erreur = null;
$recette = null;

if ($id === null || $id === false) {
    $erreur = "Recette invalide.";
} else {
    try {
        $manager = new RecettesManager(new Database());
        $recette = $manager->getRecette($id);

        //recette introuvable, ou carnet d'un autre utilisateur : même message pour ne rien révéler
        if (
            $recette === null
            || (!$recette->getRecetteDeBase() && $recette->getIdUtilisateur() !== ID_UTILISATEUR_DEMO)
        ) {
            $erreur = "Cette recette n'existe pas.";
        }
    } catch (RuntimeException $e) {
        error_log($e->getMessage());
        $erreur = "Une erreur est survenue. Réessayez plus tard.";
    }
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

        <?php if ($recette->getIdDifficulte() !== null) { ?>
            <p>Difficulté : <?= htmlspecialchars(DIFFICULTES[$recette->getIdDifficulte()] ?? '') ?></p>
        <?php } ?>

        <?php if ($recette->getNotes() !== null) { ?>
            <p><?= nl2br(htmlspecialchars($recette->getNotes())) ?></p>
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
