<?php
// appelle seulement le manager, sans aucun SQL

require_once __DIR__ . '/../src/constants.php';
require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/ProduitsManager.php';

$erreur = null;
$produits = [];

try {
    $produitsManager = new ProduitsManager(new Database());
    $produits = $produitsManager->getProduits(ID_UTILISATEUR_DEMO);
} catch (Exception $e) {
    error_log($e->getMessage());
    $erreur = "Le frigo est indisponible pour le moment.";
    // la base est en panne, alors un message simple
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon frigo · Rats du Frigo</title>
</head>

<body>
    <nav>
        <a href="index.php">Catalogue</a>
        <a href="frigo.php">Mon frigo</a>
    </nav>

    <h1>Mon frigo</h1>

    <?php if ($erreur !== null) { ?>
        <p><?= htmlspecialchars($erreur) ?></p>
    <?php } elseif (count($produits) === 0) { ?>
        <p>Ton frigo est vide.</p>
    <?php } ?>

    <ul>
        <?php foreach ($produits as $produit) { ?>
            <li>
                <?= htmlspecialchars($produit->getNomIngredient()) ?>
                × <?= htmlspecialchars($produit->getQuantite()) ?>
                — à consommer avant le <?= htmlspecialchars(date('d.m.Y', strtotime($produit->getDatePeremption()))) ?>
                (<?= htmlspecialchars($produit->getUrgence()) ?>)
            </li>
        <?php } ?>
    </ul>
</body>

</html>
