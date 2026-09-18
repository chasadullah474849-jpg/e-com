@php
    /*
     * FULLY DYNAMIC DASHBOARD
     * Every value is rebuilt from the current database on every request.
     * It supports the column names used by the supplied e-commerce project.
     */
    $DB = \Illuminate\Support\Facades\DB::class;
    $Schema = \Illuminate\Support\Facades\Schema::class;
    $period = in_array(request('period'), ['day', 'week', 'month', 'year'], true)
        ? request('period')
        : 'week';
    $currency = config('app.currency_symbol', '$');

    $totalProducts = $totalUsers = $totalOrders = $transactions = 0;
    $totalCartClicks = $totalCartQuantity = 0;
    $totalCartValue = $totalSales = $payments = $totalProfit = 0.0;
    $pendingOrders = $processingOrders = $shippedOrders = 0;
    $deliveredOrders = $cancelledOrders = 0;
    $fulfilledOrders = $partiallyFulfilledOrders = $unfulfilledOrders = 0;
    $pendingPayments = $failedPayments = $refundedPayments = 0;
    $chartLabels = $salesData = $ordersData = $cartClickData = $cartQuantityData = [];
    $popularProducts = $recentOrders = collect();
    $todayChange = 0;

    $firstColumn = function (string $table, array $columns) use ($Schema) {
        foreach ($columns as $column) {
            if ($Schema::hasColumn($table, $column)) return $column;
        }
        return null;
    };

    $statusCount = function ($query, ?string $column, array $values) use ($DB): int {
        if (!$column) return 0;
        return (clone $query)
            ->whereIn($DB::raw("LOWER(TRIM(`{$column}`))"), $values)
            ->count();
    };

    try {
        if ($Schema::hasTable('products')) {
            $totalProducts = $DB::table('products')->count();
        }
        if ($Schema::hasTable('users')) {
            $totalUsers = $DB::table('users')->count();
        }

        if ($Schema::hasTable('orders')) {
            $ordersQuery = $DB::table('orders');
            $amountColumn = $firstColumn('orders', [
                'total_amount', 'grand_total', 'total', 'amount',
                'total_price', 'payable_amount', 'order_total', 'subtotal'
            ]);
            $dateColumn = $firstColumn('orders', ['created_at', 'order_date', 'date']);
            $paymentColumn = $firstColumn('orders', ['payment_status', 'billing_status', 'paid_status']);
            $deliveryColumn = $firstColumn('orders', ['delivery_status', 'order_status', 'status']);
            $fulfillmentColumn = $firstColumn('orders', ['fulfillment_status', 'fulfilment_status']);

            $totalOrders = (clone $ordersQuery)->count();
            $transactions = $totalOrders;
            $totalSales = $amountColumn ? (float) (clone $ordersQuery)->sum($amountColumn) : 0.0;

            $paidValues = ['paid', 'completed', 'complete', 'success', 'successful', 'succeeded', 'captured'];
            $pendingPaymentValues = ['pending', 'unpaid', 'pending payment', 'pending_payment', 'cod'];

            if ($paymentColumn) {
                $payments = $amountColumn
                    ? (float) (clone $ordersQuery)
                        ->whereIn($DB::raw("LOWER(TRIM(`{$paymentColumn}`))"), $paidValues)
                        ->sum($amountColumn)
                    : 0.0;
                $pendingPayments = $statusCount($ordersQuery, $paymentColumn, $pendingPaymentValues);
                $failedPayments = $statusCount($ordersQuery, $paymentColumn, ['failed', 'declined']);
                $refundedPayments = $statusCount($ordersQuery, $paymentColumn, ['refunded', 'refund', 'partially_refunded']);
            } else {
                $payments = $totalSales;
            }

            /* Cost price is not present in the supplied tables, so profit is estimated at 20%. */
            $totalProfit = $totalSales * 0.20;

            $pendingOrders = $deliveryColumn
                ? $statusCount($ordersQuery, $deliveryColumn, ['pending', 'new'])
                : $totalOrders;
            $processingOrders = $statusCount($ordersQuery, $deliveryColumn, [
                'processing', 'confirmed', 'accepted', 'paid', 'preparing'
            ]);
            $shippedOrders = $statusCount($ordersQuery, $deliveryColumn, [
                'shipped', 'dispatched', 'on the way', 'on_the_way',
                'out for delivery', 'out_for_delivery'
            ]);
            $deliveredOrders = $statusCount($ordersQuery, $deliveryColumn, [
                'delivered', 'completed', 'complete'
            ]);
            $cancelledOrders = $statusCount($ordersQuery, $deliveryColumn, [
                'cancelled', 'canceled', 'returned', 'refunded', 'failed'
            ]);

            $fulfilledOrders = $statusCount($ordersQuery, $fulfillmentColumn, [
                'fulfilled', 'completed', 'complete'
            ]);
            $partiallyFulfilledOrders = $statusCount($ordersQuery, $fulfillmentColumn, [
                'partially_fulfilled', 'partially fulfilled', 'partial'
            ]);
            $unfulfilledOrders = $fulfillmentColumn
                ? $statusCount($ordersQuery, $fulfillmentColumn, [
                    'unfulfilled', 'pending', 'not_fulfilled', 'not fulfilled'
                ])
                : $totalOrders;

            $recentOrders = (clone $ordersQuery)
                ->orderByDesc($dateColumn ?: 'id')
                ->limit(6)
                ->get();

            $now = now();
            if ($period === 'day') {
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                $points = collect(range(0, 23))->map(fn ($i) => $start->copy()->addHours($i));
                $phpFormat = 'Y-m-d H'; $sqlFormat = '%Y-%m-%d %H';
                $chartLabels = $points->map(fn ($date) => $date->format('g A'))->all();
            } elseif ($period === 'month') {
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                $points = collect(range(0, $now->daysInMonth - 1))->map(fn ($i) => $start->copy()->addDays($i));
                $phpFormat = 'Y-m-d'; $sqlFormat = '%Y-%m-%d';
                $chartLabels = $points->map(fn ($date) => $date->format('d M'))->all();
            } elseif ($period === 'year') {
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                $points = collect(range(0, 11))->map(fn ($i) => $start->copy()->addMonths($i));
                $phpFormat = 'Y-m'; $sqlFormat = '%Y-%m';
                $chartLabels = $points->map(fn ($date) => $date->format('M'))->all();
            } else {
                $start = $now->copy()->subDays(6)->startOfDay();
                $end = $now->copy()->endOfDay();
                $points = collect(range(0, 6))->map(fn ($i) => $start->copy()->addDays($i));
                $phpFormat = 'Y-m-d'; $sqlFormat = '%Y-%m-%d';
                $chartLabels = $points->map(fn ($date) => $date->format('D'))->all();
            }

            $salesBuckets = $orderBuckets = collect();
            if ($dateColumn) {
                $chartQuery = $DB::table('orders')
                    ->whereBetween($dateColumn, [$start, $end])
                    ->selectRaw("DATE_FORMAT(`{$dateColumn}`, '{$sqlFormat}') AS bucket")
                    ->selectRaw('COUNT(*) AS order_count');
                if ($amountColumn) {
                    $chartQuery->selectRaw("SUM(`{$amountColumn}`) AS sales_total");
                }
                $chartRows = $chartQuery->groupBy('bucket')->get();
                $salesBuckets = $chartRows->pluck('sales_total', 'bucket');
                $orderBuckets = $chartRows->pluck('order_count', 'bucket');
            }
            $salesData = $points->map(fn ($date) => (float) ($salesBuckets[$date->format($phpFormat)] ?? 0))->all();
            $ordersData = $points->map(fn ($date) => (int) ($orderBuckets[$date->format($phpFormat)] ?? 0))->all();
        }

        if ($Schema::hasTable('cart_activities')) {
            $cartQuery = $DB::table('cart_activities');
            $quantityColumn = $firstColumn('cart_activities', ['quantity', 'qty']);
            $cartAmountColumn = $firstColumn('cart_activities', ['total_amount', 'cart_value', 'amount']);
            $totalCartClicks = (clone $cartQuery)->count();
            $totalCartQuantity = $quantityColumn ? (int) (clone $cartQuery)->sum($quantityColumn) : $totalCartClicks;
            $totalCartValue = $cartAmountColumn ? (float) (clone $cartQuery)->sum($cartAmountColumn) : 0.0;
            $cartClickData = array_fill(0, count($chartLabels), 0);
            $cartQuantityData = array_fill(0, count($chartLabels), 0);

            if ($Schema::hasColumn('cart_activities', 'product_id') && $Schema::hasTable('products')) {
                $productNameColumn = $firstColumn('products', ['name', 'title', 'product_name']);
                if ($productNameColumn) {
                    $popularProducts = $DB::table('cart_activities as ca')
                        ->leftJoin('products as p', 'p.id', '=', 'ca.product_id')
                        ->select('ca.product_id', "p.{$productNameColumn} as product_name")
                        ->selectRaw('COUNT(*) as total_clicks')
                        ->selectRaw($quantityColumn ? "SUM(ca.`{$quantityColumn}`) as total_quantity" : 'COUNT(*) as total_quantity')
                        ->groupBy('ca.product_id', "p.{$productNameColumn}")
                        ->orderByDesc('total_quantity')
                        ->limit(6)
                        ->get()
                        ->map(function ($item) {
                            $item->product = (object) ['name' => $item->product_name ?: 'Product'];
                            return $item;
                        });
                }
            }
        } else {
            $cartClickData = array_fill(0, count($chartLabels), 0);
            $cartQuantityData = array_fill(0, count($chartLabels), 0);
        }
    } catch (\Throwable $exception) {
        \Illuminate\Support\Facades\Log::error('Dynamic admin dashboard: '.$exception->getMessage());
        $recentOrders = collect();
    }
