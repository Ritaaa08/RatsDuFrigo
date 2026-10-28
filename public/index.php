<?php
// Identifiants lus dans config/ (hors de public/, donc invisible depuis le web)
$config = parse_ini_file(__DIR__ . '/../config/database.ini');

if ($config === false) {
    die('Fichier config/database.ini manquant : copiez config/database.ini.example.');
}

$dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";

$pdo = new PDO($dsn, $config['username'], $config['password']);

$sql = "SELECT i.nom, c.libelle AS categorie
        FROM ingredients i
        JOIN categories_ingredients c ON c.id = i.id_categorie_ingredient
        ORDER BY c.libelle, i.nom";

$stmt = $pdo->prepare($sql);

$stmt->execute();

$ingredients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rats du Frigo</title>
</head>

<body>
    <h1>Rats du Frigo</h1>

    <h2>Catalogue d'ingrédients</h2>

    <ul>
        <?php foreach ($ingredients as $ingredient) { ?>
            <li><?= htmlspecialchars($ingredient['nom']) ?> (<?= htmlspecialchars($ingredient['categorie']) ?>)</li>
        <?php } ?>
    </ul>
</body>

</html>
