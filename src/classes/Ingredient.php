<?php

class Ingredient {
    private int $id;
    private string $nom;
    private string $categorie;

    public function __construct(int $id, string $nom, string $categorie) {
        $this->id = $id;
        $this->nom = $nom;
        $this->categorie = $categorie;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getCategorie(): string {
        return $this->categorie;
    }
}
