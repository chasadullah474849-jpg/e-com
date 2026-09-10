<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template-free">
<head>
    @include('admin.header')
    <style>
        .k-dashboard{--p:#696cff;--blue:#03c3ec;--green:#71dd37;--red:#ff3e1d;--orange:#ffab00;--ink:#566a7f;--muted:#a1acb8}
        .k-dashboard .card{border:0;border-radius:.65rem;box-shadow:0 2px 6px rgba(67,89,113,.12)}
        .k-dashboard .card-title{color:#566a7f;font-weight:600}.k-dashboard .text-soft{color:var(--muted)}
        .k-welcome{position:relative;min-height:185px;overflow:hidden}.k-welcome h5{color:var(--p);font-weight:700}.k-welcome-art{position:absolute;right:12px;bottom:0;width:170px;height:155px}
        .k-welcome-art:before{position:absolute;right:22px;bottom:18px;width:102px;height:102px;border-radius:50%;background:rgba(105,108,255,.12);content:""}
        .k-welcome-art i{position:absolute;right:49px;bottom:43px;color:var(--p);font-size:62px;z-index:1}
        .k-metric-icon{display:inline-flex;width:42px;height:42px;align-items:center;justify-content:center;border-radius:.5rem;font-size:1.35rem}
        .k-purple{color:var(--p);background:rgba(105,108,255,.12)}.k-cyan{color:var(--blue);background:rgba(3,195,236,.12)}.k-green{color:var(--green);background:rgba(113,221,55,.12)}.k-red{color:var(--red);background:rgba(255,62,29,.12)}.k-orange{color:var(--orange);background:rgba(255,171,0,.13)}
        .k-metric-value{margin:.35rem 0;color:#566a7f;font-size:1.55rem;font-weight:700}.k-change{font-size:.78rem}.k-change.up{color:var(--green)}.k-change.down{color:var(--red)}
        .k-chart-lg{height:275px}.k-chart-sm{height:185px}.k-chart-bar{height:155px}
        .k-list-item{display:flex;align-items:center;gap:.8rem;padding:.7rem 0}.k-list-icon{display:inline-flex;width:38px;height:38px;flex:0 0 auto;align-items:center;justify-content:center;border-radius:.45rem;font-size:1.15rem}.k-list-main{min-width:0;flex:1}.k-list-title{margin:0;color:#566a7f;font-size:.86rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.k-list-sub{color:var(--muted);font-size:.74rem}.k-list-value{color:#566a7f;font-size:.82rem;font-weight:600;white-space:nowrap}
        .k-progress{height:7px;border-radius:10px;background:#eceef1;overflow:hidden}.k-progress span{display:block;height:100%;border-radius:10px;background:var(--p)}
        .k-total-center{text-align:center}.k-total-center strong{display:block;color:#566a7f;font-size:1.45rem}.k-total-center small{color:var(--muted)}
        .k-menu{border:0;background:transparent;color:var(--muted);font-size:1.3rem}.k-dashboard .card-header{background:transparent;border:0;padding:1.35rem 1.4rem 0}.k-dashboard .card-body{padding:1.4rem}
        .k-card-actions{position:absolute;right:1rem;top:3.4rem;z-index:20;display:none;min-width:145px;padding:.45rem;background:#fff;border-radius:.5rem;box-shadow:0 .5rem 1.5rem rgba(67,89,113,.18)}
        .k-card-actions.show{display:block}.k-card-actions button{display:block;width:100%;padding:.45rem .65rem;border:0;border-radius:.35rem;background:transparent;color:#566a7f;text-align:left}.k-card-actions button:hover{background:#f5f5f9;color:#696cff}
        @media(max-width:1199.98px){html.k-mobile-menu-open #layout-menu{transform:translate3d(0,0,0)!important}html.k-mobile-menu-open .layout-overlay{display:block!important;opacity:1!important}.layout-overlay{cursor:pointer}}
        @media(max-width:575.98px){.k-welcome-art{opacity:.25}.k-chart-lg{height:240px}.k-dashboard .card-body{padding:1.1rem}}
    </style>
</head>
<body>
<div class="layout-wrapper layout-content-navbar"><div class="layout-container">
    @include('admin.sidebar')
    <div class="layout-page">@include('admin.nav')
        <div class="content-wrapper">
            @php
                $totalProducts=$totalProducts??0;$totalCartClicks=$totalCartClicks??0;$totalCartQuantity=$totalCartQuantity??0;$totalCartValue=$totalCartValue??0;
                $totalOrders=$totalOrders??0;$totalSales=$totalSales??0;$totalProfit=$totalProfit??0;
                $payments=$payments??0;$transactions=$transactions??0;$popularProducts=$popularProducts??collect();$recentOrders=$recentOrders??collect();
                $period=$period??'week';$profitIsEstimated=$profitIsEstimated??false;$chartLabels=$chartLabels??[];$salesData=$salesData??[];$cartClickData=$cartClickData??[];$cartQuantityData=$cartQuantityData??[];
                $pendingOrders=$pendingOrders??0;$processingOrders=$processingOrders??0;$shippedOrders=$shippedOrders??0;$deliveredOrders=$deliveredOrders??0;

                /* Read order statistics directly and support common status-column names. */
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
                        $orderQuery = \Illuminate\Support\Facades\DB::table('orders');
                        $totalOrders = (clone $orderQuery)->count();

                        $statusColumn = collect(['status', 'order_status', 'delivery_status'])
                            ->first(fn ($column) => \Illuminate\Support\Facades\Schema::hasColumn('orders', $column));

                        if ($statusColumn) {
                            $statusCounts = (clone $orderQuery)
                                ->selectRaw("LOWER(TRIM({$statusColumn})) as dashboard_status, COUNT(*) as total")
                                ->groupBy('dashboard_status')
                                ->pluck('total', 'dashboard_status');

                            $countStatuses = function (array $statuses) use ($statusCounts) {
                                return collect($statuses)->sum(fn ($status) => (int) ($statusCounts[$status] ?? 0));
                            };

                            $pendingOrders = $countStatuses(['pending', 'new', 'pending payment', 'pending_payment']);
                            $processingOrders = $countStatuses(['processing', 'confirmed', 'accepted', 'paid']);
                            $shippedOrders = $countStatuses(['shipped', 'on the way', 'on_the_way', 'dispatched', 'out for delivery', 'out_for_delivery']);
                            $deliveredOrders = $countStatuses(['delivered', 'completed', 'complete']);
                        }
                    }
                } catch (\Throwable $exception) {
                    /* Keep controller-provided values if the orders table is unavailable. */
                }
            @endphp
            <main class="container-xxl flex-grow-1 container-p-y k-dashboard">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-8">
                        <div class="card k-welcome h-100"><div class="card-body">
                            <h5 class="mb-2">Welcome back, {{ auth()->user()->name ?? 'Admin' }}! 🎉</h5>
                            <p class="mb-3">Your store received <strong>{{number_format($totalCartClicks)}}</strong> add-to-cart clicks.<br>Review today’s performance below.</p>
                            <a href="{{ route('admin.dashboard', ['period'=>'month']) }}#revenueOverview" class="btn btn-sm btn-outline-primary">View Analytics</a>
                            <div class="k-welcome-art"><i class="bx bx-store-alt"></i></div>
                        </div></div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-2"><div class="card h-100"><div class="card-body"><span class="k-metric-icon k-green"><i class="bx bx-line-chart"></i></span><p class="mt-3 mb-1 text-soft">Total Profit</p><h4 class="k-metric-value">${{number_format($totalProfit,2)}}</h4><span class="k-change up"><i class="bx bx-up-arrow-alt"></i> {{$profitIsEstimated?'Estimated profit':'Actual profit'}}</span></div></div></div>
                    <div class="col-12 col-sm-6 col-lg-2"><div class="card h-100"><div class="card-body"><span class="k-metric-icon k-cyan"><i class="bx bx-wallet"></i></span><p class="mt-3 mb-1 text-soft">Total Sales</p><h4 class="k-metric-value">${{number_format($totalSales,2)}}</h4><span class="k-change up"><i class="bx bx-up-arrow-alt"></i> Cart value</span></div></div></div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-8">
                        <div id="revenueOverview" class="card h-100"><div class="card-header d-flex justify-content-between align-items-start flex-wrap gap-2"><div><h5 class="card-title mb-1">Sales Analytics</h5><small class="text-soft">{{['day'=>'Today by hour','week'=>'Last 7 days','month'=>'Current month','year'=>'Current year'][$period]}}</small></div><div class="btn-group btn-group-sm">@foreach(['day'=>'Today','week'=>'7 Days','month'=>'Month','year'=>'Year'] as $key=>$label)<a class="btn {{$period===$key?'btn-primary':'btn-outline-primary'}}" href="{{route('admin.dashboard',['period'=>$key])}}#revenueOverview">{{$label}}</a>@endforeach</div></div><div class="card-body"><div class="k-chart-lg"><canvas id="revenueChart"></canvas></div></div></div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="row g-4 h-100">
                            <div class="col-6"><div class="card h-100"><div class="card-body"><span class="k-metric-icon k-purple"><i class="bx bx-credit-card"></i></span><p class="mt-3 mb-1 text-soft">Payments</p><h4 class="k-metric-value">${{number_format($payments,2)}}</h4><span class="k-change up">Completed</span></div></div></div>
                            <div class="col-6"><div class="card h-100"><div class="card-body"><span class="k-metric-icon k-red"><i class="bx bx-transfer"></i></span><p class="mt-3 mb-1 text-soft">Transactions</p><h4 class="k-metric-value">{{number_format($transactions)}}</h4><span class="k-change up">Interactions</span></div></div></div>
                            <div class="col-6"><div class="card h-100"><div class="card-body"><span class="k-metric-icon k-orange"><i class="bx bx-cart"></i></span><p class="mt-3 mb-1 text-soft">Total Orders</p><h4 class="k-metric-value">{{number_format($totalOrders)}}</h4><span class="k-change up">All orders</span></div></div></div>
                            <div class="col-6"><div class="card h-100"><div class="card-body"><span class="k-metric-icon k-green"><i class="bx bx-package"></i></span><p class="mt-3 mb-1 text-soft">Products</p><h4 class="k-metric-value">{{number_format($totalProducts)}}</h4><span class="k-change up">In catalogue</span></div></div></div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-lg-4"><div class="card h-100"><div class="card-header d-flex justify-content-between"><div><h5 class="card-title mb-1">Order Statistics</h5><small class="text-soft">Order status overview</small></div><button class="k-menu"><i class="bx bx-dots-vertical-rounded"></i></button></div><div class="card-body"><div class="row align-items-center"><div class="col-5 k-total-center"><strong>{{number_format($totalOrders)}}</strong><small>Total Orders</small></div><div class="col-7"><div class="k-chart-sm"><canvas id="orderChart"></canvas></div></div></div>
                        <div class="k-list-item"><span class="k-list-icon k-orange"><i class="bx bx-time-five"></i></span><div class="k-list-main"><p class="k-list-title">Pending</p><span class="k-list-sub">Awaiting confirmation</span></div><span class="k-list-value">{{$pendingOrders}}</span></div>
                        <div class="k-list-item"><span class="k-list-icon k-cyan"><i class="bx bx-loader-circle"></i></span><div class="k-list-main"><p class="k-list-title">Processing</p><span class="k-list-sub">Being prepared</span></div><span class="k-list-value">{{$processingOrders}}</span></div>
                        <div class="k-list-item"><span class="k-list-icon k-purple"><i class="bx bx-package"></i></span><div class="k-list-main"><p class="k-list-title">Shipped</p><span class="k-list-sub">On the way</span></div><span class="k-list-value">{{$shippedOrders}}</span></div>
                        <div class="k-list-item"><span class="k-list-icon k-green"><i class="bx bx-check-circle"></i></span><div class="k-list-main"><p class="k-list-title">Delivered</p><span class="k-list-sub">Successfully delivered</span></div><span class="k-list-value">{{$deliveredOrders}}</span></div>
                    </div></div></div>

                    <div class="col-12 col-lg-4"><div class="card h-100"><div class="card-header d-flex justify-content-between"><div><h5 class="card-title mb-1">Sales Summary</h5><small class="text-soft">Weekly store activity</small></div><span class="badge bg-label-primary">7 Days</span></div><div class="card-body"><div class="k-chart-bar"><canvas id="salesChart"></canvas></div><hr>
                        <div class="d-flex justify-content-between mb-2"><span class="text-soft">Add-to-cart clicks</span><strong>{{number_format($totalCartClicks)}}</strong></div><div class="k-progress mb-4"><span style="width:{{min(100,$totalCartClicks*10)}}%"></span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-soft">Items added</span><strong>{{number_format($totalCartQuantity)}}</strong></div><div class="k-progress mb-4"><span style="width:{{min(100,$totalCartQuantity*10)}}%;background:#03c3ec"></span></div>
                        <div class="d-flex justify-content-between"><div><small class="text-soft d-block">Potential revenue</small><strong>${{number_format($totalCartValue,2)}}</strong></div><span class="k-metric-icon k-green"><i class="bx bx-dollar"></i></span></div>
                    </div></div></div>

                    <div class="col-12 col-lg-4"><div class="card h-100"><div class="card-header d-flex justify-content-between"><div><h5 class="card-title mb-1">Popular Products</h5><small class="text-soft">Most added to cart</small></div><button class="k-menu"><i class="bx bx-dots-vertical-rounded"></i></button></div><div class="card-body pt-2">
                        @forelse($popularProducts as $item)
                            <div class="k-list-item"><span class="k-list-icon k-purple"><i class="bx bx-package"></i></span><div class="k-list-main"><p class="k-list-title">{{optional($item->product)->name??'Deleted product'}}</p><span class="k-list-sub">{{number_format($item->total_clicks??0)}} add-to-cart clicks</span></div><span class="k-list-value">{{number_format($item->total_quantity??0)}}</span></div>
                        @empty
                            <div class="text-center py-5"><span class="k-metric-icon k-purple mb-3"><i class="bx bx-cart"></i></span><p class="text-soft mb-0">No product activity yet.</p></div>
                        @endforelse
                    </div></div></div>
                </div>
            </main>
            @include('admin.footer')<div class="content-backdrop fade"></div>
        </div>
    </div>
</div><div class="layout-overlay layout-menu-toggle"></div></div>
@include('admin.js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded',()=>{
 const root=document.documentElement,menu=document.getElementById('layout-menu')||document.querySelector('aside.menu'),overlay=document.querySelector('.layout-overlay');
 const closeMenu=()=>root.classList.remove('k-mobile-menu-open','layout-menu-expanded');
 document.querySelectorAll('.layout-menu-toggle').forEach(toggle=>toggle.addEventListener('click',event=>{event.preventDefault();event.stopPropagation();root.classList.toggle('k-mobile-menu-open')}));
 if(overlay)overlay.addEventListener('click',closeMenu);
 if(menu){
  menu.querySelectorAll('.menu-item > .menu-link').forEach(link=>{const item=link.parentElement,sub=item.querySelector(':scope > .menu-sub');if(!sub)return;link.addEventListener('click',event=>{event.preventDefault();const open=!item.classList.contains('open');item.parentElement.querySelectorAll(':scope > .menu-item.open').forEach(other=>{if(other!==item)other.classList.remove('open')});item.classList.toggle('open',open);sub.style.display=open?'block':'none'})});
  const current=(location.pathname.replace(/\/$/,'')||'/').toLowerCase();menu.querySelectorAll('a.menu-link[href]').forEach(link=>{try{const path=new URL(link.href,location.origin).pathname.replace(/\/$/,'').toLowerCase();if(path&&path!=='#'&&path===current){link.parentElement.classList.add('active');let parent=link.closest('.menu-sub');while(parent){parent.style.display='block';parent.parentElement.classList.add('open');parent=parent.parentElement.closest('.menu-sub')}}}catch(error){}});
 }
 document.querySelectorAll('input[placeholder*="Search" i]').forEach(input=>input.addEventListener('keydown',event=>{if(event.key!=='Enter')return;const term=input.value.trim().toLowerCase(),match=[...document.querySelectorAll('#layout-menu .menu-link')].find(link=>link.textContent.toLowerCase().includes(term));if(match){event.preventDefault();const href=match.getAttribute('href');href&&href!=='#'?location.href=match.href:match.click()}}));
 document.querySelectorAll('.k-menu').forEach(button=>{button.type='button';button.addEventListener('click',event=>{event.stopPropagation();document.querySelectorAll('.k-card-actions').forEach(x=>x.remove());const panel=document.createElement('div');panel.className='k-card-actions show';panel.innerHTML='<button type="button"><i class="bx bx-refresh"></i> Refresh data</button>';const card=button.closest('.card');card.style.position='relative';card.appendChild(panel);panel.firstElementChild.onclick=()=>location.reload()})});
 document.addEventListener('click',()=>document.querySelectorAll('.k-card-actions').forEach(x=>x.remove()));
 const labels=@json($chartLabels),revenue=@json($salesData),clicks=@json($cartClickData),qty=@json($cartQuantityData);
 const common={responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{display:false},ticks:{color:'#a1acb8'}},y:{beginAtZero:true,grid:{color:'rgba(67,89,113,.08)'},ticks:{color:'#a1acb8'}}}};
 const r=document.getElementById('revenueChart');if(r)new Chart(r,{type:'line',data:{labels,datasets:[{label:'Revenue',data:revenue,borderColor:'#03c3ec',backgroundColor:'rgba(3,195,236,.12)',borderWidth:3,tension:.4,fill:true,pointRadius:3}]},options:common});
 const orderValues=[{{$pendingOrders}},{{$processingOrders}},{{$shippedOrders}},{{$deliveredOrders}}],hasOrders=orderValues.some(value=>value>0);
 const o=document.getElementById('orderChart');if(o)new Chart(o,{type:'doughnut',data:{labels:hasOrders?['Pending','Processing','Shipped','Delivered']:['No categorized orders'],datasets:[{data:hasOrders?orderValues:[1],backgroundColor:hasOrders?['#ffab00','#03c3ec','#696cff','#71dd37']:['#eceef1'],borderWidth:0}]},options:{responsive:true,maintainAspectRatio:false,cutout:'72%',plugins:{legend:{display:false}}}});
 const s=document.getElementById('salesChart');if(s)new Chart(s,{type:'bar',data:{labels,datasets:[{label:'Clicks',data:clicks,backgroundColor:'#696cff',borderRadius:5},{label:'Items',data:qty,backgroundColor:'#03c3ec',borderRadius:5}]},options:{...common,plugins:{legend:{display:true,position:'bottom',labels:{boxWidth:8,usePointStyle:true}}}}});
});
</script>
</body></html>
