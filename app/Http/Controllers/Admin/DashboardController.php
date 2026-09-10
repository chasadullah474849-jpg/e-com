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
            ? $request->query('period') : 'week';

        [$startDate, $endDate, $points, $databaseFormat] = $this->periodSettings($period);

        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalCartClicks = CartActivity::count();
        $totalCartQuantity = (int) CartActivity::sum('quantity');
        $totalCartValue = (float) CartActivity::sum('total_amount');

        $periodActivities = CartActivity::query()
            ->whereBetween('created_at', [$startDate, $endDate]);

        $transactions = (clone $periodActivities)->count();
        $totalSales = (float) (clone $periodActivities)->sum('total_amount');
        $payments = $totalSales;
        $totalProfit = $totalSales * 0.25;
        $profitIsEstimated = true;

        $dateSql = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('{$databaseFormat}', created_at)"
            : "DATE_FORMAT(created_at, '{$databaseFormat}')";

        $activities = (clone $periodActivities)
            ->selectRaw("{$dateSql} AS chart_key")
            ->selectRaw('COUNT(*) AS total_clicks')
            ->selectRaw('COALESCE(SUM(quantity), 0) AS total_quantity')
            ->selectRaw('COALESCE(SUM(total_amount), 0) AS total_value')
            ->groupBy('chart_key')
            ->orderBy('chart_key')
            ->get()->keyBy('chart_key');

        $chartLabels = [];
        $cartClickData = [];
        $cartQuantityData = [];
        $salesData = [];

        foreach ($points as $point) {
            $key = $this->pointKey($point, $period);
            $row = $activities->get($key);
            $chartLabels[] = $this->pointLabel($point, $period);
            $cartClickData[] = $row ? (int) $row->total_clicks : 0;
            $cartQuantityData[] = $row ? (int) $row->total_quantity : 0;
            $salesData[] = $row ? round((float) $row->total_value, 2) : 0;
        }

        $totalOrders = 0;
        $pendingOrders = 0;
        $processingOrders = 0;
        $shippedOrders = 0;
        $deliveredOrders = 0;

        if (Schema::hasTable('orders')) {
            $totalOrders = DB::table('orders')->count();
            $statusColumn = $this->firstColumn('orders', ['status', 'order_status', 'delivery_status']);
            $amountColumn = $this->firstColumn('orders', ['grand_total', 'total_amount', 'total_price', 'total', 'amount']);
            $paymentColumn = $this->firstColumn('orders', ['payment_status', 'payment_state']);
            $profitColumn = $this->firstColumn('orders', ['profit', 'net_profit']);

            if ($statusColumn) {
                $counts = DB::table('orders')
                    ->selectRaw("LOWER(TRIM({$statusColumn})) AS order_state, COUNT(*) AS total")
                    ->groupBy('order_state')->pluck('total', 'order_state');

                $sum = fn (array $states) => collect($states)
                    ->sum(fn ($state) => (int) ($counts[$state] ?? 0));

                $pendingOrders = $sum(['pending', 'new', 'pending payment', 'pending_payment']);
                $processingOrders = $sum(['processing', 'confirmed', 'accepted', 'paid']);
                $shippedOrders = $sum(['shipped', 'dispatched', 'on the way', 'on_the_way', 'out for delivery', 'out_for_delivery']);
                $deliveredOrders = $sum(['delivered', 'completed', 'complete']);
            }

            if ($amountColumn) {
                $orderPeriod = DB::table('orders');
                if (Schema::hasColumn('orders', 'created_at')) {
                    $orderPeriod->whereBetween('created_at', [$startDate, $endDate]);
                }
                $orderSales = (float) (clone $orderPeriod)->sum($amountColumn);
                if ($orderSales > 0) {
                    $totalSales = $orderSales;
                }

                if ($paymentColumn) {
                    $payments = (float) (clone $orderPeriod)
                        ->whereIn(DB::raw("LOWER(CAST({$paymentColumn} AS CHAR))"), ['paid', 'completed', 'complete', '1'])
                        ->sum($amountColumn);
                } elseif ($statusColumn) {
                    $payments = (float) (clone $orderPeriod)
                        ->whereIn(DB::raw("LOWER(TRIM({$statusColumn}))"), ['delivered', 'completed', 'complete'])
                        ->sum($amountColumn);
                }

                if ($profitColumn) {
                    $totalProfit = (float) (clone $orderPeriod)->sum($profitColumn);
                    $profitIsEstimated = false;
                } else {
                    $totalProfit = $totalSales * 0.25;
                }
            }
        }

        $popularProducts = CartActivity::query()
            ->with('product:id,name,uuid')
            ->select('product_id', DB::raw('SUM(quantity) AS total_quantity'), DB::raw('COUNT(*) AS total_clicks'))
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)->get();

        return view('admin.dashboard', compact(
            'period', 'totalProducts', 'totalUsers', 'totalCartClicks', 'totalCartQuantity',
            'totalCartValue', 'totalSales', 'payments', 'transactions', 'totalProfit',
            'profitIsEstimated', 'totalOrders', 'pendingOrders', 'processingOrders',
            'shippedOrders', 'deliveredOrders', 'chartLabels', 'cartClickData',
            'cartQuantityData', 'salesData', 'popularProducts'
        ));
    }

    private function firstColumn(string $table, array $columns): ?string
    {
        foreach ($columns as $column) {
            if (Schema::hasColumn($table, $column)) return $column;
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
