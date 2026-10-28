

SET NAMES utf8mb4;

-- 1. Tous les utilisateurs
-- Attendu : Chef, Léo, Zoé
SELECT nom, email
FROM utilisateurs
ORDER BY nom;

-- 2. Les recettes de base (livre du chef)
-- Attendu : Crêpes, Omelette au fromage, Ratatouille
SELECT titre
FROM recettes
WHERE recette_de_base = TRUE
ORDER BY titre;

-- 3. Les produits du frigo de Zoé
-- Attendu : jambon, Tomate, Lait, Ccourgette, Yaourt,oeufs, Gruyère
SELECT i.nom, p.date_peremption
FROM produits p
JOIN ingredients i ON i.id = p.id_ingredient
WHERE p.id_utilisateur = 2 AND p.id_statut_produit = 1
ORDER BY p.date_peremption;

-- 4. Les ingrédients de la Ratatouille
-- Attendu : Aubergine, Courgette, Huile d'olive, Oignon, Poivron, Tomate
SELECT i.nom, ri.quantite
FROM recettes_ingredients ri
JOIN ingredients i ON i.id = ri.id_ingredient
WHERE ri.id_recette = 1
ORDER BY i.nom;
