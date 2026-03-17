<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exceptions;

class EntityNotFoundException extends DomainException
{
    public static function withId(string $entity, int|string $id): self
    {
        return new self("{$entity} con ID {$id} no encontrado");
    }
}
