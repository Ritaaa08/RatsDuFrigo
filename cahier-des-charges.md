# RatsduFrigo

> Projet réalisé dans le cadre du cours _Programmation serveur 2 (ProgServ2)_ à
> la [HEIG-VD](https://heig-vd.ch), année académique 2026-2027.
>
> Cahier des charges initial — version 1.0 du 30.09.2026.

Application web qui permet de gérer les ingrédients de son frigo et ses
recettes, pour cuisiner à temps ce qui va bientôt périmer.

Le but est de faciliter la recherche d'idées de repas et de réduire le coût et
le gaspillage alimentaire :

- en consultant la partie **frigo** ou la partie **recettes** séparément ;
- ou en **liant frigo et recettes** : l'application propose en priorité les
  recettes qui utilisent les produits qui vont bientôt périmer.

Le tout dans l'ambiance d'une cuisine de bistrot parisien, avec un chef rat
comme mascotte. C'est un clin d'œil au film _Ratatouille_.

## Équipe

- Demakova Margarita
- Lagniaz Marie

## Fonctionnalités principales

- **Comptes**
  - Création d'un compte, connexion et déconnexion
  - Modification de son profil (nom, e-mail, mot de passe, langue)
  - Deux rôles : utilisateur·rice et administrateur·rice (le rôle administrateur
    est attribué par l'administration, il ne se choisit pas à l'inscription)

- **Section frigo**
  - Enregistrer les ingrédients du frigo avec leur **date de péremption**
    (ajout, modification, suppression)
  - Date proposée automatiquement selon l'ingrédient (par ex. lait : 7 jours),
    modifiable
  - Couleur selon l'urgence : périmé (date dépassée), urgent (0 à 2 jours,
    aujourd'hui inclus), bientôt (3 à 5 jours), OK (6 jours et plus).
  - Recherche dans la liste via des filtres
  - Sortir un produit du frigo en indiquant s'il a été consommé ou jeté, avec un
    compteur anti-gaspillage

- **Section recettes (mon carnet)**
  - Enregistrer des recettes (ajout, modification, suppression) : un titre, des
    notes libres (par ex. la recette de sa grand-mère) et/ou le lien d'une
    recette trouvée sur Internet
  - Indiquer les ingrédients principaux de chaque recette
  - Recherche dans la liste via des filtres
  - Recettes de base publiées par l'administration, consultables avec ou sans
    compte/inscription.

- **Associations entre frigo et recettes**
  - Suggestion de repas en fonction du contenu du frigo, en priorité ceux qui
    utilisent les produits qui vont bientôt périmer
  - Les recettes sont classées par ordre d'urgence des produits qu'elles
    permettent de sauver, puis selon le nombre d'ingrédients manquants (les
    suggestions se reposent sur le carnet personnel et les recettes de base)

- **Administration**
  - Gestion du catalogue d'ingrédients (avec leur durée de conservation par
    défaut) et des catégories d'ingrédients et de recettes
  - Gestion des recettes de base

- **Multilingue** : interface en français et en anglais.

- **E-mails** : « la critique de ton frigo », un e-mail récapitulatif envoyé à
  la demande, avec les produits à consommer rapidement et les recettes
  suggérées. Le mail est envoyé dans la langue du profil.

## Fonctionnalités optionnelles

À réaliser si le temps le permet, par ordre de priorité :

1. **Frigo partagé** : inviter ses colocs ou sa famille par e-mail pour remplir
   le même frigo
2. **Liste de courses** : ajouter les ingrédients manquants d'une recette à sa
   liste, puis ranger les achats dans le frigo en un clic, avec des dates de
   péremption proposées
3. **Lien intelligent** : coller le lien d'une recette remplit automatiquement
   son titre et ses ingrédients, quand le site le permet
4. **Budget des courses**
5. **Carte ou liste de commerces pour trouver des ingrédients**
6. **Pour chaque suggestion de recette** : les produits qu'elle permet de sauver
   et les ingrédients qui manquent

## Bilan de fin de projet

_À compléter en fin de projet : fonctionnalités réellement implémentées,
difficultés rencontrées et conclusion._
