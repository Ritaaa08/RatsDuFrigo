
SET NAMES utf8mb4;

-- Suppression des tables existantes (d'abord celles qui dépendent des autres)
DROP TABLE IF EXISTS recettes_enregistrees;
DROP TABLE IF EXISTS recettes_categories;
DROP TABLE IF EXISTS recettes_ingredients;
DROP TABLE IF EXISTS recettes;
DROP TABLE IF EXISTS produits;
DROP TABLE IF EXISTS ingredients;
DROP TABLE IF EXISTS utilisateurs;
DROP TABLE IF EXISTS categories_recettes;
DROP TABLE IF EXISTS categories_ingredients;
DROP TABLE IF EXISTS difficultes;
DROP TABLE IF EXISTS statuts_produit;
DROP TABLE IF EXISTS langues;
DROP TABLE IF EXISTS roles;

-- TABLES DE RÉFÉRENCE
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(30) NOT NULL UNIQUE
    
);

CREATE TABLE langues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code CHAR(2) NOT NULL UNIQUE,
    libelle VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE statuts_produit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(30) NOT NULL UNIQUE
    
);

CREATE TABLE difficultes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(30) NOT NULL UNIQUE
    
);

CREATE TABLE categories_ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE categories_recettes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- TABLES MÉTIERS
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_role INT NOT NULL,
    id_langue INT NOT NULL,
    FOREIGN KEY (id_role) REFERENCES roles(id),
    FOREIGN KEY (id_langue) REFERENCES langues(id)
);

CREATE TABLE ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    duree_conservation_jours INT NOT NULL CHECK (duree_conservation_jours > 0),
    toujours_disponible BOOLEAN NOT NULL DEFAULT FALSE,
    id_categorie_ingredient INT NOT NULL,
    FOREIGN KEY (id_categorie_ingredient) REFERENCES categories_ingredients(id)
);

CREATE TABLE produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quantite INT NOT NULL DEFAULT 1 CHECK (quantite > 0),
    date_peremption DATE NOT NULL,
    date_ajout DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_sortie DATETIME,
    id_utilisateur INT NOT NULL,
    id_ingredient INT NOT NULL,
    id_statut_produit INT NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (id_ingredient) REFERENCES ingredients(id),
    FOREIGN KEY (id_statut_produit) REFERENCES statuts_produit(id)
);

CREATE TABLE recettes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(50) NOT NULL,
    notes TEXT,
    lien VARCHAR(2048),
    temps_preparation INT CHECK (temps_preparation > 0),
    nb_personnes INT CHECK (nb_personnes > 0),
    recette_de_base BOOLEAN NOT NULL DEFAULT FALSE,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_utilisateur INT NOT NULL,
    id_difficulte INT,
    CHECK (notes IS NOT NULL OR lien IS NOT NULL),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (id_difficulte) REFERENCES difficultes(id)
);

-- TABLES D'ASSOCIATION
CREATE TABLE recettes_ingredients (
    PRIMARY KEY (id_recette, id_ingredient),
    id_recette INT NOT NULL,
    id_ingredient INT NOT NULL,
    quantite VARCHAR(30),
    FOREIGN KEY (id_recette) REFERENCES recettes(id) ON DELETE CASCADE,
    FOREIGN KEY (id_ingredient) REFERENCES ingredients(id)
);

CREATE TABLE recettes_categories (
    PRIMARY KEY (id_recette, id_categorie_recette),
    id_recette INT NOT NULL,
    id_categorie_recette INT NOT NULL,
    FOREIGN KEY (id_recette) REFERENCES recettes(id) ON DELETE CASCADE,
    FOREIGN KEY (id_categorie_recette) REFERENCES categories_recettes(id) ON DELETE CASCADE
);

CREATE TABLE recettes_enregistrees (
    PRIMARY KEY (id_utilisateur, id_recette),
    id_utilisateur INT NOT NULL,
    id_recette INT NOT NULL,
    date_enregistrement DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (id_recette) REFERENCES recettes(id) ON DELETE CASCADE
);
