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


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = in_array($request->query('period'), ['day', 'week', 'month', 'year'], true)
            ? $request->query('period')
            : 'week';

        [$startDate, $endDate, $points, $databaseFormat] = $this->periodSettings($period);

        $totalProducts = Schema::hasTable('products') ? DB::table('products')->count() : 0;
        $totalUsers = Schema::hasTable('users') ? DB::table('users')->count() : 0;

        $totalCartClicks = 0;
        $totalCartQuantity = 0;
        $totalCartValue = 0;

        if (Schema::hasTable('cart_activities')) {
            $cartQuery = DB::table('cart_activities');
            $totalCartClicks = (clone $cartQuery)->count();

            if (Schema::hasColumn('cart_activities', 'quantity')) {
                $totalCartQuantity = (int) (clone $cartQuery)->sum('quantity');
            }

            $cartAmountColumn = $this->firstColumn(
                'cart_activities',
                ['total_amount', 'amount', 'total', 'price']
            );

            if ($cartAmountColumn) {
                $totalCartValue = (float) (clone $cartQuery)->sum($cartAmountColumn);
            }
        }

        $totalOrders = 0;
        $pendingOrders = 0;
        $processingOrders = 0;
        $shippedOrders = 0;
        $deliveredOrders = 0;
        $cancelledOrders = 0;
        $transactions = 0;
        $totalSales = 0;
        $payments = 0;
        $totalProfit = 0;
        $profitIsEstimated = true;
        $recentOrders = collect();
        $popularProducts = collect();

        $chartLabels = [];
        $salesData = [];
        $cartClickData = [];
        $cartQuantityData = [];

        foreach ($points as $point) {
            $chartLabels[] = $this->pointLabel($point, $period);
            $salesData[] = 0;
            $cartClickData[] = 0;
            $cartQuantityData[] = 0;
        }

        if (Schema::hasTable('orders')) {
            $statusColumn = $this->firstColumn(
                'orders',
                ['status', 'order_status', 'delivery_status']
            );

            $amountColumn = $this->firstColumn(
                'orders',
                [
                    'grand_total',
                    'total_amount',
                    'total_price',
                    'payable_amount',
                    'order_total',
                    'total',
                    'amount'
                ]
            );

            $paymentColumn = $this->firstColumn(
                'orders',
                ['payment_status', 'payment_state', 'is_paid']
            );

            $profitColumn = $this->firstColumn(
                'orders',
                ['profit', 'net_profit', 'gross_profit']
            );

            $costColumn = $this->firstColumn(
                'orders',
                ['total_cost', 'cost', 'cost_price', 'purchase_total']
            );

            /*
             * All-time values are used for the order-status card so that an
             * older pending/shipped order does not disappear when Week is selected.
             */
            $allOrders = DB::table('orders');
            $totalOrders = (clone $allOrders)->count();

            if ($statusColumn) {
                $statusCounts = (clone $allOrders)
                    ->selectRaw("LOWER(TRIM(CAST({$statusColumn} AS CHAR))) AS order_state")
                    ->selectRaw('COUNT(*) AS total')
                    ->groupBy('order_state')
                    ->pluck('total', 'order_state');

                $sumStatuses = fn (array $statuses) => collect($statuses)->sum(
                    fn ($status) => (int) ($statusCounts[$status] ?? 0)
                );

                $pendingOrders = $sumStatuses([
                    'pending', 'new', 'pending payment', 'pending_payment', 'unpaid'
                ]);

                $processingOrders = $sumStatuses([
                    'processing', 'confirmed', 'accepted', 'paid', 'preparing'
                ]);

                $shippedOrders = $sumStatuses([
                    'shipped', 'dispatched', 'on the way', 'on_the_way',
                    'out for delivery', 'out_for_delivery'
                ]);

                $deliveredOrders = $sumStatuses([
                    'delivered', 'completed', 'complete'
                ]);

                $cancelledOrders = $sumStatuses([
                    'cancelled', 'canceled', 'refunded', 'failed', 'rejected'
                ]);
            }

            /* Selected-period query powers Sales, Payments, Profit and graphs. */
            $periodOrders = DB::table('orders');

            if (Schema::hasColumn('orders', 'created_at')) {
                $periodOrders->whereBetween('created_at', [$startDate, $endDate]);
            }

            $transactions = (clone $periodOrders)->count();

            if ($amountColumn) {
                $totalSales = (float) (clone $periodOrders)->sum($amountColumn);

                if ($paymentColumn) {
                    $payments = (float) (clone $periodOrders)
                        ->whereIn(
                            DB::raw("LOWER(TRIM(CAST({$paymentColumn} AS CHAR)))"),
                            ['paid', 'completed', 'complete', 'success', 'successful', '1']
                        )
                        ->sum($amountColumn);
                } else {
                    /* No payment_status column: order total is treated as payment value. */
                    $payments = $totalSales;
                }

                if ($profitColumn) {
                    $totalProfit = (float) (clone $periodOrders)->sum($profitColumn);
                    $profitIsEstimated = false;
                } elseif ($costColumn) {
                    $totalCost = (float) (clone $periodOrders)->sum($costColumn);
                    $totalProfit = $totalSales - $totalCost;
                    $profitIsEstimated = false;
                } else {
                    /* Change 0.25 if your store uses another profit percentage. */
                    $totalProfit = $totalSales * 0.25;
                    $profitIsEstimated = true;
                }
            }

            if (Schema::hasColumn('orders', 'created_at')) {
                $dateSql = DB::connection()->getDriverName() === 'sqlite'
                    ? "strftime('{$databaseFormat}', created_at)"
                    : "DATE_FORMAT(created_at, '{$databaseFormat}')";

                $amountSql = $amountColumn
                    ? "COALESCE(SUM({$amountColumn}), 0)"
                    : '0';

                $groupedOrders = (clone $periodOrders)
                    ->selectRaw("{$dateSql} AS chart_key")
                    ->selectRaw('COUNT(*) AS order_count')
                    ->selectRaw("{$amountSql} AS sales_total")
                    ->groupBy('chart_key')
                    ->orderBy('chart_key')
                    ->get()
                    ->keyBy('chart_key');

                $salesData = [];
                $cartClickData = [];
                $cartQuantityData = [];

                foreach ($points as $point) {
                    $row = $groupedOrders->get($this->pointKey($point, $period));
                    $salesData[] = $row ? round((float) $row->sales_total, 2) : 0;
                    $cartClickData[] = $row ? (int) $row->order_count : 0;
                    $cartQuantityData[] = $row ? (int) $row->order_count : 0;
                }
            }

            $recentOrders = DB::table('orders')
                ->orderByDesc(
                    Schema::hasColumn('orders', 'created_at') ? 'created_at' : 'id'
                )
                ->limit(6)
                ->get();

            $popularProducts = $this->popularProducts($startDate, $endDate);
        }

        return view('admin.index', compact(
            'period',
            'totalProducts',
            'totalUsers',
            'totalCartClicks',
            'totalCartQuantity',
            'totalCartValue',
            'totalSales',
            'payments',
            'transactions',
            'totalProfit',
            'profitIsEstimated',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'chartLabels',
            'cartClickData',
            'cartQuantityData',
            'salesData',
            'recentOrders',
            'popularProducts'
        ));
    }

    private function popularProducts(Carbon $startDate, Carbon $endDate)
    {
        $itemsTable = Schema::hasTable('order_items')
            ? 'order_items'
            : (Schema::hasTable('order_details') ? 'order_details' : null);

        if (!$itemsTable || !Schema::hasTable('products')) {
            return collect();
        }

        $productColumn = $this->firstColumn(
            $itemsTable,
            ['product_id', 'product']
        );

        $quantityColumn = $this->firstColumn(
            $itemsTable,
            ['quantity', 'qty']
        );

        if (!$productColumn || !$quantityColumn) {
            return collect();
        }

        $query = DB::table($itemsTable)
            ->leftJoin(
                'products',
                "{$itemsTable}.{$productColumn}",
                '=',
                'products.id'
            )
            ->select(
                'products.id',
                'products.name',
                DB::raw("SUM({$itemsTable}.{$quantityColumn}) AS total_quantity"),
                DB::raw('COUNT(*) AS total_clicks')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5);

        if (Schema::hasColumn($itemsTable, 'created_at')) {
            $query->whereBetween(
                "{$itemsTable}.created_at",
                [$startDate, $endDate]
            );
        }

        return $query->get();
    }

    private function firstColumn(string $table, array $columns): ?string
    {
        foreach ($columns as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return null;
    }

    private function periodSettings(string $period): array
    {
        $now = now();

        if ($period === 'day') {
            $start = $now->copy()->startOfDay();
            $points = collect(range(0, 23))->map(
                fn ($hour) => $start->copy()->addHours($hour)
            );

            return [$start, $now->copy()->endOfDay(), $points, '%Y-%m-%d %H'];
        }

        if ($period === 'month') {
            $start = $now->copy()->startOfMonth();
            $points = collect(range(0, $now->daysInMonth - 1))->map(
                fn ($day) => $start->copy()->addDays($day)
            );

            return [$start, $now->copy()->endOfMonth(), $points, '%Y-%m-%d'];
        }

        if ($period === 'year') {
            $start = $now->copy()->startOfYear();
            $points = collect(range(0, 11))->map(
                fn ($month) => $start->copy()->addMonths($month)
            );

            return [$start, $now->copy()->endOfYear(), $points, '%Y-%m'];
        }

        $start = $now->copy()->subDays(6)->startOfDay();
        $points = collect(range(0, 6))->map(
            fn ($day) => $start->copy()->addDays($day)
        );

        return [$start, $now->copy()->endOfDay(), $points, '%Y-%m-%d'];
    }

    private function pointKey(Carbon $point, string $period): string
    {
        return match ($period) {
            'day' => $point->format('Y-m-d H'),
            'year' => $point->format('Y-m'),
            default => $point->format('Y-m-d'),
        };
    }

    private function pointLabel(Carbon $point, string $period): string
    {
        return match ($period) {
            'day' => $point->format('g A'),
            'month' => $point->format('d M'),
            'year' => $point->format('M'),
            default => $point->format('D'),
        };
    }
}
