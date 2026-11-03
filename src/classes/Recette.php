<?php

class Recette {
    private ?int $id;
    private string $title;
    private ?string $note;
    private ?string $link;
    private ?int $duration;
    private ?int $person;
    private ?int $difficulty;
    private ?string $creation;
    private int $idUser;
    private array $ingredients = [];

    public function __construct(
        string $title,
        int $idUser,
        ?string $note = null, //proposition de l'IA "= null" --> sans ça, tout est obligatoire
        ?string $link = null,
        ?int $duration = null,
        ?int $person = null,
        ?int $difficulty = null,
        ?string $creation = null,
        ?int $id = null
    ) {
        $this->title = $title;
        $this->idUser = $idUser;
        $this->note = $note;
        $this->link = $link;
        $this->duration = $duration;
        $this->person = $person;
        $this->difficulty = $difficulty;
        $this->creation = $creation;
        $this->id = $id;
    }

    //----------------------------- GETTERS -----------------------------

    public function getId(): ?int {
        return $this->id;
    }
    public function getTitle(): string {
        return $this->title;
    }
    public function getNote(): ?string {
        return $this->note;
    }
    public function getLink(): ?string {
        return $this->link;
    }
    public function getDuration(): ?int {
        return $this->duration;
    }
    public function getPerson(): ?int {
        return $this->person;
    }
    public function getDifficulty(): ?int {
        return $this->difficulty;
    }
    public function getCreation(): ?string {
        return $this->creation;
    }
    public function getIdUser(): int {
        return $this->idUser;
    }
    public function getIngredients(): array {
        return $this->ingredients;
    }

    //----------------------------- SETTERS -----------------------------
    //On en met seulement sur les éléments qui peuvent potentiellement changer après la création

    public function setTitle(string $title): void {
        $this->title = $title;
    }
    public function setNote(?string $note): void {
        $this->note = $note;
    }
    public function setLink(?string $link): void {
        $this->link = $link;
    }
    public function setDuration(?int $duration): void {
        $this->duration = $duration;
    }
    public function setPerson(?int $person): void {
        $this->person = $person;
    }
    public function setDifficulty(?int $difficulty): void {
        $this->difficulty = $difficulty;
    }
    public function setIngredients(array $ingredients): void {
        $this->ingredients = $ingredients;
    }

    //----------------------------- VALIDATION -----------------------------

    public function validate(): array {
        $errors = [];

        //titre
        $title = trim($this->title);
        if ($title === '') {
            $errors[] = "Le titre est obligatoire.";
        } elseif (mb_strlen($title) > 50) {
            $errors[] = "Le titre ne peut pas dépasser 50 caractères.";
        }

        //note
        $note = trim((string) $this->note); //facultatif donc on indique que la note de cette recette est transformée de toute façon en string donc null -> ''. Trim pour enlever les espaces avant et après.
        if (mb_strlen($note) > 500) {
            $errors[] = "Les notes ne peuvent pas dépasser 500 caractères.";
        }

        //lien
        $link = trim((string) $this->link);
        if ($link !== '') {
            $protocole = strtolower((string) parse_url($link, PHP_URL_SCHEME)); //suggestion de l'IA. On récolte la première partie entrée dans le lien pour faire les contrôles de sécurité.
            //parse_url coupe le lien pour récupérer 'https' et met tout en minuscule
            if (mb_strlen($link) > 2048) {
                $errors[] = "Le lien ne peut pas dépasser 2048 caractères.";
            } elseif (!in_array($protocole, ['http', 'https'], true)) { //si il n'y a pas http ou https dans le lien...
                $errors[] = "Le lien doit commencer par http:// ou https://.";
            }
        }

        //règle spéciale : il faut au moins un lien OU une note.
        if ($note === '' && $link === '') {
            $errors[] = "Indiquez au moins des notes ou un lien.";
        }

        //temps preparation
        if ($this->duration !== null && ($this->duration < 1 || $this->duration > 9999)) {
            $errors[] = "Durée invalide : elle doit être comprise entre 1 et 9999 minutes.";
        }

        //nombre de personnes
        if ($this->person !== null && ($this->person < 1 || $this->person > 100)) {
            $errors[] = "Nombre de personnes invalide : il doit être entre 1 et 100.";
        }

        //difficulté
        if ($this->difficulty !== null && !in_array($this->difficulty, [1, 2, 3], true)) {
            $errors[] = "La difficulté choisie n'existe pas.";
        }

        //ingrédients : au moins un, et pas deux fois le même. Chaque ingrédient est un tableau. On a donc un tableau de fiches d'ingrédients.
        if (count($this->ingredients) === 0) {
            $errors[] = "Ajoutez au moins un ingrédient.";
        }
        $dejaVus = []; //liste d'id ingrédients déjà rencontrés
        foreach ($this->ingredients as $ingredient) { //pour chaque fiche...
            $id = $ingredient['id_ingredient'];
            if (in_array($id, $dejaVus, true)) { //"num dans la liste des ingrédients déjà vu ?" Si oui...
                $errors[] = "Un ingrédient ne peut être ajouté qu'une seule fois.";
                break;
            }
            $dejaVus[] = $id; //Si ce n'est pas le cas on ajoute l'ingrédient dans la liste des déjà-vus
        }

        return $errors;
    }
}
