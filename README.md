# Rats du Frigo

Notre site est disponible sur : https://ratsdufrigo.thenox.ch

Rats du Frigo est une application web pour gérer les produits de son frigo et
ses recettes, afin de cuisiner à temps ce qui va bientôt périmer et de réduire
le gaspillage.

Projet réalisé dans le cadre du cours _Programmation serveur 2 (ProgServ2)_ à la
[HEIG-VD](https://heig-vd.ch), année académique 2026-2027, à partir du modèle de
projet du cours.

Équipe : Margarita Demakova et Marie Lagniaz.

Le détail du projet se trouve dans le
[cahier des charges](cahier-des-charges.md).

## Fonctionnalités réalisées

- **Catalogue** : la liste des ingrédients, par catégorie.
- **Mon frigo** : les produits classés par date de péremption, avec leur niveau
  d'urgence (périmé, urgent, bientôt, OK). On peut ajouter et supprimer un
  produit.
- **Recettes** : le livre du chef (recettes de base) et mon carnet (recettes
  personnelles). On peut voir, créer et supprimer une recette.

La connexion n'est pas encore faite : l'application utilise pour l'instant un
utilisateur de démonstration (id 2).

## Structure du projet

- `public/` : les pages du site. En ligne, c'est le seul dossier accessible
  depuis le web.
- `components/` : les morceaux communs à toutes les pages (head, header,
  footer).
- `src/classes/` : les classes de l'application (`Recette`,`Produit`,
  `Ingredient`), les managers qui font les requêtes SQL, `Database`
  et`ValidationException`.
- `src/interfaces/` : les interfaces des managers.
- `src/constants.php` : les valeurs fixes.
- `src/errors.php` : affiche un message neutre en cas d'erreur imprévue.
- `src/db/` : les scripts SQL (`schema.sql`, `donnees-reference.sql`, et
  `donnees-test.sql` pour le local uniquement).
- `config/database.ini.example` : le modèle de configuration de la base de
  données. Le vrai fichier `config/database.ini` ne va jamais dans Git.

## Lancer le projet en local

1. Ouvrir le dossier du projet dans Visual Studio Code, puis choisir _"Reopen in
   Container"_.
2. Dans le terminal, démarrer les services :

   ```bash
   docker compose up -d
   ```

3. Copier `config/database.ini.example` en `config/database.ini`. Les valeurs
   par défaut correspondent à `compose.yaml`.
4. Ouvrir phpMyAdmin sur <http://localhost:9090> (identifiants dans
   `compose.yaml`), choisir la base `my_database`, puis exécuter dans l'onglet
   SQL, dans cet ordre : `schema.sql`, `donnees-reference.sql` et
   `donnees-test.sql`.
5. Ouvrir le site sur <http://localhost:8080/public/index.php>.

## Sécurité

- **Injections SQL** : toutes les requêtes utilisent des requêtes préparées
  (`prepare` + `bindValue`).
- **XSS** : toutes les données affichées passent par `htmlspecialchars`.
- **Validation** : les données sont vérifiées côté serveur, dans le constructeur
  des classes. Un objet avec des données invalides ne peut pas être créé.
- **Nettoyage des saisies** : les champs sont lus avec `filter_input`, et les
  identifiants avec `FILTER_VALIDATE_INT`.
- **Suppressions** : uniquement par formulaire (POST), et seulement sur les
  données de l'utilisateur.
- **Erreurs** : un message neutre s'affiche à l'écran, et le détail va dans le
  journal du serveur.
- **Configuration** : en ligne, `config/` et `src/` ne sont pas accessibles
  depuis le web.

Limites connues :

- Il n'y a pas de protection contre les attaques CSRF.
- Il n'y a pas encore de connexion. La séparation entre utilisateurs est déjà
  présente dans les requêtes SQL, mais elle ne pourra être testée qu'avec la
  connexion.

## Utilisation de l'IA

Nous avons utilisé **Claude Code (Anthropic)** pour :

- nous aider à nous souvenir comment créer le MLD et le MPD de la base de
  données en demandant des explications sur des notions pas vues ou oubliées ;
- aider à écrire les scripts SQL du dossier `src/db/` surtout pour les données
  de test et les requêtes de test.

Pour les séances 5 à 7, nous l'avons aussi utilisé pour :

- nous expliquer la POO (classes, managers, interfaces) et nous aider à
  organiser nos classes ;
- proposer du code pour certaines parties, par exemple les composants communs
  (head, header, footer) et l'ajout et suppression de produits dans le frigo ;
- relire notre code, trouver des bugs et nous expliquer comment le code
  fonctionne et comment on peut corriger les bugs (étape par étape) ;
- après les remarques du prof, nous aider à déplacer la validation dans les
  constructeurs ;
- vérifier la sécurité de l'application (injections SQL, XSS, affichage des
  erreurs) et proposer des corrections ;
- tester les pages et nous guider pour Git (branches, pull requests) quand on a
  eu des difficultés.

Ce qui a été généré par l'IA a été testé, vérifié et corrigé par le groupe.
