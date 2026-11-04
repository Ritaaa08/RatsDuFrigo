<?php
//le contrat du manager

interface ProduitsManagerInterface {
    public function getProduits(int $idUtilisateur): array;
}
