<?php

declare(strict_types=1);

namespace App\Modules\Order\Domain\Entities;

use App\Modules\Order\Domain\ValueObjects\OrderStatus;
use App\Shared\Domain\Exceptions\DomainException;
use App\Shared\Domain\ValueObjects\Money;

class Order
{
    /** @var OrderItem[] */
    private array $items;

    public function __construct(
        private readonly ?int $id,
        private readonly int $customerId,
        private readonly ?int $userId,
        private OrderStatus $status,
        private Money $total,
        private string $notes,
        array $items = [],
        private ?string $createdAt = null,
        private ?string $updatedAt = null,
    ) {
        $this->items = $items;
    }

    public static function create(int $customerId, ?int $userId, string $notes = ''): self
    {
        return new self(
            id: null,
            customerId: $customerId,
            userId: $userId,
            status: OrderStatus::PENDIENTE,
            total: Money::zero(),
            notes: $notes,
            items: [],
        );
    }

    public static function fromArray(array $data, array $items = []): self
    {
        $orderItems = array_map(fn(array $item) => OrderItem::fromArray($item), $items);

        return new self(
            id: $data['id'] ?? null,
            customerId: $data['customer_id'],
            userId: $data['user_id'] ?? null,
            status: OrderStatus::from($data['status']),
            total: Money::create((float) ($data['total'] ?? 0)),
            notes: $data['notes'] ?? '',
            items: $orderItems,
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
        );
    }

    public function id(): ?int { return $this->id; }
    public function customerId(): int { return $this->customerId; }
    public function userId(): ?int { return $this->userId; }
    public function status(): OrderStatus { return $this->status; }
    public function total(): Money { return $this->total; }
    public function notes(): string { return $this->notes; }
    /** @return OrderItem[] */
    public function items(): array { return $this->items; }
    public function createdAt(): ?string { return $this->createdAt; }

    public function addItem(OrderItem $item): void
    {
        $this->items[] = $item;
        $this->recalculateTotal();
    }

    public function changeStatus(OrderStatus $newStatus): void
    {
        if (!$this->status->canTransitionTo($newStatus)) {
            throw DomainException::because(
                "No se puede cambiar el estado de {$this->status->label()} a {$newStatus->label()}"
            );
        }
        $this->status = $newStatus;
    }

    public function recalculateTotal(): void
    {
        $total = Money::zero();
        foreach ($this->items as $item) {
            $total = $total->add($item->subtotal());
        }
        $this->total = $total;
    }

    public function itemCount(): int
    {
        return count($this->items);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customerId,
            'user_id' => $this->userId,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'status_color' => $this->status->color(),
            'total' => $this->total->amount(),
            'notes' => $this->notes,
            'items' => array_map(fn(OrderItem $item) => $item->toArray(), $this->items),
            'item_count' => $this->itemCount(),
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
