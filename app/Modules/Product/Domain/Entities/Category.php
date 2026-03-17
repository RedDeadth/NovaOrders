<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Entities;

class Category
{
    public function __construct(
        private readonly ?int $id,
        private string $name,
        private string $description,
        private string $slug,
    ) {}

    public static function create(string $name, string $description): self
    {
        return new self(
            id: null,
            name: $name,
            description: $description,
            slug: \Illuminate\Support\Str::slug($name),
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'],
            description: $data['description'] ?? '',
            slug: $data['slug'] ?? \Illuminate\Support\Str::slug($data['name']),
        );
    }

    public function id(): ?int { return $this->id; }
    public function name(): string { return $this->name; }
    public function description(): string { return $this->description; }
    public function slug(): string { return $this->slug; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
        ];
    }
}
