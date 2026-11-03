<?php

class Recette {
    private ?int $id;
    private string $titre;
    private ?string $notes;
    private ?string $lien;
    private ?int $tempsPreparation;
    private ?int $nbPersonnes;
    private ?int $idDifficulte;
    private ?string $dateCreation;
    private int $idUtilisateur;
    private array $ingredients = [];

    public function __construct(
        string $titre,
        int $idUtilisateur,
        ?string $notes = null, //proposition de l'IA "= null" --> sans ça, tout est obligatoire
        ?string $lien = null,
        ?int $tempsPreparation = null,
        ?int $nbPersonnes = null,
        ?int $idDifficulte = null,
        ?string $dateCreation = null,
        ?int $id = null
    ) {
        $this->titre = $titre;
        $this->idUtilisateur = $idUtilisateur;
        $this->notes = $notes;
        $this->lien = $lien;
        $this->tempsPreparation = $tempsPreparation;
        $this->nbPersonnes = $nbPersonnes;
        $this->idDifficulte = $idDifficulte;
        $this->dateCreation = $dateCreation;
        $this->id = $id;
    }

    //----------------------------- GETTERS -----------------------------

    public function getId(): ?int {
        return $this->id;
    }
    public function getTitre(): string {
        return $this->titre;
    }
    public function getNotes(): ?string {
        return $this->notes;
    }
    public function getLien(): ?string {
        return $this->lien;
    }
    public function getTempsPreparation(): ?int {
        return $this->tempsPreparation;
    }
    public function getNbPersonnes(): ?int {
        return $this->nbPersonnes;
    }
    public function getIdDifficulte(): ?int {
        return $this->idDifficulte;
    }
    public function getDateCreation(): ?string {
        return $this->dateCreation;
    }
    public function getIdUtilisateur(): int {
        return $this->idUtilisateur;
    }
    public function getIngredients(): array {
        return $this->ingredients;
    }

    //----------------------------- SETTERS -----------------------------
    //On en met seulement sur les éléments qui peuvent potentiellement changer après la création

    public function setTitre(string $titre): void {
        $this->titre = $titre;
    }
    public function setNotes(?string $notes): void {
        $this->notes = $notes;
    }
    public function setLien(?string $lien): void {
        $this->lien = $lien;
    }
    public function setTempsPreparation(?int $tempsPreparation): void {
        $this->tempsPreparation = $tempsPreparation;
    }
    public function setNbPersonnes(?int $nbPersonnes): void {
        $this->nbPersonnes = $nbPersonnes;
    }
    public function setIdDifficulte(?int $idDifficulte): void {
        $this->idDifficulte = $idDifficulte;
    }
    public function setIngredients(array $ingredients): void {
        $this->ingredients = $ingredients;
    }

    //----------------------------- VALIDATION -----------------------------

    public function valider(): array {
        $erreurs = [];

        //titre
        $titre = trim($this->titre);
        if ($titre === '') {
            $erreurs[] = "Le titre est obligatoire.";
        } elseif (mb_strlen($titre) > 50) {
            $erreurs[] = "Le titre ne peut pas dépasser 50 caractères.";
        }

        //note
        $notes = trim((string) $this->notes); //facultatif donc on indique que la note de cette recette est transformée de toute façon en string donc null -> ''. Trim pour enlever les espaces avant et après.
        if (mb_strlen($notes) > 500) {
            $erreurs[] = "Les notes ne peuvent pas dépasser 500 caractères.";
        }

        //lien
        $lien = trim((string) $this->lien);
        if ($lien !== '') {
            $protocole = strtolower((string) parse_url($lien, PHP_URL_SCHEME)); //suggestion de l'IA. On récolte la première partie entrée dans le lien pour faire les contrôles de sécurité.
            //parse_url coupe le lien pour récupérer 'https' et met tout en minuscule
            if (mb_strlen($lien) > 2048) {
                $erreurs[] = "Le lien ne peut pas dépasser 2048 caractères.";
            } elseif (!in_array($protocole, ['http', 'https'], true)) { //si il n'y a pas http ou https dans le lien...
                $erreurs[] = "Le lien doit commencer par http:// ou https://.";
            }
        }

        //règle spéciale : il faut au moins un lien OU une note.
        if ($notes === '' && $lien === '') {
            $erreurs[] = "Indiquez au moins des notes ou un lien.";
        }

        //temps preparation
        if ($this->tempsPreparation !== null && ($this->tempsPreparation < 1 || $this->tempsPreparation > 9999)) {
            $erreurs[] = "Durée invalide : elle doit être comprise entre 1 et 9999 minutes.";
        }

        //nombre de personnes
        if ($this->nbPersonnes !== null && ($this->nbPersonnes < 1 || $this->nbPersonnes > 100)) {
            $erreurs[] = "Nombre de personnes invalide : il doit être entre 1 et 100.";
        }

        //difficulté
        if ($this->idDifficulte !== null && !in_array($this->idDifficulte, [1, 2, 3], true)) {
            $erreurs[] = "La difficulté choisie n'existe pas.";
        }

        //ingrédients : au moins un, et pas deux fois le même. Chaque ingrédient est un tableau. On a donc un tableau de fiches d'ingrédients.
        if (count($this->ingredients) === 0) {
            $erreurs[] = "Ajoutez au moins un ingrédient.";
        }
        $dejaVus = []; //liste d'id ingrédients déjà rencontrés
        foreach ($this->ingredients as $ingredient) { //pour chaque fiche...
            $id = $ingredient['id_ingredient'];
            if (in_array($id, $dejaVus, true)) { //"num dans la liste des ingrédients déjà vu ?" Si oui...
                $erreurs[] = "Un ingrédient ne peut être ajouté qu'une seule fois.";
                break;
            }
            $dejaVus[] = $id; //Si ce n'est pas le cas on ajoute l'ingrédient dans la liste des déjà-vus
        }

        return $erreurs;
    }
}
