<?php
require_once __DIR__ . '/../src/constants.php';
require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/RecettesManager.php';

$rubriques = [
    'chef'   => 'Le livre du chef',
    'carnet' => 'Mon carnet',
];

$rubrique = filter_input(INPUT_GET, 'rubrique') ?? 'chef';

$erreur = null;
$recettes = [];
$titrePage = 'Recettes';

if (!array_key_exists($rubrique, $rubriques)) {
    $erreur = "Cette rubrique n'existe pas.";
} else {
    $titrePage = $rubriques[$rubrique];

    try {
        $manager = new RecettesManager(new Database());

        if ($rubrique === 'chef') {
            $recettes = $manager->getRecettesDuChef();
        } else {
            $recettes = $manager->getRecettesDuCarnet(ID_UTILISATEUR_DEMO);
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
    <title><?= htmlspecialchars($titrePage) ?></title> <!--affiche nom selon URL-->
</head>

<body>
    <nav>
        <a href="recettes.php?rubrique=chef">Le livre du chef</a>
        <a href="recettes.php?rubrique=carnet">Mon carnet</a>
    </nav>

    <h1><?= htmlspecialchars($titrePage) ?></h1>
    <p><a href="creerRecette.php">Créer une recette</a></p>

    <?php if ($erreur !== null) { ?>
        <p><?= htmlspecialchars($erreur) ?></p>
    <?php } ?>
    <ul>
        <?php foreach ($recettes as $recette) { ?>
            <li>
                <a href="recette.php?id=<?= (int) $recette->getId() ?>">
                    <?= htmlspecialchars($recette->getTitre()) ?>
                </a>
                <?php if ($recette->getTempsPreparation() !== null) { ?>
                    - <?= (int) $recette->getTempsPreparation() ?> min
                <?php } ?>
                <?php if ($recette->getIdDifficulte() !== null) { ?>
                    - <?= htmlspecialchars(DIFFICULTES[$recette->getIdDifficulte()] ?? 'Inconnue') ?>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
</body>

</html>
