<?php
require_once __DIR__ . '/../src/constants.php';
require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/RecettesManager.php';
require_once __DIR__ . '/../src/classes/IngredientsManager.php';

$erreurs = [];
$titrePage = 'Créer une recette'; // titre de l'onglet, utilisé par head.php

// Valeurs affichées dans le formulaire (vides au départ)
$titre = '';
$notes = '';
$lien = '';
$temps = '';
$personnes = '';
$difficulte = '';
$idsIngredients = ['', '', ''];
$quantites = ['', '', ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //lire et nettoyer
    $titre = trim((string) filter_input(INPUT_POST, 'titre'));
    $notes = trim((string) filter_input(INPUT_POST, 'notes'));
    $lien = trim((string) filter_input(INPUT_POST, 'lien'));
    $temps = trim((string) filter_input(INPUT_POST, 'temps'));
    $personnes = trim((string) filter_input(INPUT_POST, 'personnes'));
    $difficulte = trim((string) filter_input(INPUT_POST, 'difficulte'));
    $idsIngredients = filter_input(INPUT_POST, 'id_ingredient', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?: [];
    $quantites = filter_input(INPUT_POST, 'quantite', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?: [];

    //gestion des champs vides
    $notesBdd = $notes === '' ? null : $notes;
    $lienBdd = $lien === '' ? null : $lien;
    $tempsBdd = $temps === '' ? null : (int) $temps;
    $personnesBdd = $personnes === '' ? null : (int) $personnes;
    $difficulteBdd = $difficulte === '' ? null : (int) $difficulte;

    //construire la liste des ingrédients
    $ingredients = [];
    foreach ($idsIngredients as $i => $idIngredient) {
        if ((string) $idIngredient !== '') {
            $quantite = trim((string) ($quantites[$i] ?? ''));
            $ingredients[] = [
                'id_ingredient' => (int) $idIngredient,
                'quantite' => $quantite === '' ? null : $quantite,
            ];
        }
    }

    //création de recette
    $recette = new Recette($titre, ID_UTILISATEUR_DEMO, $notesBdd, $lienBdd, $tempsBdd, $personnesBdd, $difficulteBdd);
    $recette->setIngredients($ingredients);
    $erreurs = $recette->valider();

    //enregistrement de la recette
    if (empty($erreurs)) {
        try {
            $manager = new RecettesManager(new Database());
            $idNouvelle = $manager->ajouterRecette($recette);
            header('Location: recette.php?id=' . $idNouvelle);
            exit;
        } catch (RuntimeException $e) {
            error_log($e->getMessage());
            $erreurs[] = "Une erreur est survenue. Réessayez plus tard.";
        }
    }
}

//menu déroulant de la liste d'ingrédients
$listeIngredients = [];
$erreurListe = null;
try {
    $listeIngredients = (new IngredientsManager(new Database()))->getIngredients();
} catch (Exception $e) {
    error_log($e->getMessage());
    $erreurListe = "La liste des ingrédients est indisponible pour le moment.";
}
?>

<?php require __DIR__ . '/../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../components/header.php'; ?>

    <main>
        <h1>Créer une recette</h1>

        <?php if (!empty($erreurs)) { ?>
            <ul>
                <?php foreach ($erreurs as $erreur) { ?>
                    <li><?= htmlspecialchars($erreur) ?></li>
                <?php } ?>
            </ul>
        <?php } ?>

        <form action="creerRecette.php" method="POST">
            <label for="titre">Titre</label><br>
            <input type="text" id="titre" name="titre" maxlength="50" value="<?= htmlspecialchars($titre) ?>"><br>

            <label for="notes">Notes</label><br>
            <textarea id="notes" name="notes" maxlength="500"><?= htmlspecialchars($notes) ?></textarea><br>

            <label for="lien">Lien</label><br>
            <input type="text" id="lien" name="lien" value="<?= htmlspecialchars($lien) ?>"><br>

            <label for="temps">Temps de préparation (min)</label><br>
            <input type="number" id="temps" name="temps" min="1" value="<?= htmlspecialchars($temps) ?>"><br>

            <label for="personnes">Nombre de personnes</label><br>
            <input type="number" id="personnes" name="personnes" min="1" value="<?= htmlspecialchars($personnes) ?>"><br>

            <label for="difficulte">Difficulté</label><br>
            <select id="difficulte" name="difficulte">
                <option value="">-</option>
                <?php foreach (DIFFICULTES as $idDifficulte => $libelle) { ?>
                    <option value="<?= $idDifficulte ?>" <?= $difficulte === (string) $idDifficulte ? 'selected' : '' ?>><?= htmlspecialchars($libelle) ?></option>
                <?php } ?>
            </select><br>

            <h2>Ingrédients</h2> <!--suggestion de l'IA-->
            <?php if ($erreurListe !== null) { ?>
                <p><?= htmlspecialchars($erreurListe) ?></p>
            <?php } ?>
            <?php for ($i = 0; $i < 3; $i++) { ?> <!--a chaque tour on créé un menu déroulant. Maximum trois ingrédients pour le moment-->
                <select name="id_ingredient[<?= $i ?>]">
                    <option value="">-</option>
                    <?php foreach ($listeIngredients as $ingredient) { ?>
                        <option value="<?= (int) $ingredient->getId() ?>"
                            <?= ($idsIngredients[$i] ?? '') === (string) $ingredient->getId() ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ingredient->getNom()) ?>
                        </option>
                    <?php } ?>
                </select>
                <input type="text" name="quantite[<?= $i ?>]" maxlength="30" placeholder="Quantité"
                    value="<?= htmlspecialchars($quantites[$i] ?? '') ?>"><br>
            <?php } ?>

            <button type="submit">Enregistrer</button>
        </form>

        <p><a href="recettes.php?rubrique=carnet">Retour au carnet</a></p>
    </main>

    <?php require __DIR__ . '/../src/components/footer.php'; ?>
</body>

</html>
