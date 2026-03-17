<?php

declare(strict_types=1);

namespace App\Modules\Product\Infrastructure\Repositories;

use App\Modules\Product\Domain\Entities\Product;
use App\Modules\Product\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Product\Infrastructure\Models\ProductModel;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): ?Product
    {
        $model = ProductModel::find($id);
        return $model ? $this->toDomain($model) : null;
    }

    public function findBySku(string $sku): ?Product
    {
        $model = ProductModel::where('sku', $sku)->first();
        return $model ? $this->toDomain($model) : null;
    }

    public function all(array $filters = []): array
    {
        $query = ProductModel::with('category');

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%");
        }

        return $query->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    public function save(Product $product): Product
    {
        $model = ProductModel::create([
            'name' => $product->name(),
            'description' => $product->description(),
            'price' => $product->price()->amount(),
            'stock' => $product->stock(),
            'category_id' => $product->categoryId(),
            'sku' => $product->sku(),
            'image_url' => $product->imageUrl(),
        ]);

        return $this->toDomain($model);
    }

    public function update(int $id, Product $product): Product
    {
        $model = ProductModel::findOrFail($id);
        $model->update([
            'name' => $product->name(),
            'description' => $product->description(),
            'price' => $product->price()->amount(),
            'stock' => $product->stock(),
            'category_id' => $product->categoryId(),
            'sku' => $product->sku(),
            'image_url' => $product->imageUrl(),
        ]);

        return $this->toDomain($model->fresh());
    }

    public function delete(int $id): void
    {
        ProductModel::findOrFail($id)->delete();
    }

    public function findByCategory(int $categoryId): array
    {
        return ProductModel::where('category_id', $categoryId)
            ->get()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    private function toDomain(ProductModel $model): Product
    {
        return Product::fromArray([
            'id' => $model->id,
            'name' => $model->name,
            'description' => $model->description,
            'price' => $model->price,
            'stock' => $model->stock,
            'category_id' => $model->category_id,
            'sku' => $model->sku,
            'image_url' => $model->image_url,
            'created_at' => $model->created_at?->toISOString(),
        ]);
    }
}
