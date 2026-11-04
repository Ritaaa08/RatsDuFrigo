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

$titrePage = ($erreur === null) ? $recette->getTitre() : 'Recette'; // titre de l'onglet, utilisé par head.php
?>

<?php require __DIR__ . '/../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../components/header.php'; ?>

    <main>
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

        <?php $rubriqueRetour = ($recette !== null && !$recette->getRecetteDeBase()) ? 'carnet' : 'chef'; ?>
        <p><a href="recettes.php?rubrique=<?= $rubriqueRetour ?>">Voir les recettes</a></p>
    </main>

    <?php require __DIR__ . '/../components/footer.php'; ?>
</body>

</html>
