<?php
require_once __DIR__ . '/../src/classes/Recette.php';

$rubriques = [ //suggestion de l'IA pour basculer entre les deux pages qui ont la même organisation. On y donne les valeurs acceptées dans l'URL
    'chef'   => 'Le livre du chef',
    'carnet' => 'Mon carnet',
];

$rubrique = filter_input(INPUT_GET, 'rubrique') ?? 'chef'; //rubrique livre du chef par défaut

$difficultes = [1 => 'Facile', 2 => 'Moyen', 3 => 'Difficile']; //temporaire : pas encore de manager

$erreur = null;
$recettes = [];
$titrePage = 'Recettes';

if (!array_key_exists($rubrique, $rubriques)) { //suggestion IA -> vérifier si la rubrique existe dans le tableau précédent 
    $erreur = "Cette rubrique n'existe pas.";
} else {
    $titrePage = $rubriques[$rubrique];

    if ($rubrique === 'chef') {
        //données de test
        $recettes = [
            new Recette('Pâtes tomate', 1, 'Cuire les pâtes...', null, 20, 2, 1, null, 1),
            new Recette('Pâtes pesto', 1, 'Cuire les pâtes...', null, 10, 3, 2, null, 2),
            new Recette('Pâtes carbonara', 1, 'Cuire les pâtes...', null, 30, 1, 3, null, 3),
        ];
    } else {
        $recettes = [
            new Recette('Gratin de courgettes', 2, 'Mes notes...', null, 45, 4, 2, null, 4),
            new Recette('Soupe de potiron', 2, null, 'https://exemple.ch/soupe', null, 2, 1, null, 5),
        ];
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
                    - <?= htmlspecialchars($difficultes[$recette->getIdDifficulte()] ?? 'Inconnue') ?>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
    <?php ?>
</body>

</html>
