

SET NAMES utf8mb4;

INSERT INTO roles (id, libelle) VALUES
    (1, 'utilisateur·rice'),
    (2, 'administrateur·rice');

INSERT INTO langues (id, code, libelle) VALUES
    (1, 'fr', 'Français'),
    (2, 'en', 'English');

INSERT INTO statuts_produit (id, libelle) VALUES
    (1, 'au frigo'),
    (2, 'consommé'),
    (3, 'jeté');

INSERT INTO difficultes (id, libelle) VALUES
    (1, 'facile'),
    (2, 'moyenne'),
    (3, 'difficile');

INSERT INTO categories_ingredients (id, libelle) VALUES
    (1, 'Légumes'),
    (2, 'Fruits'),
    (3, 'Laitages et œufs'),
    (4, 'Viandes et poissons'),
    (5, 'Boissons'),
    (6, 'Épicerie');

INSERT INTO categories_recettes (id, libelle) VALUES
    (1, 'Entrées'),
    (2, 'Plats'),
    (3, 'Desserts'),
    (4, 'Boissons'),
    (5, 'Végétarien'),
    (6, 'Végan');

-- toujours_disponible = TRUE : ingrédient de placard, jamais compté comme manquant
INSERT INTO ingredients (id, nom, duree_conservation_jours, toujours_disponible, id_categorie_ingredient) VALUES
    (1, 'Tomate', 7, FALSE, 1),
    (2, 'Courgette', 7, FALSE, 1),
    (3, 'Aubergine', 7, FALSE, 1),
    (4, 'Poivron', 7, FALSE, 1),
    (5, 'Oignon', 30, FALSE, 1),
    (6, 'Salade', 5, FALSE, 1),
    (7, 'Pomme', 30, FALSE, 2),
    (8, 'Banane', 7, FALSE, 2),
    (9, 'Lait', 7, FALSE, 3),
    (10, 'Yaourt', 21, FALSE, 3),
    (11, 'Œufs', 28, FALSE, 3),
    (12, 'Gruyère', 30, FALSE, 3),
    (13, 'Mozzarella', 7, FALSE, 3),
    (14, 'Beurre', 30, FALSE, 3),
    (15, 'Jambon', 5, FALSE, 4),
    (16, 'Poulet', 2, FALSE, 4),
    (17, 'Jus d''orange', 7, FALSE, 5),
    (18, 'Lait de coco', 365, FALSE, 6),
    (19, 'Riz', 730, FALSE, 6),
    (20, 'Farine', 365, TRUE, 6),
    (21, 'Sucre', 730, TRUE, 6),
    (22, 'Sel', 3650, TRUE, 6),
    (23, 'Huile d''olive', 365, TRUE, 6);
