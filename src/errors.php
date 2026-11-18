<?php

function afficherErreurGenerique(Throwable $e): void {
    error_log($e->getMessage());
    http_response_code(500);
    echo "Une erreur est survenue. Réessayez plus tard.";
}

ini_set('display_errors', '0');
set_exception_handler('afficherErreurGenerique');
