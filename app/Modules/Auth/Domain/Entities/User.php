<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\Entities;

use App\Modules\Auth\Domain\ValueObjects\Email;
use App\Modules\Auth\Domain\ValueObjects\UserRole;

class User
{
    public function __construct(
        private readonly ?int $id,
        private string $name,
        private Email $email,
        private UserRole $role,
        private ?string $createdAt = null,
    ) {}

    public static function create(string $name, string $email, UserRole $role): self
    {
        return new self(
            id: null,
            name: $name,
            email: Email::create($email),
            role: $role,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'],
            email: Email::create($data['email']),
            role: UserRole::from($data['role']),
            createdAt: $data['created_at'] ?? null,
        );
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function role(): UserRole
    {
        return $this->role;
    }

    public function createdAt(): ?string
    {
        return $this->createdAt;
    }

    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    public function canManageProducts(): bool
    {
        return $this->role->canManageProducts();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email->value(),
            'role' => $this->role->value,
            'created_at' => $this->createdAt,
        ];
    }
}
