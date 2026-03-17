<?php

declare(strict_types=1);

namespace App\Modules\Report\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Order\Domain\Repositories\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportApiController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository
    ) {}

    public function sales(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $report = $this->orderRepository->salesReport(
            $request->input('start_date'),
            $request->input('end_date'),
        );

        $totalSales = array_sum(array_column($report, 'total_sales'));
        $totalOrders = array_sum(array_column($report, 'total_orders'));

        return response()->json([
            'data' => $report,
            'summary' => [
                'total_sales' => $totalSales,
                'total_orders' => $totalOrders,
                'average_order_value' => $totalOrders > 0 ? round($totalSales / $totalOrders, 2) : 0,
                'period' => [
                    'start' => $request->input('start_date'),
                    'end' => $request->input('end_date'),
                ],
            ],
        ]);
    }

    public function dashboard(): JsonResponse
    {
        $stats = [
            'total_products' => DB::table('products')->count(),
            'total_customers' => DB::table('customers')->count(),
            'total_orders' => DB::table('orders')->count(),
            'pending_orders' => DB::table('orders')->where('status', 'pendiente')->count(),
            'total_revenue' => DB::table('orders')
                ->whereIn('status', ['pagado', 'enviado', 'entregado'])
                ->sum('total'),
            'recent_orders' => DB::table('orders')
                ->join('customers', 'orders.customer_id', '=', 'customers.id')
                ->select('orders.*', 'customers.name as customer_name')
                ->orderBy('orders.created_at', 'desc')
                ->limit(5)
                ->get(),
            'low_stock_products' => DB::table('products')
                ->where('stock', '<=', 10)
                ->orderBy('stock')
                ->limit(5)
                ->get(),
        ];

        return response()->json(['data' => $stats]);
    }
}
