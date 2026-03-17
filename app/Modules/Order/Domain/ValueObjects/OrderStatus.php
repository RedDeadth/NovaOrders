<?php

declare(strict_types=1);

namespace App\Modules\Order\Domain\ValueObjects;

enum OrderStatus: string
{
    case PENDIENTE = 'pendiente';
    case PAGADO = 'pagado';
    case ENVIADO = 'enviado';
    case ENTREGADO = 'entregado';
    case CANCELADO = 'cancelado';

    public function label(): string
    {
        return match ($this) {
            self::PENDIENTE => 'Pendiente',
            self::PAGADO => 'Pagado',
            self::ENVIADO => 'Enviado',
            self::ENTREGADO => 'Entregado',
            self::CANCELADO => 'Cancelado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDIENTE => 'yellow',
            self::PAGADO => 'blue',
            self::ENVIADO => 'indigo',
            self::ENTREGADO => 'green',
            self::CANCELADO => 'red',
        };
    }

    public function canTransitionTo(OrderStatus $newStatus): bool
    {
        return match ($this) {
            self::PENDIENTE => in_array($newStatus, [self::PAGADO, self::CANCELADO]),
            self::PAGADO => in_array($newStatus, [self::ENVIADO, self::CANCELADO]),
            self::ENVIADO => in_array($newStatus, [self::ENTREGADO]),
            self::ENTREGADO, self::CANCELADO => false,
        };
    }

    /** @return OrderStatus[] */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::PENDIENTE => [self::PAGADO, self::CANCELADO],
            self::PAGADO => [self::ENVIADO, self::CANCELADO],
            self::ENVIADO => [self::ENTREGADO],
            self::ENTREGADO, self::CANCELADO => [],
        };
    }
}
