<?php

declare(strict_types=1);

namespace Exceptions;

use Exception;

class AppException extends Exception
{
    /** @param string|null $champ Nom du champ de formulaire lie a l'erreur (affichage sous l'input). */
    public function __construct(string $message, public readonly ?string $champ = null)
    {
        parent::__construct($message);
    }
}