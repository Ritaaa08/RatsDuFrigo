<?php
// appelle seulement le manager, sans aucun SQL

require_once __DIR__ . '/../src/constants.php';
require_once __DIR__ . '/../src/classes/Database.php';
require_once __DIR__ . '/../src/classes/ProduitsManager.php';

$titrePage = 'Mon frigo';
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

<?php require __DIR__ . '/../components/head.php'; ?>

<body>
    <?php require __DIR__ . '/../components/header.php'; ?>

    <main>
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
    </main>

    <?php require __DIR__ . '/../components/footer.php'; ?>
</body>

</html>
