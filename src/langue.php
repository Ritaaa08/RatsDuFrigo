<?php

require_once __DIR__ . '/constants.php';

$language = DEFAULT_LANGUAGE;

//si langue envoyée (cookie) ET valide (existe dans la constante LANGUES_DISPONIBLES), alors la définir comme valeur de langage
if (isset($_COOKIE['language']) && in_array($_COOKIE['language'], LANGUES_DISPONIBLES, true)) { //in_array vérifie l'existance dans le tableau de la valeur
    $language = $_COOKIE['language'];
}
