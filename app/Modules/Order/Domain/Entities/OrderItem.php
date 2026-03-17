<?php

declare(strict_types=1);

namespace App\Modules\Order\Domain\Entities;

use App\Shared\Domain\ValueObjects\Money;

class OrderItem
{
    public function __construct(
        private readonly ?int $id,
        private readonly int $productId,
        private readonly string $productName,
        private int $quantity,
        private Money $unitPrice,
    ) {}

    public static function create(int $productId, string $productName, int $quantity, float $unitPrice): self
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('La cantidad debe ser mayor a 0');
        }

        return new self(
            id: null,
            productId: $productId,
            productName: $productName,
            quantity: $quantity,
            unitPrice: Money::create($unitPrice),
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            productId: $data['product_id'],
            productName: $data['product_name'] ?? '',
            quantity: (int) $data['quantity'],
            unitPrice: Money::create((float) $data['unit_price']),
        );
    }

    public function id(): ?int { return $this->id; }
    public function productId(): int { return $this->productId; }
    public function productName(): string { return $this->productName; }
    public function quantity(): int { return $this->quantity; }
    public function unitPrice(): Money { return $this->unitPrice; }

    public function subtotal(): Money
    {
        return $this->unitPrice->multiply($this->quantity);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->productId,
            'product_name' => $this->productName,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice->amount(),
            'subtotal' => $this->subtotal()->amount(),
        ];
    }
}
