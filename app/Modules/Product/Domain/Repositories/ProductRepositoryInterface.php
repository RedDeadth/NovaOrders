<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Repositories;

use App\Modules\Product\Domain\Entities\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;
    public function findBySku(string $sku): ?Product;
    public function all(array $filters = []): array;
    public function save(Product $product): Product;
    public function update(int $id, Product $product): Product;
    public function delete(int $id): void;
    public function findByCategory(int $categoryId): array;
}
