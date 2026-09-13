<!DOCTYPE html>
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
>
<head>
    @include('admin.header')

    <style>
        .analytics-page {
            --purple: #696cff;
            --green: #71dd37;
            --cyan: #03c3ec;
            --red: #ff3e1d;
            --orange: #ffab00;
            --ink: #566a7f;
            --muted: #a1acb8;
        }

        .analytics-page .card {
            height: 100%;
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(67, 89, 113, 0.12);
        }

        .metric-icon {
            display: grid;
            width: 46px;
            height: 46px;
            place-items: center;
            border-radius: 0.65rem;
            font-size: 1.4rem;
        }

        .bg-purple {
            color: var(--purple);
            background: rgba(105, 108, 255, 0.13);
        }

        .bg-green {
            color: var(--green);
            background: rgba(113, 221, 55, 0.14);
        }

        .bg-cyan {
            color: var(--cyan);
            background: rgba(3, 195, 236, 0.13);
        }

        .bg-red {
            color: var(--red);
            background: rgba(255, 62, 29, 0.12);
        }

        .bg-orange {
            color: var(--orange);
            background: rgba(255, 171, 0, 0.14);
        }

        .metric-label {
            margin: 15px 0 4px;
            color: var(--muted);
            font-size: 0.82rem;
        }

        .metric-value {
            margin: 0;
            color: var(--ink);
            font-size: 1.55rem;
            font-weight: 700;
        }

        .chart-box {
            position: relative;
            height: 350px;
        }

        .status-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eceef1;
        }

        .status-box:last-child {
            border-bottom: 0;
        }

        .status-name {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--ink);
            font-weight: 600;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .analytics-page .table {
            color: var(--ink);
        }

        .analytics-page .table thead th {
            border-bottom: 1px solid #e7e9ed;
            color: var(--muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .analytics-page .table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .empty-state {
            padding: 45px 15px !important;
            color: var(--muted);
            text-align: center;
        }

        .period-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        @media (max-width: 575.98px) {
            .chart-box {
                height: 280px;
            }

            .period-buttons {
                width: 100%;
            }

            .period-buttons .btn {
                flex: 1;
                padding-right: 8px;
                padding-left: 8px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            {{-- Admin sidebar --}}
            @include('admin.sidebar')

            <div class="layout-page">

                {{-- Admin navbar --}}
                @include('admin.nav')

                <div class="content-wrapper">
                    <main
                        class="
                            container-xxl
                            flex-grow-1
                            container-p-y
                            analytics-page
                        "
                    >
                        {{-- Page heading and date filters --}}
                        <div
                            class="
                                d-flex
                                flex-wrap
                                align-items-center
                                justify-content-between
                                gap-3
                                mb-4
                            "
                        >
                            <div>
                                <h4 class="fw-bold mb-1">
                                    Sales & Profit Analytics
                                </h4>

                                <p class="text-muted mb-0">
                                    Live information calculated from your orders.
                                </p>
                            </div>

                            <div
                                class="btn-group period-buttons"
                                role="group"
                                aria-label="Analytics period"
                            >
                                @foreach ([
                                    'day' => 'Today',
                                    'week' => '7 Days',
                                    'month' => 'Month',
                                    'year' => 'Year'
                                ] as $key => $label)
                                    <a
                                        href="{{ route(
                                            'admin.analytics.index',
                                            ['period' => $key]
                                        ) }}"
                                        class="
                                            btn
                                            {{
                                                $period === $key
                                                    ? 'btn-primary'
                                                    : 'btn-outline-primary'
                                            }}
                                        "
                                    >
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Financial summary cards --}}
                        <div class="row g-4 mb-4">
                            <div class="col-12 col-sm-6 col-xl">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="metric-icon bg-purple">
                                            <i class="bx bx-dollar-circle"></i>
                                        </span>

                                        <p class="metric-label">
                                            Total Revenue
                                        </p>

                                        <h5 class="metric-value">
                                            {{ $currency }}{{
                                                number_format(
                                                    (float) $totalRevenue,
                                                    2
                                                )
                                            }}
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-xl">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="metric-icon bg-cyan">
                                            <i class="bx bx-credit-card"></i>
                                        </span>

                                        <p class="metric-label">
                                            Paid Revenue
                                        </p>

                                        <h5 class="metric-value">
                                            {{ $currency }}{{
                                                number_format(
                                                    (float) $paidRevenue,
                                                    2
                                                )
                                            }}
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-xl">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="metric-icon bg-orange">
                                            <i class="bx bx-wallet"></i>
                                        </span>

                                        <p class="metric-label">
                                            Expenses / Cost
                                        </p>

                                        <h5 class="metric-value">
                                            {{ $currency }}{{
                                                number_format(
                                                    (float) $totalExpenses,
                                                    2
                                                )
                                            }}
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-xl">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="metric-icon bg-green">
                                            <i class="bx bx-line-chart"></i>
                                        </span>

                                        <p class="metric-label">
                                            Gross Profit
                                        </p>

                                        <h5 class="metric-value">
                                            {{ $currency }}{{
                                                number_format(
                                                    (float) $grossProfit,
                                                    2
                                                )
                                            }}
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-xl">
                                <div class="card">
                                    <div class="card-body">
                                        <span
                                            class="
                                                metric-icon
                                                {{
                                                    $netProfit >= 0
                                                        ? 'bg-green'
                                                        : 'bg-red'
                                                }}
                                            "
                                        >
                                            <i
                                                class="
                                                    bx
                                                    {{
                                                        $netProfit >= 0
                                                            ? 'bx-trending-up'
                                                            : 'bx-trending-down'
                                                    }}
                                                "
                                            ></i>
                                        </span>

                                        <p class="metric-label">
                                            {{
                                                $netProfit >= 0
                                                    ? 'Net Profit'
                                                    : 'Net Loss'
                                            }}
                                        </p>

                                        <h5 class="metric-value">
                                            {{ $currency }}{{
                                                number_format(
                                                    abs((float) $netProfit),
                                                    2
                                                )
                                            }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Revenue graph and order statuses --}}
                        <div class="row g-4 mb-4">
                            <div class="col-12 col-xl-8">
                                <div class="card">
                                    <div
                                        class="
                                            card-header
                                            d-flex
                                            justify-content-between
                                            align-items-start
                                        "
                                    >
                                        <div>
                                            <h5 class="mb-1">
                                                Revenue & Profit
                                            </h5>

                                            <small class="text-muted">
                                                {{ ucfirst($period) }}
                                                performance
                                            </small>
                                        </div>

                                        <span class="badge bg-label-primary">
                                            Live Data
                                        </span>
                                    </div>

                                    <div class="card-body">
                                        <div class="chart-box">
                                            <canvas
                                                id="revenueProfitChart"
                                            ></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-xl-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-1">
                                            Order Status
                                        </h5>

                                        <small class="text-muted">
                                            {{ number_format($totalOrders) }}
                                            orders in this period
                                        </small>
                                    </div>

                                    <div class="card-body">
                                        <div class="status-box">
                                            <span class="status-name">
                                                <span
                                                    class="status-dot"
                                                    style="background: #ffab00"
                                                ></span>

                                                Pending
                                            </span>

                                            <strong>
                                                {{
                                                    number_format(
                                                        $pendingOrders
                                                    )
                                                }}
                                            </strong>
                                        </div>

                                        <div class="status-box">
                                            <span class="status-name">
                                                <span
                                                    class="status-dot"
                                                    style="background: #03c3ec"
                                                ></span>

                                                Processing
                                            </span>

                                            <strong>
                                                {{
                                                    number_format(
                                                        $processingOrders
                                                    )
                                                }}
                                            </strong>
                                        </div>

                                        <div class="status-box">
                                            <span class="status-name">
                                                <span
                                                    class="status-dot"
                                                    style="background: #696cff"
                                                ></span>

                                                Shipped
                                            </span>

                                            <strong>
                                                {{
                                                    number_format(
                                                        $shippedOrders
                                                    )
                                                }}
                                            </strong>
                                        </div>

                                        <div class="status-box">
                                            <span class="status-name">
                                                <span
                                                    class="status-dot"
                                                    style="background: #71dd37"
                                                ></span>

                                                Delivered
                                            </span>

                                            <strong>
                                                {{
                                                    number_format(
                                                        $deliveredOrders
                                                    )
                                                }}
                                            </strong>
                                        </div>

                                        <div class="status-box">
                                            <span class="status-name">
                                                <span
                                                    class="status-dot"
                                                    style="background: #ff3e1d"
                                                ></span>

                                                Cancelled
                                            </span>

                                            <strong>
                                                {{
                                                    number_format(
                                                        $cancelledOrders
                                                    )
                                                }}
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Recent orders and products --}}
                        <div class="row g-4 mb-4">
                            <div class="col-12 col-xl-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-1">
                                            Recent Orders
                                        </h5>

                                        <small class="text-muted">
                                            Latest orders for the selected period
                                        </small>
                                    </div>

                                    <div class="table-responsive">
                                        <table
                                            class="
                                                table
                                                table-hover
                                                mb-0
                                            "
                                        >
                                            <thead>
                                                <tr>
                                                    <th>Order</th>
                                                    <th>Customer</th>
                                                    <th>Status</th>
                                                    <th>Amount</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse (
                                                    $recentOrders as $order
                                                )
                                                    @php
                                                        $orderId =
                                                            $order->uuid
                                                            ?? $order->order_number
                                                            ?? $order->id
                                                            ?? '-';

                                                        $customer =
                                                            $order->customer_name
                                                            ?? $order->name
                                                            ?? $order->billing_name
                                                            ?? $order->email
                                                            ?? 'Guest';

                                                        $status =
                                                            $order->status
                                                            ?? $order->order_status
                                                            ?? $order->delivery_status
                                                            ?? 'Pending';

                                                        $amount =
                                                            $order->grand_total
                                                            ?? $order->total_amount
                                                            ?? $order->total_price
                                                            ?? $order->total
                                                            ?? $order->amount
                                                            ?? 0;
                                                    @endphp

                                                    <tr>
                                                        <td>
                                                            #{{ $orderId }}
                                                        </td>

                                                        <td>
                                                            {{ $customer }}
                                                        </td>

                                                        <td>
                                                            @php
                                                                $statusValue =
                                                                    strtolower(
                                                                        (string) $status
                                                                    );

                                                                $statusClass =
                                                                    match (true) {
                                                                        in_array(
                                                                            $statusValue,
                                                                            [
                                                                                'delivered',
                                                                                'completed',
                                                                                'complete'
                                                                            ]
                                                                        ) => 'success',

                                                                        in_array(
                                                                            $statusValue,
                                                                            [
                                                                                'cancelled',
                                                                                'canceled',
                                                                                'failed',
                                                                                'refunded'
                                                                            ]
                                                                        ) => 'danger',

                                                                        in_array(
                                                                            $statusValue,
                                                                            [
                                                                                'shipped',
                                                                                'dispatched'
                                                                            ]
                                                                        ) => 'primary',

                                                                        in_array(
                                                                            $statusValue,
                                                                            [
                                                                                'processing',
                                                                                'confirmed',
                                                                                'accepted'
                                                                            ]
                                                                        ) => 'info',

                                                                        default => 'warning',
                                                                    };
                                                            @endphp

                                                            <span
                                                                class="
                                                                    badge
                                                                    bg-label-{{
                                                                        $statusClass
                                                                    }}
                                                                "
                                                            >
                                                                {{
                                                                    ucfirst(
                                                                        str_replace(
                                                                            '_',
                                                                            ' ',
                                                                            $status
                                                                        )
                                                                    )
                                                                }}
                                                            </span>
                                                        </td>

                                                        <td>
                                                            {{ $currency }}{{
                                                                number_format(
                                                                    (float) $amount,
                                                                    2
                                                                )
                                                            }}
                                                        </td>

                                                        <td>
                                                            @if (
                                                                isset(
                                                                    $order->created_at
                                                                )
                                                            )
                                                                {{
                                                                    \Carbon\Carbon::parse(
                                                                        $order->created_at
                                                                    )->format(
                                                                        'd M Y'
                                                                    )
                                                                }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td
                                                            colspan="5"
                                                            class="empty-state"
                                                        >
                                                            <i
                                                                class="
                                                                    bx
                                                                    bx-receipt
                                                                    fs-1
                                                                    d-block
                                                                    mb-2
                                                                "
                                                            ></i>

                                                            No orders found for
                                                            this period.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-xl-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-1">
                                            Best-Selling Products
                                        </h5>

                                        <small class="text-muted">
                                            Based on sold quantity
                                        </small>
                                    </div>

                                    <div class="card-body">
                                        @forelse (
                                            $bestSellingProducts as $product
                                        )
                                            <div class="status-box">
                                                <div>
                                                    <strong class="d-block">
                                                        {{
                                                            $product
                                                                ->product_name
                                                            ?? 'Deleted product'
                                                        }}
                                                    </strong>

                                                    <small class="text-muted">
                                                        {{
                                                            number_format(
                                                                $product
                                                                    ->sold_quantity
                                                                ?? 0
                                                            )
                                                        }}
                                                        items sold
                                                    </small>
                                                </div>

                                                <strong>
                                                    {{ $currency }}{{
                                                        number_format(
                                                            (float) (
                                                                $product
                                                                    ->sales_amount
                                                                ?? 0
                                                            ),
                                                            2
                                                        )
                                                    }}
                                                </strong>
                                            </div>
                                        @empty
                                            <div class="empty-state">
                                                <i
                                                    class="
                                                        bx
                                                        bx-package
                                                        fs-1
                                                        d-block
                                                        mb-2
                                                    "
                                                ></i>

                                                No order-item data found.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>

                    @include('admin.footer')

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    @include('admin.js')

    <script
        src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"
    ></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById(
                'revenueProfitChart'
            );

            if (!canvas || typeof Chart === 'undefined') {
                return;
            }

            new Chart(canvas, {
                type: 'line',

                data: {
                    labels: @json($chartLabels),

                    datasets: [
                        {
                            label: 'Revenue',
                            data: @json($revenueData),
                            borderColor: '#696cff',
                            backgroundColor:
                                'rgba(105, 108, 255, 0.12)',
                            fill: true,
                            tension: 0.38,
                            borderWidth: 3,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        },
                        {
                            label: 'Profit',
                            data: @json($profitData),
                            borderColor: '#71dd37',
                            backgroundColor:
                                'rgba(113, 221, 55, 0.08)',
                            fill: true,
                            tension: 0.38,
                            borderWidth: 3,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        },
                        {
                            label: 'Orders',
                            data: @json($orderData),
                            borderColor: '#03c3ec',
                            backgroundColor: 'transparent',
                            tension: 0.38,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            yAxisID: 'ordersAxis'
                        }
                    ]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    interaction: {
                        mode: 'index',
                        intersect: false
                    },

                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',

                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 20
                            }
                        },

                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    if (
                                        context.dataset.label === 'Orders'
                                    ) {
                                        return (
                                            ' Orders: '
                                            + context.parsed.y
                                        );
                                    }

                                    return (
                                        ' '
                                        + context.dataset.label
                                        + ': {{ $currency }}'
                                        + Number(
                                            context.parsed.y
                                        ).toLocaleString()
                                    );
                                }
                            }
                        }
                    },

                    scales: {
                        x: {
                            grid: {
                                display: false
                            },

                            ticks: {
                                color: '#a1acb8',
                                maxRotation: 45,
                                minRotation: 0
                            }
                        },

                        y: {
                            beginAtZero: true,

                            title: {
                                display: true,
                                text: 'Revenue / Profit'
                            },

                            grid: {
                                color: 'rgba(67, 89, 113, 0.08)'
                            },

                            ticks: {
                                color: '#a1acb8',

                                callback: function (value) {
                                    return (
                                        '{{ $currency }}'
                                        + Number(value).toLocaleString()
                                    );
                                }
                            }
                        },

                        ordersAxis: {
                            beginAtZero: true,
                            position: 'right',

                            title: {
                                display: true,
                                text: 'Orders'
                            },

                            grid: {
                                drawOnChartArea: false
                            },

                            ticks: {
                                color: '#03c3ec',
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
