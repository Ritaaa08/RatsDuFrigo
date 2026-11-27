<?php

require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/traductions.php';

$language = DEFAULT_LANGUAGE;

//si langue envoyée (cookie) ET valide (existe dans la constante LANGUES_DISPONIBLES), alors la définir comme valeur de langage
if (isset($_COOKIE['language']) && in_array($_COOKIE['language'], LANGUES_DISPONIBLES, true)) { //in_array vérifie l'existance dans le tableau de la valeur
    $language = $_COOKIE['language'];
}

//création d'une fonction t qui permet d'écrire plus rapidement et de manière plus lisible la traduction d'un texte de l'interface en utilisant sa clé.
//exemple : t('nav_frigo') au lieu de $traductions[$language]['nav_frigo'] --> <?= htmlspecialchars(t('nav_frigo')) 

//création d'une fonction t() qui permet d'écrire de manière condensée la récupération
//d'une traduction dans la langue choisie ($language).
//global : permet d'accéder à $traductions et $language, qui sont définies en dehors de la fonction.
//1. on vérifie si la clé existe dans la langue choisie : si oui, on renvoie la traduction
//2. sinon, on passe en français (langue par défaut) : si la clé existe, on renvoie la traduction française
//3. sinon, on renvoie la clé elle-même (ça permet de voir qu'il y a une erreur)
function t(string $cle): string {
    global $traductions, $language; //accès à tous les éléments en-dehors de la fonction pour que ça marche : $traductions est dans traduction.php et $language dans langue.php

    if (isset($traductions[$language][$cle])) {
        return $traductions[$language][$cle];
    }

    if (isset($traductions['fr'][$cle])) { //si le cookie est invalide dans le isset précédent, on fait un isset sur fr car c'est la langue par défautdonc return est garantit de fonctionner.
        return $traductions['fr'][$cle];
    }

    return $cle;
}
