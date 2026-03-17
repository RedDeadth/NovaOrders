<?php

declare(strict_types=1);

namespace App\Modules\Auth\Domain\ValueObjects;

enum UserRole: string
{
    case ADMIN = 'admin';
    case VENDEDOR = 'vendedor';
    case CLIENTE = 'cliente';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::VENDEDOR => 'Vendedor',
            self::CLIENTE => 'Cliente',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function canManageProducts(): bool
    {
        return in_array($this, [self::ADMIN, self::VENDEDOR]);
    }

    public function canManageOrders(): bool
    {
        return in_array($this, [self::ADMIN, self::VENDEDOR]);
    }
}
