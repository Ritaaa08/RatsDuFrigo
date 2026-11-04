<?php

class Produit {
    // id du statut « au frigo » dans la table statuts_produit
    public const STATUT_DANS_FRIGO = 1;

    private ?int $id;
    private int $idIngredient;
    private ?string $nomIngredient;
    private int $quantite;
    private string $datePeremption;

    public function __construct(int $idIngredient, int $quantite, string $datePeremption, ?string $nomIngredient = null, ?int $id = null) {
        $this->idIngredient = $idIngredient;
        $this->quantite = $quantite;
        $this->datePeremption = $datePeremption;
        $this->nomIngredient = $nomIngredient;
        $this->id = $id;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getIdIngredient(): int {
        return $this->idIngredient;
    }

    public function getNomIngredient(): ?string {
        return $this->nomIngredient;
    }

    public function getQuantite(): int {
        return $this->quantite;
    }

    public function getDatePeremption(): string {
        return $this->datePeremption;
    }

    public function getJoursRestants(): int {
        $aujourdhui = new DateTime('today');
        $peremption = new DateTime($this->datePeremption);

        return (int) $aujourdhui->diff($peremption)->format('%r%a');
    }

    // Niveaux d'urgence du cahier des charges
    public function getUrgence(): string {
        $jours = $this->getJoursRestants();

        if ($jours < 0) {
            return 'périmé';
        }
        if ($jours <= 2) {
            return 'urgent';
        }
        if ($jours <= 5) {
            return 'bientôt';
        }
        return 'OK';
    }
}
