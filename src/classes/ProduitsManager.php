<?php
// il va chercher en base les produits de cet utilisateur qui sont encore au frigo, du plus urgent au moins urgent

require_once __DIR__ . '/Manager.php';
require_once __DIR__ . '/Produit.php';
require_once __DIR__ . '/../interfaces/ProduitsManagerInterface.php';

//grace au filtre sur id_utilisateur, zoé ne voit jamais le frigo de léo
class ProduitsManager extends Manager implements ProduitsManagerInterface {
    public function getProduits(int $idUtilisateur): array {
        $sql = "SELECT p.id, p.id_ingredient, i.nom, p.quantite, p.date_peremption
                FROM produits p
                JOIN ingredients i ON i.id = p.id_ingredient
                WHERE p.id_utilisateur = :idUtilisateur
                  AND p.id_statut_produit = :idStatut
                ORDER BY p.date_peremption, i.nom";

        // utilise prepare + bindValue, ce qui protège de l’injection SQL
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
        $stmt->bindValue(':idStatut', Produit::STATUT_DANS_FRIGO, PDO::PARAM_INT);
        $stmt->execute();

        $produits = [];

        foreach ($stmt->fetchAll() as $ligne) {
            $produits[] = new Produit($ligne['id_ingredient'], $ligne['quantite'], $ligne['date_peremption'], $ligne['nom'], $ligne['id']);
        }

        return $produits;
    }
}
