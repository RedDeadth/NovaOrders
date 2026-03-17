<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\ValueObjects;

use InvalidArgumentException;

final class Email
{
    private function __construct(
        private readonly string $value
    ) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email inválido: {$value}");
        }
    }

    public static function create(string $value): self
    {
        return new self(strtolower(trim($value)));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
