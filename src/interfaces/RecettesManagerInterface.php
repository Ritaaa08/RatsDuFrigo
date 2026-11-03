<?php

interface RecettesManagerInterface {
    public function getRecettes(): array;
    public function getRecette(int $id): ?Recette; //? -> si l'id n'existe pas
}
