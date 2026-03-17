<?php

declare(strict_types=1);

namespace App\Modules\Customer\Domain\Entities;

class Customer
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $userId,
        private string $name,
        private string $email,
        private string $phone,
        private string $address,
        private string $city,
        private string $documentNumber,
        private ?string $createdAt = null,
    ) {}

    public static function create(
        ?int $userId,
        string $name,
        string $email,
        string $phone,
        string $address,
        string $city,
        string $documentNumber,
    ): self {
        return new self(
            id: null,
            userId: $userId,
            name: $name,
            email: $email,
            phone: $phone,
            address: $address,
            city: $city,
            documentNumber: $documentNumber,
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            userId: $data['user_id'] ?? null,
            name: $data['name'],
            email: $data['email'] ?? '',
            phone: $data['phone'] ?? '',
            address: $data['address'] ?? '',
            city: $data['city'] ?? '',
            documentNumber: $data['document_number'] ?? '',
            createdAt: $data['created_at'] ?? null,
        );
    }

    public function id(): ?int { return $this->id; }
    public function userId(): ?int { return $this->userId; }
    public function name(): string { return $this->name; }
    public function email(): string { return $this->email; }
    public function phone(): string { return $this->phone; }
    public function address(): string { return $this->address; }
    public function city(): string { return $this->city; }
    public function documentNumber(): string { return $this->documentNumber; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'document_number' => $this->documentNumber,
            'created_at' => $this->createdAt,
        ];
    }
}
