<?php

interface RecettesManagerInterface {
    public function getRecettesDuChef(): array;
    public function getRecettesDuCarnet(int $idUtilisateur): array;
    public function getRecette(int $id): ?Recette; //? -> si l'id n'existe pas
    public function ajouterRecette(Recette $recette): int;
}
