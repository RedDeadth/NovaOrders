<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs;

final class RegisterDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role = 'cliente',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'] ?? 'cliente',
        );
    }
}
