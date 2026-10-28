

SET NAMES utf8mb4;

INSERT INTO utilisateurs (id, nom, email, mot_de_passe, id_role, id_langue) VALUES
    (1, 'Chef', 'chef@ratsdufrigo.test', '$2y$10$ObR5O.uKyXKjTQh4fHm.m.wNXrAlNTJAl3107nQC8JzLAQflzxqRO', 2, 1),
    (2, 'Zoé', 'zoe@ratsdufrigo.test', '$2y$10$wlGc3VCoAiJAE6wI1dpbA.iUGFHL1DZf2p8mCwQOV.GdQwhNsF.7u', 1, 1),
    (3, 'Léo', 'leo@ratsdufrigo.test', '$2y$10$2UuRh4Kw1qgKRwigBn0SNOPKJavZyGEUbWUnJLRnmEne8qouwdOPG', 1, 2);

-- Frigo de Zoé (dates calculées à partir d'aujourd'hui)
INSERT INTO produits (id, quantite, date_peremption, id_utilisateur, id_ingredient, id_statut_produit) VALUES
    (1, 4, CURDATE(), 2, 1, 1),                     -- Tomate
    (2, 1, CURDATE() + INTERVAL 1 DAY, 2, 9, 1),    -- Lait
    (3, 3, CURDATE() + INTERVAL 2 DAY, 2, 2, 1),    -- Courgette
    (4, 4, CURDATE() + INTERVAL 4 DAY, 2, 10, 1),   -- Yaourt
    (5, 6, CURDATE() + INTERVAL 12 DAY, 2, 11, 1),  -- oeufs
    (6, 1, CURDATE() + INTERVAL 20 DAY, 2, 12, 1),  -- Gruyère
    (7, 1, CURDATE() - INTERVAL 1 DAY, 2, 15, 1);   -- Jambon (périmé)

-- Frigo de Léo (Zoé ne doit jamais le voir)
INSERT INTO produits (id, quantite, date_peremption, date_ajout, date_sortie, id_utilisateur, id_ingredient, id_statut_produit) VALUES
    (11, 2, CURDATE() + INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY, NULL, 3, 16, 1);  -- Poulet : demain

-- recettes : 3 recettes de base (publiés par le chef), 2 de Zoé, 1 de Léo
INSERT INTO recettes (id, titre, notes, lien, temps_preparation, nb_personnes, recette_de_base, id_utilisateur, id_difficulte) VALUES
    (1, 'Ratatouille', 'Faire revenir l''oignon, ajouter les légumes en dés, laisser mijoter 30 minutes.',
        'https://www.example.com/ratatouille', 45, 4, TRUE, 1, 1),
    (2, 'Crêpes', 'Mélanger, laisser reposer la pâte 1 heure, cuire à la poêle.', NULL, 20, 4, TRUE, 1, 1),
    (3, 'Omelette au fromage', 'Battre les œufs, ajouter le gruyère râpé, cuire 5 minutes.', NULL, 10, 2, TRUE, 1, 1),
    (4, 'Le gratin de Mamie', 'Mamie mettait toujours beaucoup trop de gruyère. 35 minutes à 200 °C.', NULL, 45, 4, FALSE, 2, 2),
    (5, 'Pizza au jambon', NULL, 'https://www.example.com/pizza-jambon', 30, 2, FALSE, 2, 1),
    (6, 'Chicken curry', NULL, 'https://www.example.com/chicken-curry', 40, 2, FALSE, 3, 2);

INSERT INTO recettes_ingredients (id_recette, id_ingredient, quantite) VALUES
    (1, 2, '2'), (1, 3, '1'), (1, 4, '1'), (1, 1, '4'), (1, 5, '1'), (1, 23, '2 c. à s.'),   -- ratatouille
    (2, 9, '5 dl'), (2, 11, '3'), (2, 20, '250 g'), (2, 21, '1 c. à s.'), (2, 14, '20 g'),   -- crêpes
    (3, 11, '3'), (3, 12, '50 g'), (3, 14, '10 g'),                                         -- omelette au fromage
    (4, 2, '3'), (4, 9, '2 dl'), (4, 11, '2'), (4, 12, '150 g'),                            -- le gratin de Mamie
    (5, 1, '3'), (5, 13, '1'), (5, 15, '4 tranches'), (5, 20, '300 g'), (5, 23, NULL),      -- Pizza au jambon
    (6, 16, '400 g'), (6, 18, '2 dl'), (6, 19, '150 g');                                    -- Chicken curry

INSERT INTO recettes_categories (id_recette, id_categorie_recette) VALUES
    (1, 2), (1, 5), (1, 6),   -- ratatouille : Plats, Végétarien, Végan
    (2, 3), (2, 5),           -- crepes : Desserts, Végétarien
    (3, 2), (3, 5),           -- omelette : Plats, Végétarien
    (4, 2), (4, 5),           -- gratin : Plats, Végétarien
    (5, 2),                   -- pizza au jambon : Plats
    (6, 2);                   -- chicken curry : Plats

-- Zoé a ajouté la recette de base « omelette au fromage » à son carnet
INSERT INTO recettes_enregistrees (id_utilisateur, id_recette) VALUES
    (2, 3);