@endphp

<style>
  .dashboard-money-card{min-width:0;overflow:hidden}
  .dashboard-money-value{
    display:block;width:100%;max-width:100%;margin-bottom:.5rem;
    color:#566a7f;font-size:clamp(1.05rem,1.5vw,1.65rem);
    font-weight:600;line-height:1.25;white-space:normal!important;
    overflow-wrap:anywhere;word-break:break-word
  }
  .dashboard-small-money{
    display:block;max-width:100%;overflow:hidden;
    font-size:clamp(.72rem,.95vw,.95rem);
    text-overflow:ellipsis;white-space:nowrap
  }
  .dashboard-profile-card{min-height:180px;overflow:hidden}
  .dashboard-profile-layout{min-width:0}
  .dashboard-profile-chart{
    position:relative;width:145px;max-width:45%;height:85px;
    flex:0 0 145px;overflow:hidden
  }
  .dashboard-profile-chart canvas{
    display:block!important;width:100%!important;max-width:100%!important;
    height:85px!important;max-height:85px!important
  }
  @media(max-width:1399.98px){
    .dashboard-money-value{font-size:1.12rem}
    .dashboard-profile-chart{width:118px;flex-basis:118px}
  }
  @media(max-width:575.98px){
    .dashboard-money-value{font-size:1rem}
    .dashboard-profile-chart{width:100%;max-width:100%;height:90px;flex-basis:100%}
  }
