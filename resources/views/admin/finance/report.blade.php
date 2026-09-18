<!DOCTYPE html>
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ asset('admins/assets') }}/"
>
<head>
    @include('admin.header')

    <style>
        .finance-page .card {
            border: 0;
            border-radius: 0.8rem;
            box-shadow: 0 2px 12px rgba(67, 89, 113, 0.1);
        }

        .finance-nav .btn {
            min-width: 105px;
        }

        .finance-chart {
            width: 100%;
            min-height: 330px;
        }

        .finance-metric {
            color: #566a7f;
            font-size: 1.6rem;
            font-weight: 700;
        }

        .chart-card .card-header {
            padding: 1.5rem;
        }

        .chart-card .card-body {
            padding: 0 1.5rem 1.5rem;
        }

        .expense-description {
            display: block;
            max-width: 260px;
            overflow: hidden;
            color: #a1acb8;
            font-size: 0.75rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (max-width: 575.98px) {
            .finance-nav {
                display: flex;
                width: 100%;
            }

            .finance-nav .btn {
                min-width: 0;
                flex: 1;
            }

            .finance-metric {
                font-size: 1.25rem;
            }
        }
    </style>
</head>

<body>
@php
    $currency = config('app.currency_symbol', '$');

    $titles = [
        'income' => 'Income Report',
        'expenses' => 'Expenses Report',
        'profit' => 'Profit Report',
    ];

    $colors = [
        'income' => 'primary',
        'expenses' => 'danger',
        'profit' => 'success',
    ];

    $selectedTitle = $titles[$type] ?? 'Finance Report';
    $selectedColor = $colors[$type] ?? 'primary';
@endphp

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        @include('admin.sidebar')

        <div class="layout-page">

            @include('admin.nav')

            <div class="content-wrapper finance-page">

                <main class="container-xxl flex-grow-1 container-p-y">

                    {{-- Page heading --}}
                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4"
                    >
                        <div>
                            <h4 class="mb-1">{{ $selectedTitle }}</h4>

                            <p class="text-muted mb-0">
                                Live financial data from
                                {{ $start->format('d M Y') }}
                                to
                                {{ $end->format('d M Y') }}
                            </p>
                        </div>

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="btn btn-outline-secondary"
                        >
                            <i class="bx bx-arrow-back me-1"></i>
                            Dashboard
                        </a>
                    </div>

                    {{-- Finance navigation --}}
                    <div class="finance-nav btn-group mb-4">

                        <a
                            href="{{ route('admin.finance.income', [
                                'period' => $period
                            ]) }}"
                            class="btn {{
                                $type === 'income'
                                    ? 'btn-primary'
                                    : 'btn-outline-primary'
                            }}"
                        >
                            Income
                        </a>

                        <a
                            href="{{ route('admin.finance.expenses', [
                                'period' => $period
                            ]) }}"
                            class="btn {{
                                $type === 'expenses'
                                    ? 'btn-danger'
                                    : 'btn-outline-danger'
                            }}"
                        >
                            Expenses
                        </a>

                        <a
                            href="{{ route('admin.finance.profit', [
                                'period' => $period
                            ]) }}"
                            class="btn {{
                                $type === 'profit'
                                    ? 'btn-success'
                                    : 'btn-outline-success'
                            }}"
                        >
                            Profit
                        </a>
                    </div>

                    {{-- Success message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <i class="bx bx-check-circle me-1"></i>

                            {{ session('success') }}

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                            ></button>
                        </div>
                    @endif

                    {{-- Validation errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Please correct the following:</strong>

                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Summary cards --}}
                    <div class="row g-4 mb-4">

                        <div class="col-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <span
                                        class="badge bg-label-{{ $selectedColor }} mb-3"
                                    >
                                        Selected Total
                                    </span>

                                    <div class="finance-metric">
                                        {{ $currency }}{{ number_format(
                                            (float) $total,
                                            2
                                        ) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <span class="badge bg-label-primary mb-3">
                                        Revenue
                                    </span>

                                    <div class="finance-metric">
                                        {{ $currency }}{{ number_format(
                                            (float) $revenue,
                                            2
                                        ) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <span class="badge bg-label-info mb-3">
                                        Paid Income
                                    </span>

                                    <div class="finance-metric">
                                        {{ $currency }}{{ number_format(
                                            (float) $paidIncome,
                                            2
                                        ) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <span class="badge bg-label-warning mb-3">
                                        Orders
                                    </span>

                                    <div class="finance-metric">
                                        {{ number_format($totalOrders) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Income/expense/profit totals --}}
                    <div class="row g-4 mb-4">

                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <small class="text-muted d-block mb-2">
                                        Paid Income
                                    </small>

                                    <h4 class="text-primary mb-0">
                                        {{ $currency }}{{ number_format(
                                            (float) $paidIncome,
                                            2
                                        ) }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <small class="text-muted d-block mb-2">
                                        Total Expenses
                                    </small>

                                    <h4 class="text-danger mb-0">
                                        {{ $currency }}{{ number_format(
                                            (float) $expenses,
                                            2
                                        ) }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <small class="text-muted d-block mb-2">
                                        Net Profit
                                    </small>

                                    <h4
                                        class="{{
                                            $profit >= 0
                                                ? 'text-success'
                                                : 'text-danger'
                                        }} mb-0"
                                    >
                                        {{ $currency }}{{ number_format(
                                            (float) $profit,
                                            2
                                        ) }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Finance chart --}}
                    <div class="card chart-card mb-4">

                        <div
                            class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3"
                        >
                            <h5 class="mb-0">
                                {{ $selectedTitle }} Chart
                            </h5>

                            <div class="btn-group btn-group-sm">

                                @foreach([
                                    'week' => 'Week',
                                    'month' => 'Month',
                                    'year' => 'Year'
                                ] as $periodKey => $periodLabel)

                                    <a
                                        href="{{ request()->url() }}?period={{
                                            $periodKey
                                        }}"
                                        class="btn {{
                                            $period === $periodKey
                                                ? 'btn-primary'
                                                : 'btn-outline-primary'
                                        }}"
                                    >
                                        {{ $periodLabel }}
                                    </a>

                                @endforeach

                            </div>
                        </div>

                        <div class="card-body">
                            <div
                                id="financeChart"
                                class="finance-chart"
                            ></div>
                        </div>

                    </div>

                    {{-- Expenses management --}}
                    @if($type === 'expenses')

                        <div class="row g-4 mb-4">

                            {{-- Add expense form --}}
                            <div class="col-lg-4">
                                <div class="card h-100">

                                    <div class="card-header">
                                        <h5 class="mb-0">Add Expense</h5>
                                    </div>

                                    <div class="card-body">

                                        <form
                                            method="POST"
                                            action="{{
                                                route(
                                                    'admin.finance.expenses.store'
                                                )
                                            }}"
                                        >
                                            @csrf

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Expense Title
                                                </label>

                                                <input
                                                    type="text"
                                                    name="title"
                                                    value="{{ old('title') }}"
                                                    class="form-control"
                                                    placeholder="Enter expense title"
                                                    required
                                                >
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Category
                                                </label>

                                                <select
                                                    name="category"
                                                    class="form-select"
                                                    required
                                                >
                                                    @foreach([
                                                        'General',
                                                        'Products',
                                                        'Shipping',
                                                        'Marketing',
                                                        'Salary',
                                                        'Utilities',
                                                        'Refund',
                                                        'Other'
                                                    ] as $category)

                                                        <option
                                                            value="{{ $category }}"
                                                            {{
                                                                old('category')
                                                                === $category
                                                                    ? 'selected'
                                                                    : ''
                                                            }}
                                                        >
                                                            {{ $category }}
                                                        </option>

                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Amount
                                                </label>

                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        {{ $currency }}
                                                    </span>

                                                    <input
                                                        type="number"
                                                        name="amount"
                                                        value="{{ old('amount') }}"
                                                        class="form-control"
                                                        min="0.01"
                                                        step="0.01"
                                                        placeholder="0.00"
                                                        required
                                                    >
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Expense Date
                                                </label>

                                                <input
                                                    type="date"
                                                    name="expense_date"
                                                    value="{{
                                                        old(
                                                            'expense_date',
                                                            now()->toDateString()
                                                        )
                                                    }}"
                                                    class="form-control"
                                                    required
                                                >
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Description
                                                </label>

                                                <textarea
                                                    name="description"
                                                    class="form-control"
                                                    rows="3"
                                                    placeholder="Optional description"
                                                >{{ old('description') }}</textarea>
                                            </div>

                                            <button
                                                type="submit"
                                                class="btn btn-danger w-100"
                                            >
                                                <i class="bx bx-plus me-1"></i>
                                                Add Expense
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </div>

                            {{-- Expense records --}}
                            <div class="col-lg-8">
                                <div class="card h-100">

                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            Expense Records
                                        </h5>
                                    </div>

                                    <div class="table-responsive">
                                        <table
                                            class="table table-hover align-middle mb-0"
                                        >
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Expense</th>
                                                    <th>Category</th>
                                                    <th>Amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse(
                                                    $expensesList as $expense
                                                )
                                                    <tr>
                                                        <td>
                                                            {{
                                                                \Carbon\Carbon::parse(
                                                                    $expense->expense_date
                                                                )->format('d M Y')
                                                            }}
                                                        </td>

                                                        <td>
                                                            <strong>
                                                                {{
                                                                    $expense->title
                                                                }}
                                                            </strong>

                                                            @if(
                                                                $expense->description
                                                            )
                                                                <small
                                                                    class="expense-description"
                                                                >
                                                                    {{
                                                                        $expense->description
                                                                    }}
                                                                </small>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <span
                                                                class="badge bg-label-secondary"
                                                            >
                                                                {{
                                                                    $expense->category
                                                                }}
                                                            </span>
                                                        </td>

                                                        <td
                                                            class="fw-bold text-danger"
                                                        >
                                                            {{
                                                                $currency
                                                            }}{{
                                                                number_format(
                                                                    (float)
                                                                    $expense->amount,
                                                                    2
                                                                )
                                                            }}
                                                        </td>

                                                        <td>
                                                            <form
                                                                method="POST"
                                                                action="{{
                                                                    route(
                                                                        'admin.finance.expenses.destroy',
                                                                        $expense->id
                                                                    )
                                                                }}"
                                                            >
                                                                @csrf
                                                                @method('DELETE')

                                                                <button
                                                                    type="submit"
                                                                    class="btn btn-sm btn-icon btn-outline-danger"
                                                                    onclick="return confirm('Delete this expense?')"
                                                                    title="Delete expense"
                                                                >
                                                                    <i
                                                                        class="bx bx-trash"
                                                                    ></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td
                                                            colspan="5"
                                                            class="text-center text-muted py-5"
                                                        >
                                                            <i
                                                                class="bx bx-receipt fs-1 d-block mb-2"
                                                            ></i>

                                                            No expense records
                                                            found for this
                                                            period.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>

                    @endif

                    {{-- Recent orders --}}
                    <div class="card">

                        <div
                            class="card-header d-flex justify-content-between align-items-center"
                        >
                            <div>
                                <h5 class="mb-1">Recent Orders</h5>

                                <small class="text-muted">
                                    Orders within the selected period
                                </small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">
                                    <tr>
                                        <th>Order</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Payment</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($orders as $order)

                                        @php
                                            $orderNumber =
                                                $order->order_no
                                                ?? $order->order_number
                                                ?? $order->id;

                                            $customerName =
                                                $order->customer_name
                                                ?? $order->name
                                                ?? $order->email
                                                ?? 'Guest';

                                            $orderDate =
                                                $order->created_at
                                                ?? $order->order_date
                                                ?? null;

                                            $paymentStatus =
                                                $order->payment_status
                                                ?? $order->billing_status
                                                ?? 'pending';

                                            $orderAmount =
                                                $order->total_amount
                                                ?? $order->grand_total
                                                ?? $order->total
                                                ?? $order->amount
                                                ?? 0;

                                            $paidStatus = in_array(
                                                strtolower(
                                                    trim($paymentStatus)
                                                ),
                                                [
                                                    'paid',
                                                    'success',
                                                    'successful',
                                                    'succeeded',
                                                    'completed',
                                                    'complete',
                                                    'captured'
                                                ],
                                                true
                                            );
                                        @endphp

                                        <tr>
                                            <td
                                                class="fw-semibold text-primary"
                                            >
                                                #{{ $orderNumber }}
                                            </td>

                                            <td>{{ $customerName }}</td>

                                            <td>
                                                {{
                                                    $orderDate
                                                        ? \Carbon\Carbon::parse(
                                                            $orderDate
                                                        )->format('d M Y')
                                                        : '—'
                                                }}
                                            </td>

                                            <td>
                                                <span
                                                    class="badge bg-label-{{
                                                        $paidStatus
                                                            ? 'success'
                                                            : 'warning'
                                                    }}"
                                                >
                                                    {{
                                                        ucfirst(
                                                            str_replace(
                                                                '_',
                                                                ' ',
                                                                $paymentStatus
                                                            )
                                                        )
                                                    }}
                                                </span>
                                            </td>

                                            <td class="fw-semibold">
                                                {{ $currency }}{{
                                                    number_format(
                                                        (float) $orderAmount,
                                                        2
                                                    )
                                                }}
                                            </td>
                                        </tr>

                                    @empty

                                        <tr>
                                            <td
                                                colspan="5"
                                                class="text-center text-muted py-5"
                                            >
                                                <i
                                                    class="bx bx-cart fs-1 d-block mb-2"
                                                ></i>

                                                No orders found for this period.
                                            </td>
                                        </tr>

                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                </main>

                @include('admin.footer')

            </div>
        </div>
    </div>

    <div class="layout-overlay layout-menu-toggle"></div>
