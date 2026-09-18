<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FinanceReportController extends Controller
{
    public function income(Request $request)
    {
        return $this->report($request, 'income');
    }

    public function expenses(Request $request)
    {
        return $this->report($request, 'expenses');
    }

    public function profit(Request $request)
    {
        return $this->report($request, 'profit');
    }

    public function storeExpense(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description'  => 'nullable|string|max:1000',
        ]);

        DB::table('expenses')->insert([
            'title'        => $data['title'],
            'category'     => $data['category'],
            'amount'       => $data['amount'],
            'expense_date' => $data['expense_date'],
            'description'  => $data['description'] ?? null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return back()->with('success', 'Expense added successfully.');
    }

    public function destroyExpense(int $expense)
    {
        DB::table('expenses')->where('id', $expense)->delete();

        return back()->with('success', 'Expense deleted successfully.');
    }

    private function report(Request $request, string $type)
    {
        $period = in_array(
            $request->query('period'),
            ['week', 'month', 'year'],
            true
        ) ? $request->query('period') : 'month';

        $now = now();

        [$start, $end] = match ($period) {
            'week' => [
                $now->copy()->subDays(6)->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            'year' => [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear(),
            ],
            default => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
            ],
        };

        $findColumn = function (string $table, array $columns) {
            foreach ($columns as $column) {
                if (Schema::hasColumn($table, $column)) {
                    return $column;
                }
            }

            return null;
        };

        $orders = collect();
        $expensesList = collect();

        $totalOrders = 0;
        $revenue = 0;
        $paidIncome = 0;
        $expenses = 0;

        $amountColumn = null;
        $dateColumn = null;
        $paymentColumn = null;

        if (Schema::hasTable('orders')) {
            $amountColumn = $findColumn('orders', [
                'total_amount',
                'grand_total',
                'total',
                'amount',
                'order_total',
                'subtotal',
            ]);

            $dateColumn = $findColumn('orders', [
                'created_at',
                'order_date',
                'date',
            ]);

            $paymentColumn = $findColumn('orders', [
                'payment_status',
                'billing_status',
                'paid_status',
            ]);

            $ordersQuery = DB::table('orders');

            if ($dateColumn) {
                $ordersQuery->whereBetween($dateColumn, [$start, $end]);
            }

            $totalOrders = (clone $ordersQuery)->count();

            if ($amountColumn) {
                $revenue = (float) (clone $ordersQuery)->sum($amountColumn);
            }

            $paidStatuses = [
                'paid',
                'completed',
                'complete',
                'successful',
                'success',
                'succeeded',
                'captured',
            ];

            if ($amountColumn && $paymentColumn) {
                $paidIncome = (float) (clone $ordersQuery)
                    ->whereIn(
                        DB::raw("LOWER(TRIM(`{$paymentColumn}`))"),
                        $paidStatuses
                    )
                    ->sum($amountColumn);
            } else {
                $paidIncome = $revenue;
            }

            $orders = (clone $ordersQuery)
                ->orderByDesc($dateColumn ?: 'id')
                ->limit(20)
                ->get();
        }

        if (Schema::hasTable('expenses')) {
            $expenseQuery = DB::table('expenses')
                ->whereBetween('expense_date', [
                    $start->toDateString(),
                    $end->toDateString(),
                ]);

            $expenses = (float) (clone $expenseQuery)->sum('amount');

            $expensesList = (clone $expenseQuery)
                ->orderByDesc('expense_date')
                ->orderByDesc('id')
                ->get();
        }

        $profit = $paidIncome - $expenses;

        if ($period === 'year') {
            $points = collect(range(1, 12))
                ->map(fn ($month) => $start->copy()->month($month));

            $phpFormat = 'Y-m';
            $sqlFormat = '%Y-%m';

            $labels = $points
                ->map(fn ($date) => $date->format('M'))
                ->all();
        } else {
            $points = collect(
                CarbonPeriod::create(
                    $start->copy()->startOfDay(),
                    $end->copy()->startOfDay()
                )
            );

            $phpFormat = 'Y-m-d';
            $sqlFormat = '%Y-%m-%d';

            $labels = $points
                ->map(fn ($date) => $date->format('d M'))
                ->all();
        }

        $incomeBuckets = collect();
        $expenseBuckets = collect();

        if ($dateColumn && $amountColumn) {
            $incomeQuery = DB::table('orders')
                ->whereBetween($dateColumn, [$start, $end]);

            if ($paymentColumn) {
                $incomeQuery->whereIn(
                    DB::raw("LOWER(TRIM(`{$paymentColumn}`))"),
                    [
                        'paid',
                        'completed',
                        'complete',
                        'successful',
                        'success',
                        'succeeded',
                        'captured',
                    ]
                );
            }

            $incomeBuckets = $incomeQuery
                ->selectRaw(
                    "DATE_FORMAT(`{$dateColumn}`, '{$sqlFormat}') AS bucket"
                )
                ->selectRaw("SUM(`{$amountColumn}`) AS total")
                ->groupBy('bucket')
                ->pluck('total', 'bucket');
        }

        if (Schema::hasTable('expenses')) {
            $expenseBuckets = DB::table('expenses')
                ->whereBetween('expense_date', [
                    $start->toDateString(),
                    $end->toDateString(),
                ])
                ->selectRaw(
                    "DATE_FORMAT(expense_date, '{$sqlFormat}') AS bucket"
                )
                ->selectRaw('SUM(amount) AS total')
                ->groupBy('bucket')
                ->pluck('total', 'bucket');
        }

        $incomeData = $points->map(
            fn ($date) => (float) (
                $incomeBuckets[$date->format($phpFormat)] ?? 0
            )
        )->all();

        $expenseData = $points->map(
            fn ($date) => (float) (
                $expenseBuckets[$date->format($phpFormat)] ?? 0
            )
        )->all();

        $profitData = array_map(
            fn ($income, $expense) => $income - $expense,
            $incomeData,
            $expenseData
        );

        $values = match ($type) {
            'expenses' => $expenseData,
            'profit'   => $profitData,
            default    => $incomeData,
        };

        $total = match ($type) {
            'expenses' => $expenses,
            'profit'   => $profit,
            default    => $paidIncome,
        };

        return view('admin.finance.report', compact(
            'type',
            'period',
            'start',
            'end',
            'total',
            'totalOrders',
            'revenue',
            'paidIncome',
            'expenses',
            'profit',
            'labels',
            'values',
            'orders',
            'expensesList'
        ));
    }
}
