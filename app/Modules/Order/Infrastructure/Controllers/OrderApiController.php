<?php

declare(strict_types=1);

namespace App\Modules\Order\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Domain\Entities\Order;
use App\Modules\Order\Domain\Entities\OrderItem;
use App\Modules\Order\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Order\Domain\ValueObjects\OrderStatus;
use App\Modules\Product\Domain\Repositories\ProductRepositoryInterface;
use App\Shared\Domain\Exceptions\DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'customer_id', 'date_from', 'date_to']);
        $orders = $this->orderRepository->all($filters);

        return response()->json([
            'data' => array_map(fn(Order $o) => $o->toArray(), $orders),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::create(
            customerId: (int) $request->input('customer_id'),
            userId: $request->user()->id,
            notes: $request->input('notes', ''),
        );

        foreach ($request->input('items') as $itemData) {
            $product = $this->productRepository->findById((int) $itemData['product_id']);

            if (!$product) {
                return response()->json([
                    'message' => "Producto con ID {$itemData['product_id']} no encontrado"
                ], 404);
            }

            if (!$product->hasStock((int) $itemData['quantity'])) {
                return response()->json([
                    'message' => "Stock insuficiente para {$product->name()}. Disponible: {$product->stock()}"
                ], 422);
            }

            $item = OrderItem::create(
                productId: $product->id(),
                productName: $product->name(),
                quantity: (int) $itemData['quantity'],
                unitPrice: $product->price()->amount(),
            );

            $order->addItem($item);
        }

        $saved = $this->orderRepository->save($order);

        return response()->json([
            'message' => 'Pedido creado exitosamente',
            'data' => $saved->toArray(),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        return response()->json(['data' => $order->toArray()]);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pendiente,pagado,enviado,entregado,cancelado',
        ]);

        $order = $this->orderRepository->findById($id);

        if (!$order) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        try {
            $newStatus = OrderStatus::from($request->input('status'));
            $order->changeStatus($newStatus);

            $updated = $this->orderRepository->updateStatus($id, $newStatus->value);

            return response()->json([
                'message' => "Estado actualizado a {$newStatus->label()}",
                'data' => $updated->toArray(),
            ]);
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        $this->orderRepository->delete($id);

        return response()->json(['message' => 'Pedido eliminado']);
    }
}
