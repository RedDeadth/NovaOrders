<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Entities;

use App\Shared\Domain\ValueObjects\Money;

class Product
{
    public function __construct(
        private readonly ?int $id,
        private string $name,
        private string $description,
        private Money $price,
        private int $stock,
        private ?int $categoryId,
        private string $sku,
        private ?string $imageUrl = null,
        private ?string $createdAt = null,
    ) {}

    public static function create(
        string $name,
        string $description,
        float $price,
        int $stock,
        ?int $categoryId,
        string $sku,
        ?string $imageUrl = null,
    ): self {
        return new self(
            id: null,
            name: $name,
            description: $description,
            price: Money::create($price),
            stock: $stock,
            categoryId: $categoryId,
            sku: $sku,
            imageUrl: $imageUrl,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'],
            description: $data['description'] ?? '',
            price: Money::create((float) $data['price']),
            stock: (int) $data['stock'],
            categoryId: $data['category_id'] ?? null,
            sku: $data['sku'] ?? '',
            imageUrl: $data['image_url'] ?? null,
            createdAt: $data['created_at'] ?? null,
        );
    }

    public function id(): ?int { return $this->id; }
    public function name(): string { return $this->name; }
    public function description(): string { return $this->description; }
    public function price(): Money { return $this->price; }
    public function stock(): int { return $this->stock; }
    public function categoryId(): ?int { return $this->categoryId; }
    public function sku(): string { return $this->sku; }
    public function imageUrl(): ?string { return $this->imageUrl; }

    public function hasStock(int $quantity = 1): bool
    {
        return $this->stock >= $quantity;
    }

    public function reduceStock(int $quantity): void
    {
        if (!$this->hasStock($quantity)) {
            throw new \InvalidArgumentException(
                "Stock insuficiente para {$this->name}. Disponible: {$this->stock}, solicitado: {$quantity}"
            );
        }
        $this->stock -= $quantity;
    }

    public function addStock(int $quantity): void
    {
        $this->stock += $quantity;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price->amount(),
            'stock' => $this->stock,
            'category_id' => $this->categoryId,
            'sku' => $this->sku,
            'image_url' => $this->imageUrl,
            'created_at' => $this->createdAt,
        ];
    }
}
