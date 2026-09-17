<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CartActivity;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = in_array($request->query('period'), ['day', 'week', 'month', 'year'], true)
            ? $request->query('period')
            : 'week';

        [$startDate, $endDate] = $this->periodDates($period);
        [$chartLabels, $chartKeys] = $this->chartAxis($period, $startDate, $endDate);

        if (!Schema::hasTable('orders')) {
            return $this->emptyDashboard($period, $chartLabels, $chartKeys);
        }

        // created_at is reliably filled by Laravel. order_date is only a fallback.
        $dateColumn = Schema::hasColumn('orders', 'created_at')
            ? 'created_at'
            : (Schema::hasColumn('orders', 'order_date') ? 'order_date' : 'id');

        $ordersQuery = Order::query()->with('items');
        if ($dateColumn !== 'id') {
            $ordersQuery->whereBetween($dateColumn, [$startDate, $endDate]);
        }

        $orders = $ordersQuery
            ->orderByDesc($dateColumn)
            ->get();

        $totalProducts = Schema::hasTable('products') ? Product::query()->count() : 0;
        $totalOrders = $orders->count();
        $transactions = $totalOrders;
        $totalSales = $orders->sum(fn (Order $order) => $this->orderTotal($order));

        $hasPaymentStatus = Schema::hasColumn('orders', 'payment_status');
        $payments = $hasPaymentStatus
            ? $orders->filter(fn (Order $order) => in_array(strtolower(trim((string) $order->payment_status)), [
                'paid', 'completed', 'complete', 'succeeded', 'success', 'captured',
            ], true))->sum(fn (Order $order) => $this->orderTotal($order))
            : $totalSales;

        // No cost/purchase-price field exists in the supplied models.
        $totalProfit = $totalSales * 0.20;
        $profitIsEstimated = true;

        $statusColumn = collect(['status', 'order_status', 'delivery_status'])
            ->first(fn (string $column) => Schema::hasColumn('orders', $column));

        $statusCounts = [
            'pending' => 0,
            'processing' => 0,
            'shipped' => 0,
            'delivered' => 0,
            'cancelled' => 0,
        ];

        foreach ($orders as $order) {
            $status = $statusColumn
                ? strtolower(trim((string) ($order->{$statusColumn} ?? 'pending')))
                : 'pending';

            $group = $this->statusGroup($status);
            $statusCounts[$group]++;
        }

        $salesMap = array_fill_keys($chartKeys, 0.0);
        $ordersMap = array_fill_keys($chartKeys, 0);

        foreach ($orders as $order) {
            $date = $order->{$dateColumn};
            if (!$date) continue;

            $date = $date instanceof Carbon ? $date : Carbon::parse($date);
            $key = match ($period) {
                'day' => $date->format('H'),
                'year' => $date->format('Y-m'),
                default => $date->format('Y-m-d'),
            };

            if (array_key_exists($key, $salesMap)) {
                $salesMap[$key] += $this->orderTotal($order);
                $ordersMap[$key]++;
            }
        }

        $recentOrders = Order::query()
            ->with('items')
            ->orderByDesc($dateColumn)
            ->limit(8)
            ->get()
            ->map(function (Order $order) use ($statusColumn, $dateColumn) {
                return (object) [
                    'id' => $order->id,
                    'number' => $order->display_number,
                    'customer' => $order->customer_name
                        ?? $order->name
                        ?? $order->billing_name
                        ?? $order->email
                        ?? 'Customer',
                    'amount' => $this->orderTotal($order),
                    'status' => $statusColumn ? ($order->{$statusColumn} ?? 'pending') : 'pending',
                    'date' => $order->{$dateColumn},
                ];
            });

        return view('admin.dashboard', [
            'period' => $period,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'transactions' => $transactions,
            'totalSales' => $totalSales,
            'payments' => $payments,
            'totalProfit' => $totalProfit,
            'profitIsEstimated' => $profitIsEstimated,
            'statusCounts' => $statusCounts,
            'pendingOrders' => $statusCounts['pending'],
            'processingOrders' => $statusCounts['processing'],
            'shippedOrders' => $statusCounts['shipped'],
            'deliveredOrders' => $statusCounts['delivered'],
            'cancelledOrders' => $statusCounts['cancelled'],
            'chartLabels' => $chartLabels,
            'salesData' => array_values($salesMap),
            'ordersData' => array_values($ordersMap),
            'cartClickData' => array_values($ordersMap),
            'cartQuantityData' => array_values($ordersMap),
            'totalCartClicks' => 0,
            'totalCartQuantity' => 0,
            'totalCartValue' => 0.0,
            'popularProducts' => collect(),
            'recentOrders' => $recentOrders,
        ]);
    }

    private function orderTotal(Order $order): float
    {
        $total = (float) ($order->total_amount ?? $order->total ?? 0);
        if ($total > 0) return $total;

        return (float) $order->items->sum(function ($item) {
            $itemTotal = (float) ($item->total ?? $item->subtotal ?? 0);
            if ($itemTotal > 0) return $itemTotal;

            $unitPrice = (float) ($item->unit_price ?? $item->price ?? 0);
            return $unitPrice * max(1, (int) ($item->quantity ?? 1));
        });
    }

    private function statusGroup(string $status): string
    {
        return match (true) {
            in_array($status, ['processing', 'confirmed', 'accepted', 'paid', 'preparing'], true) => 'processing',
            in_array($status, ['shipped', 'dispatched', 'on the way', 'on_the_way', 'out for delivery', 'out_for_delivery'], true) => 'shipped',
            in_array($status, ['delivered', 'completed', 'complete'], true) => 'delivered',
            in_array($status, ['cancelled', 'canceled', 'refunded', 'failed'], true) => 'cancelled',
            default => 'pending',
        };
    }

    private function periodDates(string $period): array
    {
        $now = now();

        return match ($period) {
            'day' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'year' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            default => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
        };
    }

    private function chartAxis(string $period, Carbon $startDate, Carbon $endDate): array
    {
        if ($period === 'day') {
            $keys = collect(range(0, 23))->map(fn ($hour) => str_pad((string) $hour, 2, '0', STR_PAD_LEFT))->all();
            $labels = collect(range(0, 23))->map(fn ($hour) => Carbon::createFromTime($hour)->format('g A'))->all();
            return [$labels, $keys];
        }

        if ($period === 'year') {
            $keys = collect(range(1, 12))->map(fn ($month) => $startDate->copy()->month($month)->format('Y-m'))->all();
            $labels = collect(range(1, 12))->map(fn ($month) => $startDate->copy()->month($month)->format('M'))->all();
            return [$labels, $keys];
        }

        $dates = collect(CarbonPeriod::create($startDate->copy()->startOfDay(), $endDate->copy()->startOfDay()));
        return [
            $dates->map(fn ($date) => $date->format('M d'))->all(),
            $dates->map(fn ($date) => $date->format('Y-m-d'))->all(),
        ];
    }

    private function emptyDashboard(string $period, array $chartLabels, array $chartKeys)
    {
        return view('admin.dashboard', [
            'period' => $period,
            'totalProducts' => Schema::hasTable('products') ? Product::query()->count() : 0,
            'totalOrders' => 0,
            'transactions' => 0,
            'totalSales' => 0.0,
            'payments' => 0.0,
            'totalProfit' => 0.0,
            'profitIsEstimated' => true,
            'statusCounts' => ['pending'=>0,'processing'=>0,'shipped'=>0,'delivered'=>0,'cancelled'=>0],
            'pendingOrders' => 0,
            'processingOrders' => 0,
            'shippedOrders' => 0,
            'deliveredOrders' => 0,
            'cancelledOrders' => 0,
            'chartLabels' => $chartLabels,
            'salesData' => array_fill(0, count($chartKeys), 0),
            'ordersData' => array_fill(0, count($chartKeys), 0),
            'cartClickData' => array_fill(0, count($chartKeys), 0),
            'cartQuantityData' => array_fill(0, count($chartKeys), 0),
            'totalCartClicks' => 0,
            'totalCartQuantity' => 0,
            'totalCartValue' => 0.0,
            'popularProducts' => collect(),
            'recentOrders' => collect(),
        ]);
    }
}
