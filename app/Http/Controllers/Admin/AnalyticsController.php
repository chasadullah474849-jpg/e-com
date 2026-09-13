<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = in_array($request->query('period'), ['day', 'week', 'month', 'year'], true)
            ? $request->query('period')
            : 'month';

        [$startDate, $endDate, $points, $databaseFormat] = $this->periodSettings($period);

        $data = [
            'period' => $period,
            'totalOrders' => 0,
            'totalRevenue' => 0,
            'paidRevenue' => 0,
            'totalExpenses' => 0,
            'grossProfit' => 0,
            'netProfit' => 0,
            'pendingOrders' => 0,
            'processingOrders' => 0,
            'shippedOrders' => 0,
            'deliveredOrders' => 0,
            'cancelledOrders' => 0,
            'chartLabels' => [],
            'revenueData' => [],
            'profitData' => [],
            'orderData' => [],
            'recentOrders' => collect(),
            'bestSellingProducts' => collect(),
            'currency' => config('app.currency_symbol', '$'),
        ];

        foreach ($points as $point) {
            $data['chartLabels'][] = $this->pointLabel($point, $period);
        }

        if (!Schema::hasTable('orders')) {
            return view('admin.analytics.index', $data);
        }

        $statusColumn = $this->firstColumn('orders', ['status', 'order_status', 'delivery_status']);
        $amountColumn = $this->firstColumn('orders', ['grand_total', 'total_amount', 'total_price', 'total', 'amount']);
        $paymentColumn = $this->firstColumn('orders', ['payment_status', 'payment_state']);
        $profitColumn = $this->firstColumn('orders', ['profit', 'net_profit']);
        $costColumn = $this->firstColumn('orders', ['cost', 'total_cost', 'cost_price']);
        $expenseColumn = $this->firstColumn('orders', ['expense', 'expenses', 'total_expense']);

        $orders = DB::table('orders');
        if (Schema::hasColumn('orders', 'created_at')) {
            $orders->whereBetween('created_at', [$startDate, $endDate]);
        }

        $data['totalOrders'] = (clone $orders)->count();
        $data['totalRevenue'] = $amountColumn ? (float) (clone $orders)->sum($amountColumn) : 0;

        if ($paymentColumn && $amountColumn) {
            $data['paidRevenue'] = (float) (clone $orders)
                ->whereIn(DB::raw("LOWER(CAST({$paymentColumn} AS CHAR))"), ['paid', 'completed', 'complete', '1'])
                ->sum($amountColumn);
        } elseif ($statusColumn && $amountColumn) {
            $data['paidRevenue'] = (float) (clone $orders)
                ->whereIn(DB::raw("LOWER(TRIM({$statusColumn}))"), ['delivered', 'completed', 'complete'])
                ->sum($amountColumn);
        } else {
            $data['paidRevenue'] = $data['totalRevenue'];
        }

        if ($expenseColumn) {
            $data['totalExpenses'] = (float) (clone $orders)->sum($expenseColumn);
        } elseif ($costColumn) {
            $data['totalExpenses'] = (float) (clone $orders)->sum($costColumn);
        }

        if ($profitColumn) {
            $data['grossProfit'] = (float) (clone $orders)->sum($profitColumn);
        } elseif ($data['totalExpenses'] > 0) {
            $data['grossProfit'] = $data['totalRevenue'] - $data['totalExpenses'];
        } else {
            $data['grossProfit'] = $data['totalRevenue'] * 0.25;
        }
        $data['netProfit'] = $data['grossProfit'];

        if ($statusColumn) {
            $counts = (clone $orders)
                ->selectRaw("LOWER(TRIM({$statusColumn})) AS order_state, COUNT(*) AS total")
                ->groupBy('order_state')
                ->pluck('total', 'order_state');

            $sum = fn (array $states) => collect($states)->sum(
                fn ($state) => (int) ($counts[$state] ?? 0)
            );

            $data['pendingOrders'] = $sum(['pending', 'new', 'pending payment', 'pending_payment']);
            $data['processingOrders'] = $sum(['processing', 'confirmed', 'accepted', 'paid']);
            $data['shippedOrders'] = $sum(['shipped', 'dispatched', 'on the way', 'on_the_way', 'out for delivery', 'out_for_delivery']);
            $data['deliveredOrders'] = $sum(['delivered', 'completed', 'complete']);
            $data['cancelledOrders'] = $sum(['cancelled', 'canceled', 'refunded', 'failed']);
        }

        $grouped = collect();
        if (Schema::hasColumn('orders', 'created_at')) {
            $dateSql = DB::connection()->getDriverName() === 'sqlite'
                ? "strftime('{$databaseFormat}', created_at)"
                : "DATE_FORMAT(created_at, '{$databaseFormat}')";

            $valueSql = $amountColumn ? "COALESCE(SUM({$amountColumn}), 0)" : '0';
            $profitSql = $profitColumn
                ? "COALESCE(SUM({$profitColumn}), 0)"
                : ($costColumn && $amountColumn
                    ? "COALESCE(SUM({$amountColumn} - {$costColumn}), 0)"
                    : "({$valueSql} * 0.25)");

            $grouped = (clone $orders)
                ->selectRaw("{$dateSql} AS chart_key")
                ->selectRaw('COUNT(*) AS order_count')
                ->selectRaw("{$valueSql} AS revenue")
                ->selectRaw("{$profitSql} AS profit")
                ->groupBy('chart_key')
                ->orderBy('chart_key')
                ->get()
                ->keyBy('chart_key');
        }

        foreach ($points as $point) {
            $row = $grouped->get($this->pointKey($point, $period));
            $data['revenueData'][] = $row ? round((float) $row->revenue, 2) : 0;
            $data['profitData'][] = $row ? round((float) $row->profit, 2) : 0;
            $data['orderData'][] = $row ? (int) $row->order_count : 0;
        }

        $data['recentOrders'] = (clone $orders)
            ->orderByDesc(Schema::hasColumn('orders', 'created_at') ? 'created_at' : 'id')
            ->limit(10)
            ->get();

        $data['bestSellingProducts'] = $this->bestSellingProducts($startDate, $endDate);

        return view('admin.analytics.index', $data);
    }

    private function bestSellingProducts(Carbon $startDate, Carbon $endDate)
    {
        $itemsTable = Schema::hasTable('order_items') ? 'order_items'
            : (Schema::hasTable('order_details') ? 'order_details' : null);

        if (!$itemsTable || !Schema::hasTable('products')) {
            return collect();
        }

        $productId = $this->firstColumn($itemsTable, ['product_id', 'product']);
        $quantity = $this->firstColumn($itemsTable, ['quantity', 'qty']);
        $price = $this->firstColumn($itemsTable, ['total', 'subtotal', 'total_amount', 'price']);

        if (!$productId || !$quantity) {
            return collect();
        }

        $query = DB::table($itemsTable)
            ->leftJoin('products', "{$itemsTable}.{$productId}", '=', 'products.id')
            ->selectRaw("products.name AS product_name, SUM({$itemsTable}.{$quantity}) AS sold_quantity")
            ->selectRaw($price ? "SUM({$itemsTable}.{$price}) AS sales_amount" : '0 AS sales_amount')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('sold_quantity')
            ->limit(5);

        if (Schema::hasColumn($itemsTable, 'created_at')) {
            $query->whereBetween("{$itemsTable}.created_at", [$startDate, $endDate]);
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
            $points = collect(range(0, 23))->map(fn ($hour) => $start->copy()->addHours($hour));
            return [$start, $now->copy()->endOfDay(), $points, '%Y-%m-%d %H'];
        }
        if ($period === 'month') {
            $start = $now->copy()->startOfMonth();
            $points = collect(range(0, $now->daysInMonth - 1))->map(fn ($day) => $start->copy()->addDays($day));
            return [$start, $now->copy()->endOfMonth(), $points, '%Y-%m-%d'];
        }
        if ($period === 'year') {
            $start = $now->copy()->startOfYear();
            $points = collect(range(0, 11))->map(fn ($month) => $start->copy()->addMonths($month));
            return [$start, $now->copy()->endOfYear(), $points, '%Y-%m'];
        }
        $start = $now->copy()->subDays(6)->startOfDay();
        $points = collect(range(0, 6))->map(fn ($day) => $start->copy()->addDays($day));
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
