<?php

declare(strict_types=1);

namespace App\Modules\Order\Infrastructure\Repositories;

use App\Modules\Order\Domain\Entities\Order;
use App\Modules\Order\Domain\Entities\OrderItem;
use App\Modules\Order\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Order\Infrastructure\Models\OrderModel;
use App\Modules\Order\Infrastructure\Models\OrderItemModel;
use Illuminate\Support\Facades\DB;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function findById(int $id): ?Order
    {
        $model = OrderModel::with(['items.product', 'customer'])->find($id);
        return $model ? $this->toDomain($model) : null;
    }

    public function all(array $filters = []): array
    {
        $query = OrderModel::with(['items.product', 'customer', 'user']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    public function save(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            $model = OrderModel::create([
                'customer_id' => $order->customerId(),
                'user_id' => $order->userId(),
                'status' => $order->status()->value,
                'total' => $order->total()->amount(),
                'notes' => $order->notes(),
            ]);

            foreach ($order->items() as $item) {
                OrderItemModel::create([
                    'order_id' => $model->id,
                    'product_id' => $item->productId(),
                    'quantity' => $item->quantity(),
                    'unit_price' => $item->unitPrice()->amount(),
                    'subtotal' => $item->subtotal()->amount(),
                ]);
            }

            return $this->toDomain($model->fresh(['items.product', 'customer']));
        });
    }

    public function updateStatus(int $id, string $status): Order
    {
        $model = OrderModel::findOrFail($id);
        $model->update(['status' => $status]);
        return $this->toDomain($model->fresh(['items.product', 'customer']));
    }

    public function delete(int $id): void
    {
        OrderModel::findOrFail($id)->delete();
    }

    public function findByCustomer(int $customerId): array
    {
        return OrderModel::with(['items.product'])
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    public function salesReport(string $startDate, string $endDate): array
    {
        return DB::table('orders')
            ->selectRaw("DATE(created_at) as date, COUNT(*) as total_orders, SUM(total) as total_sales")
            ->whereIn('status', ['pagado', 'enviado', 'entregado'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    private function toDomain(OrderModel $model): Order
    {
        $items = $model->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product?->name ?? '',
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
            ];
        })->toArray();

        return Order::fromArray([
            'id' => $model->id,
            'customer_id' => $model->customer_id,
            'user_id' => $model->user_id,
            'status' => $model->status->value,
            'total' => $model->total,
            'notes' => $model->notes,
            'created_at' => $model->created_at?->toISOString(),
            'updated_at' => $model->updated_at?->toISOString(),
        ], $items);
    }
}