</style>

   <!-- Layout wrapper -->




          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-lg-8 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary">Welcome {{ auth()->user()->name ?? 'Admin' }}! 🎉</h5>
                          <p class="mb-4">
                            Your store has received <span class="fw-bold">{{ number_format($totalOrders) }}</span> orders.
                            Open analytics to review live sales and profit.
                          </p>

<a
    href="{{ route('admin.analytics.index', ['period' => $period ?? 'week']) }}"
    class="btn btn-sm btn-outline-primary"
>
    View Analytics
</a>                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="{{asset('admins/assets/img/illustrations/man-with-laptop-light.png')}}"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 order-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card dashboard-money-card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="{{asset('admins/assets/img/icons/unicons/chart-success.png')}}"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                              </div>
                            </div>
                          </div>
                          <span class="fw-semibold d-block mb-1">Profit</span>
                          <h3 class="card-title dashboard-money-value">{{ $currency }}{{ number_format((float) $totalProfit, 2) }}</h3>
                          <small class="{{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }} fw-semibold"><i class="bx {{ $totalProfit >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i> Live profit</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card dashboard-money-card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                                src="{{asset('admins/assets/img/icons/unicons/wallet-info.png')}}"
                                alt="Credit Card"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt6"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                              </div>
                            </div>
                          </div>
                          <span>Sales</span>
                          <h3 class="card-title dashboard-money-value">{{ $currency }}{{ number_format((float) $totalSales, 2) }}</h3>
                          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> {{ ucfirst($period) }} sales</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Total Revenue -->
                <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                  <div class="card">
                    <div class="row row-bordered g-0">
                      <div class="col-md-8">
                        <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
                        <div class="px-2" style="height:315px"><canvas id="dynamicRevenueChart"></canvas></div>
                      </div>
                      <div class="col-md-4">
                        <div class="card-body">
                          <div class="text-center">
                            <div class="dropdown">
                              <button
                                class="btn btn-sm btn-outline-primary dropdown-toggle"
                                type="button"
                                id="growthReportId"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                {{ ['day'=>'Today','week'=>'7 Days','month'=>'Month','year'=>'Year'][$period] ?? '7 Days' }}
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                @foreach(['day'=>'Today','week'=>'7 Days','month'=>'Month','year'=>'Year'] as $key=>$label)
                                  <a class="dropdown-item {{ $period===$key?'active':'' }}" href="{{ route('admin.dashboard',['period'=>$key]) }}">{{ $label }}</a>
                                @endforeach
                              </div>
                            </div>
                          </div>
                        </div>
                        <div style="height:120px"><canvas id="dynamicGrowthChart"></canvas></div>
                        <div class="text-center fw-semibold pt-3 mb-2">{{ number_format($totalOrders) }} Total Orders</div>

                        <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                          <div class="d-flex">
                            <div class="me-2">
                              <span class="badge bg-label-primary p-2"><i class="bx bx-dollar text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                              <small>Sales</small>
                              <h6 class="mb-0 dashboard-small-money">{{ $currency }}{{ number_format((float)$totalSales,2) }}</h6>
                            </div>
                          </div>
                          <div class="d-flex">
                            <div class="me-2">
                              <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                              <small>Profit</small>
                              <h6 class="mb-0 dashboard-small-money">{{ $currency }}{{ number_format((float)$totalProfit,2) }}</h6>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Total Revenue -->
                <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                  <div class="row">
                    <div class="col-6 mb-4">
                      <div class="card dashboard-money-card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="{{asset('admins/assets/img/icons/unicons/paypal.png')}}" alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt4"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                              </div>
                            </div>
                          </div>
                          <span class="d-block mb-1">Payments</span>
                          <h3 class="card-title dashboard-money-value">{{ $currency }}{{ number_format((float)$payments,2) }}</h3>
                          <small class="text-success fw-semibold"><i class="bx bx-check"></i> Paid amount</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-6 mb-4">
                      <div class="card dashboard-money-card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="{{asset('admins/assets/img/icons/unicons/cc-primary.png')}}" alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt1"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                              </div>
                            </div>
                          </div>
                          <span class="fw-semibold d-block mb-1">Transactions</span>
                          <h3 class="card-title mb-2">{{ number_format($transactions) }}</h3>
                          <small class="text-success fw-semibold"><i class="bx bx-transfer"></i> Transactions</small>
                        </div>
                      </div>
                    </div>
                    <!-- </div>
    <div class="row"> -->
                    <div class="col-12 mb-4">
                      <div class="card dashboard-profile-card">
                        <div class="card-body">
                          <div class="d-flex justify-content-between flex-sm-row flex-column gap-3 dashboard-profile-layout">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                              <div class="card-title">
                                <h5 class="text-nowrap mb-2">Profile Report</h5>
                                <span class="badge bg-label-warning rounded-pill">{{ ucfirst($period) }}</span>
                              </div>
                              <div class="mt-sm-auto">
                                <small class="text-success text-nowrap fw-semibold"><i class="bx bx-chevron-up"></i> Live revenue</small>
                                <h3 class="mb-0 dashboard-money-value">{{ $currency }}{{ number_format((float)$totalSales,2) }}</h3>
                              </div>
                            </div>
                            <div class="dashboard-profile-chart"><canvas id="dynamicProfileChart"></canvas></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <!-- Order Statistics -->
                <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Order Statistics</h5>
                        <small class="text-muted">{{ $currency }}{{ number_format((float)$totalSales,2) }} Total Sales</small>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="orederStatistics"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                          <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                          <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column align-items-center gap-1">
                          <h2 class="mb-2">{{ number_format($totalOrders) }}</h2>
                          <span>Total Orders</span>
                        </div>
                        <div style="width:130px;height:130px"><canvas id="dynamicOrderChart"></canvas></div>
                      </div>
                      <ul class="p-0 m-0">
                        @foreach([
                          ['Pending','Awaiting confirmation',$pendingOrders,'warning','bx-time-five'],
                          ['Processing','Order is being prepared',$processingOrders,'info','bx-loader-circle'],
                          ['Shipped','Order is on the way',$shippedOrders,'primary','bx-package'],
                          ['Delivered','Successfully delivered',$deliveredOrders,'success','bx-check-circle'],
                          ['Cancelled','Cancelled or refunded',$cancelledOrders,'danger','bx-x-circle']
                        ] as [$label,$description,$count,$color,$icon])
                          <li class="d-flex {{ !$loop->last ? 'mb-3 pb-1' : '' }}">
                            <div class="avatar flex-shrink-0 me-3">
                              <span class="avatar-initial rounded bg-label-{{ $color }}"><i class="bx {{ $icon }}"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                              <div class="me-2"><h6 class="mb-0">{{ $label }}</h6><small class="text-muted">{{ $description }}</small></div>
                              <div class="user-progress"><small class="fw-semibold">{{ number_format($count) }}</small></div>
                            </div>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
                <!--/ Order Statistics -->

                <!-- Expense Overview -->
                <div class="col-md-6 col-lg-4 order-1 mb-4">
                  <div class="card h-100">
                    <div class="card-header">
                      <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" href="{{ route('admin.finance.income') }}">Income</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{ route('admin.finance.expenses') }}">Expenses</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{ route('admin.finance.profit') }}">Profit</a>
                        </li>
                      </ul>
                    </div>
                    <div class="card-body px-0">
                      <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                          <div class="d-flex p-4 pt-3">
                            <div class="avatar flex-shrink-0 me-3">
                              <img src="{{asset('admins/assets/img/icons/unicons/wallet.png')}}" alt="User" />
                            </div>
                            <div>
                              <small class="text-muted d-block">Total Balance</small>
                              <div class="d-flex align-items-center">
                                <h6 class="mb-0 me-1">{{ $currency }}{{ number_format((float)$totalSales,2) }}</h6>
                                <small class="text-success fw-semibold">
                                  <i class="bx bx-chevron-up"></i>
                                  {{ ucfirst($period) }}
                                </small>
                              </div>
                            </div>
                          </div>
                          <div style="height:185px"><canvas id="dynamicIncomeChart"></canvas></div>
                          <div class="d-flex justify-content-center pt-4 gap-2">
                            <div class="flex-shrink-0">
                              <span class="avatar-initial rounded bg-label-warning p-2"><i class="bx bx-wallet"></i></span>
                            </div>
                            <div>
                              <p class="mb-n1 mt-1">Cart Value</p>
                              <small class="text-muted">{{ $currency }}{{ number_format((float)$totalCartValue,2) }} potential revenue</small>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Expense Overview -->

                <!-- Transactions -->
                <div class="col-md-6 col-lg-4 order-2 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="card-title m-0 me-2">Transactions</h5>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="transactionID"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                          <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                          <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                          <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0">
                        @forelse($recentOrders as $order)
                          @php
                            $orderNumber = $order->order_no ?? $order->order_number ?? $order->uuid ?? $order->id ?? '-';
                            $customerName = $order->customer_name ?? $order->billing_name ?? $order->name ?? $order->email ?? 'Guest';
                            $orderStatus = $order->status ?? $order->order_status ?? $order->delivery_status ?? 'pending';
                            $orderAmount = $order->grand_total ?? $order->total_amount ?? $order->total_price ?? $order->total ?? $order->amount ?? 0;
                          @endphp
                          <li class="d-flex {{ !$loop->last ? 'mb-4 pb-1' : '' }}">
                            <div class="avatar flex-shrink-0 me-3">
                              <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-receipt"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                              <div class="me-2">
                                <small class="text-muted d-block mb-1">Order #{{ $orderNumber }}</small>
                                <h6 class="mb-0">{{ $customerName }}</h6>
                                <small class="text-muted">{{ ucfirst(str_replace('_',' ',$orderStatus)) }}</small>
                              </div>
                              <div class="user-progress d-flex align-items-center gap-1">
                                <h6 class="mb-0">{{ $currency }}{{ number_format((float)$orderAmount,2) }}</h6>
                              </div>
                            </div>
                          </li>
                        @empty
                          <li class="text-center text-muted py-4"><i class="bx bx-receipt fs-2 d-block mb-2"></i>No recent orders found.</li>
                        @endforelse
                      </ul>
                    </div>
                  </div>
                </div>
                <!--/ Transactions -->
              </div>
            </div>
            <!-- / Content -->



            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (typeof Chart === 'undefined') return;

  const labels = @json($chartLabels);
  const sales = @json($salesData);
  const clicks = @json($cartClickData);
  const quantities = @json($cartQuantityData);
  const profitRate = {{ $totalSales > 0 ? round(($totalProfit / $totalSales) * 100, 2) : 0 }};
  const money = '{{ $currency }}';
  const gridColor = 'rgba(67,89,113,.08)';
  const tickColor = '#a1acb8';

  const lineOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: 'index', intersect: false },
    plugins: { legend: { display: true, position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } },
    scales: {
      x: { grid: { display: false }, ticks: { color: tickColor } },
      y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor, callback: value => money + Number(value).toLocaleString() } }
    }
  };

  const revenueCanvas = document.getElementById('dynamicRevenueChart');
  if (revenueCanvas) new Chart(revenueCanvas, {
    type: 'bar',
    data: { labels, datasets: [
      { label: 'Sales', data: sales, backgroundColor: '#696cff', borderRadius: 6 },
      { label: 'Estimated Profit', data: sales.map(value => Number(value) * .25), backgroundColor: '#03c3ec', borderRadius: 6 }
    ]},
    options: lineOptions
  });

  const growthCanvas = document.getElementById('dynamicGrowthChart');
  if (growthCanvas) new Chart(growthCanvas, {
    type: 'doughnut',
    data: { labels: ['Profit', 'Remaining Revenue'], datasets: [{ data: [Math.max(0, profitRate), Math.max(0, 100-profitRate)], backgroundColor: ['#696cff','#eceef1'], borderWidth: 0 }] },
    options: { responsive:true, maintainAspectRatio:false, cutout:'72%', plugins:{legend:{display:false}} }
  });

  const profileCanvas = document.getElementById('dynamicProfileChart');
  if (profileCanvas) new Chart(profileCanvas, {
    type: 'line',
    data: { labels, datasets: [{ data:sales, borderColor:'#ffab00', backgroundColor:'rgba(255,171,0,.12)', fill:true, tension:.4, pointRadius:0, borderWidth:2 }] },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{x:{display:false},y:{display:false}} }
  });

  const orderValues = [{{ $pendingOrders }},{{ $processingOrders }},{{ $shippedOrders }},{{ $deliveredOrders }},{{ $cancelledOrders }}];
  const hasOrders = orderValues.some(value => value > 0);
  const orderCanvas = document.getElementById('dynamicOrderChart');
  if (orderCanvas) new Chart(orderCanvas, {
    type: 'doughnut',
    data: { labels:hasOrders?['Pending','Processing','Shipped','Delivered','Cancelled']:['No orders'], datasets:[{data:hasOrders?orderValues:[1],backgroundColor:hasOrders?['#ffab00','#03c3ec','#696cff','#71dd37','#ff3e1d']:['#eceef1'],borderWidth:0}] },
    options: { responsive:true, maintainAspectRatio:false, cutout:'68%', plugins:{legend:{display:false}} }
  });

  const incomeCanvas = document.getElementById('dynamicIncomeChart');
  if (incomeCanvas) new Chart(incomeCanvas, {
    type: 'line',
    data: { labels, datasets: [
      { label:'Sales',data:sales,borderColor:'#696cff',backgroundColor:'rgba(105,108,255,.12)',fill:true,tension:.4,borderWidth:2 },
      { label:'Cart Items',data:quantities,borderColor:'#03c3ec',backgroundColor:'transparent',tension:.4,borderWidth:2 }
    ]},
    options: { responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{display:false},y:{display:false}} }
  });
});
</script>
