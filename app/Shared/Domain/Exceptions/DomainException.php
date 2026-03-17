<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

use Exception;

class DomainException extends Exception
{
    public static function because(string $reason): self
    {
        return new self($reason);
    }
}
