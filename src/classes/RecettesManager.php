<?php

require_once __DIR__ . '/Manager.php';
require_once __DIR__ . '/Recette.php';
require_once __DIR__ . '/../interfaces/RecettesManagerInterface.php';

class RecettesManager extends Manager implements RecettesManagerInterface {

    //transformer ligne bdd en objet Recette
    private function creerRecette(array $ligne): Recette {
        $recette = new Recette(
            $ligne['titre'],
            $ligne['id_utilisateur'],
            $ligne['notes'],
            $ligne['lien'],
            $ligne['temps_preparation'],
            $ligne['nb_personnes'],
            $ligne['id_difficulte'],
            $ligne['date_creation'],
            $ligne['id']
        );
        $recette->setRecetteDeBase((bool) $ligne['recette_de_base']);

        return $recette;
    }

    public function getRecettesDuChef(): array {
        $sql = "SELECT * FROM recettes WHERE recette_de_base = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $lignes = $stmt->fetchAll();

        $recettes = [];

        foreach ($lignes as $ligne) {
            $recettes[] = $this->creerRecette($ligne);
        }
        return $recettes;
    }

    public function getRecettesDuCarnet(int $idUtilisateur): array {
        $sql = "SELECT * FROM recettes
                WHERE id_utilisateur = :idUtilisateur
                AND recette_de_base = 0";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idUtilisateur', $idUtilisateur);
        $stmt->execute();

        $lignes = $stmt->fetchAll();

        $recettes = [];

        foreach ($lignes as $ligne) {
            $recettes[] = $this->creerRecette($ligne);
        }
        return $recettes;
    }

    public function getRecette(int $id): ?Recette {
        //Chercher recette
        $sql = "SELECT * FROM recettes WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $ligne = $stmt->fetch();

        //Si elle n'existe pas
        if ($ligne === false) {
            return null;
        }

        //Transformer la ligne en objet Recette
        $recette = $this->creerRecette($ligne);

        //Chercher les ingrédients
        $sql = "SELECT i.id AS id_ingredient, i.nom, ri.quantite
            FROM recettes_ingredients ri
            JOIN ingredients i ON i.id = ri.id_ingredient
            WHERE ri.id_recette = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        //Ranger dans recette
        $recette->setIngredients($stmt->fetchAll());

        return $recette;
    }

    public function ajouterRecette(Recette $recette): int {
        $this->pdo->beginTransaction();

        try {
            $sql = "INSERT INTO recettes (
                titre,
                notes,
                lien,
                temps_preparation,
                nb_personnes,
                id_utilisateur,
                id_difficulte
            ) VALUES (
                :titre,
                :notes,
                :lien,
                :tempsPreparation,
                :nbPersonnes,
                :idUtilisateur,
                :idDifficulte
            )";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':titre', $recette->getTitre());
            $stmt->bindValue(':notes', $recette->getNotes());
            $stmt->bindValue(':lien', $recette->getLien());
            $stmt->bindValue(':tempsPreparation', $recette->getTempsPreparation());
            $stmt->bindValue(':nbPersonnes', $recette->getNbPersonnes());
            $stmt->bindValue(':idUtilisateur', $recette->getIdUtilisateur());
            $stmt->bindValue(':idDifficulte', $recette->getIdDifficulte());

            $stmt->execute();

            $idRecette = (int) $this->pdo->lastInsertId();

            $sql = "INSERT INTO recettes_ingredients (
                id_recette,
                id_ingredient,
                quantite
            ) VALUES (
                :idRecette,
                :idIngredient,
                :quantite
            )";

            $stmt = $this->pdo->prepare($sql);

            foreach ($recette->getIngredients() as $ingredient) {
                $stmt->bindValue(':idRecette', $idRecette);
                $stmt->bindValue(':idIngredient', $ingredient['id_ingredient']);
                $stmt->bindValue(':quantite', $ingredient['quantite']);

                $stmt->execute();
            }

            $this->pdo->commit();
            return $idRecette;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