</div>

@include('admin.js')

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof ApexCharts === 'undefined') {
        return;
    }

    const chartElement = document.querySelector('#financeChart');

    if (!chartElement) {
        return;
    }

    const reportType = @json($type);
    const currency = @json($currency);

    const chartColor = reportType === 'expenses'
        ? '#ff3e1d'
        : reportType === 'profit'
            ? '#71dd37'
            : '#696cff';

    const chartName = reportType === 'expenses'
        ? 'Expenses'
        : reportType === 'profit'
            ? 'Profit'
            : 'Income';

    const chart = new ApexCharts(chartElement, {
        chart: {
            type: 'area',
            height: 330,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            }
        },

        series: [
            {
                name: chartName,
                data: @json($values)
            }
        ],

        colors: [chartColor],

        stroke: {
            curve: 'smooth',
            width: 3
        },

        fill: {
            type: 'gradient',
            gradient: {
                opacityFrom: 0.35,
                opacityTo: 0.05
            }
        },

        dataLabels: {
            enabled: false
        },

        xaxis: {
            categories: @json($labels),
            labels: {
                rotate: -35,
                hideOverlappingLabels: true
            }
        },

        yaxis: {
            labels: {
                formatter: function (value) {
                    return currency + Number(value).toLocaleString();
                }
            }
        },

        tooltip: {
            y: {
                formatter: function (value) {
                    return currency + Number(value).toLocaleString();
                }
            }
        },

        grid: {
            borderColor: '#eceef1'
        },

        noData: {
            text: 'No financial data available'
        }
    });

    chart.render();
});
</script>

</body>
</html>
