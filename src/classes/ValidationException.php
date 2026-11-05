<?php

class ValidationException extends InvalidArgumentException {
    private array $erreurs;

    public function __construct(array $erreurs) {
        parent::__construct(implode(' ', $erreurs));
        $this->erreurs = $erreurs;
    }

    public function getErreurs(): array {
        return $this->erreurs;
    }
}
