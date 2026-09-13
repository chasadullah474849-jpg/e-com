@php
    $period = $period ?? request('period', 'week');
    $totalProducts = $totalProducts ?? 0;
    $totalUsers = $totalUsers ?? 0;
    $totalCartClicks = $totalCartClicks ?? 0;
    $totalCartQuantity = $totalCartQuantity ?? 0;
    $totalCartValue = $totalCartValue ?? 0;
    $totalSales = $totalSales ?? 0;
    $payments = $payments ?? 0;
    $transactions = $transactions ?? 0;
    $totalProfit = $totalProfit ?? 0;
    $totalOrders = $totalOrders ?? 0;
    $pendingOrders = $pendingOrders ?? 0;
    $processingOrders = $processingOrders ?? 0;
    $shippedOrders = $shippedOrders ?? 0;
    $deliveredOrders = $deliveredOrders ?? 0;
    $cancelledOrders = $cancelledOrders ?? 0;
    $chartLabels = $chartLabels ?? [];
    $salesData = $salesData ?? [];
    $cartClickData = $cartClickData ?? [];
    $cartQuantityData = $cartQuantityData ?? [];
    $popularProducts = $popularProducts ?? collect();
    $recentOrders = $recentOrders ?? collect();
    $currency = config('app.currency_symbol', '$');
    $todayChange = $todayChange ?? 0;

    try {
        if ($recentOrders->isEmpty() && \Illuminate\Support\Facades\Schema::hasTable('orders')) {
            $recentOrders = \Illuminate\Support\Facades\DB::table('orders')
                ->orderByDesc(\Illuminate\Support\Facades\Schema::hasColumn('orders', 'created_at') ? 'created_at' : 'id')
                ->limit(6)
                ->get();
        }

        if ($cancelledOrders === 0 && \Illuminate\Support\Facades\Schema::hasTable('orders')) {
            $dashboardStatusColumn = collect(['status', 'order_status', 'delivery_status'])
                ->first(fn ($column) => \Illuminate\Support\Facades\Schema::hasColumn('orders', $column));

            if ($dashboardStatusColumn) {
                $cancelledOrders = \Illuminate\Support\Facades\DB::table('orders')
                    ->whereIn(
                        \Illuminate\Support\Facades\DB::raw("LOWER(TRIM({$dashboardStatusColumn}))"),
                        ['cancelled', 'canceled', 'refunded', 'failed']
                    )->count();
            }
        }
    } catch (\Throwable $exception) {
        $recentOrders = collect();
    }
@endphp

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
                      <div class="card">
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
                          <h3 class="card-title mb-2">{{ $currency }}{{ number_format((float) $totalProfit, 2) }}</h3>
                          <small class="{{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }} fw-semibold"><i class="bx {{ $totalProfit >= 0 ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }}"></i> Live profit</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card">
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
                          <h3 class="card-title text-nowrap mb-1">{{ $currency }}{{ number_format((float) $totalSales, 2) }}</h3>
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
                              <h6 class="mb-0">{{ $currency }}{{ number_format((float)$totalSales,2) }}</h6>
                            </div>
                          </div>
                          <div class="d-flex">
                            <div class="me-2">
                              <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                              <small>Profit</small>
                              <h6 class="mb-0">{{ $currency }}{{ number_format((float)$totalProfit,2) }}</h6>
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
                      <div class="card">
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
                          <h3 class="card-title text-nowrap mb-2">{{ $currency }}{{ number_format((float)$payments,2) }}</h3>
                          <small class="text-success fw-semibold"><i class="bx bx-check"></i> Paid amount</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-6 mb-4">
                      <div class="card">
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
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                              <div class="card-title">
                                <h5 class="text-nowrap mb-2">Profile Report</h5>
                                <span class="badge bg-label-warning rounded-pill">{{ ucfirst($period) }}</span>
                              </div>
                              <div class="mt-sm-auto">
                                <small class="text-success text-nowrap fw-semibold"><i class="bx bx-chevron-up"></i> Live revenue</small>
                                <h3 class="mb-0">{{ $currency }}{{ number_format((float)$totalSales,2) }}</h3>
                              </div>
                            </div>
                            <div style="width:145px;height:85px"><canvas id="dynamicProfileChart"></canvas></div>
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
                          <button
                            type="button"
                            class="nav-link active"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-tabs-line-card-income"
                            aria-controls="navs-tabs-line-card-income"
                            aria-selected="true"
                          >
                            Income
                          </button>
                        </li>
                        <li class="nav-item">
                          <button type="button" class="nav-link" role="tab">Expenses</button>
                        </li>
                        <li class="nav-item">
                          <button type="button" class="nav-link" role="tab">Profit</button>
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
                            $orderNumber = $order->order_number ?? $order->uuid ?? $order->id ?? '-';
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
