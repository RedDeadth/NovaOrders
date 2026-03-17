<?php

declare(strict_types=1);

namespace App\Modules\Product\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Domain\Entities\Product;
use App\Modules\Product\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Product\Infrastructure\Models\CategoryModel;
use App\Shared\Domain\Exceptions\EntityNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category_id', 'search']);
        $products = $this->productRepository->all($filters);

        return response()->json([
            'data' => array_map(fn(Product $p) => $p->toArray(), $products),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'required|string|unique:products,sku',
            'image_url' => 'nullable|string',
        ]);

        $product = Product::create(
            name: $request->input('name'),
            description: $request->input('description', ''),
            price: (float) $request->input('price'),
            stock: (int) $request->input('stock'),
            categoryId: $request->input('category_id'),
            sku: $request->input('sku'),
            imageUrl: $request->input('image_url'),
        );

        $saved = $this->productRepository->save($product);

        return response()->json([
            'message' => 'Producto creado exitosamente',
            'data' => $saved->toArray(),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json(['data' => $product->toArray()]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'sometimes|string|unique:products,sku,' . $id,
            'image_url' => 'nullable|string',
        ]);

        $existing = $this->productRepository->findById($id);
        if (!$existing) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $product = Product::fromArray(array_merge($existing->toArray(), $request->all()));
        $updated = $this->productRepository->update($id, $product);

        return response()->json([
            'message' => 'Producto actualizado',
            'data' => $updated->toArray(),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $existing = $this->productRepository->findById($id);
        if (!$existing) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $this->productRepository->delete($id);

        return response()->json(['message' => 'Producto eliminado']);
    }

    // ---- Categories ----

    public function categories(): JsonResponse
    {
        $categories = CategoryModel::withCount('products')->get();
        return response()->json(['data' => $categories]);
    }

    public function storeCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = CategoryModel::create([
            'name' => $request->input('name'),
            'description' => $request->input('description', ''),
            'slug' => \Illuminate\Support\Str::slug($request->input('name')),
        ]);

        return response()->json([
            'message' => 'Categoría creada',
            'data' => $category,
        ], 201);
    }
}
