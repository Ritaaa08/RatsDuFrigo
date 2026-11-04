<?php
//le contrat du manager

interface ProduitsManagerInterface {
    public function getProduits(int $idUtilisateur): array;
    public function ajouterProduit(Produit $produit, int $idUtilisateur): int;
    public function supprimerProduit(int $id, int $idUtilisateur): bool;
}
