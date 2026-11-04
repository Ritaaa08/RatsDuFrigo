<?php
require_once __DIR__ . '/../src/constants.php';
require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/RecettesManager.php';
//require_once __DIR__ . '/../src/classes/IngredientsManager.php';

$erreurs = [];

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
    $titre = trim($_POST['titre'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $lien = trim($_POST['lien'] ?? '');
    $temps = trim($_POST['temps'] ?? '');
    $personnes = trim($_POST['personnes'] ?? '');
    $difficulte = trim($_POST['difficulte'] ?? '');
    $idsIngredients = $_POST['id_ingredient'] ?? [];
    $quantites = $_POST['quantite'] ?? [];

    //gestion des champs vides
    $notesBdd = $notes === '' ? null : $notes;
    $lienBdd = $lien === '' ? null : $lien;
    $tempsBdd = $temps === '' ? null : (int) $temps;
    $personnesBdd = $personnes === '' ? null : (int) $personnes;
    $difficulteBdd = $difficulte === '' ? null : (int) $difficulte;

    //construire la liste des ingrédients
    $ingredients = [];
    foreach ($idsIngredients as $i => $idIngredient) {
        if ($idIngredient !== '') {
            $quantite = trim($quantites[$i] ?? '');
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
try {
    //$listeIngredients = (new IngredientsManager(new Database()))->getIngredients();
} catch (RuntimeException $e) {
    error_log($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une recette</title>
</head>

<body>
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
</body>

</html>
