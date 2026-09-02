<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    @include('admin.header')
  </head>

  <body>
    @include('admin.sidebar')
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->


        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
                @include('admin.nav')

<div class="container-fluid flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Admin /</span> Orders Management
        </h4>
        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Create New Order
        </a>
    </div>

    <!-- Orders Table Card -->
    <div class="card shadow-sm">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">All Orders</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order No</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Fulfillment</th>
                        <th>Delivery Status</th>
                        <th>Method</th>
                        <th>Items</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-semibold text-primary">
                                #{{ $order->order_no }}
                            </a>
                        </td>
                        <td>{{ $order->order_date }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ $order->customer_name }}</span>
                                <small class="text-muted">{{ $order->customer_email }}</small>
                            </div>
                        </td>
                        <td class="fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            @php
                                $paymentBadges = [
                                    'paid' => 'bg-label-success',
                                    'pending' => 'bg-label-warning',
                                    'failed' => 'bg-label-danger',
                                    'refunded' => 'bg-label-info'
                                ];
                            @endphp
                            <span class="badge {{ $paymentBadges[$order->payment_status] ?? 'bg-label-secondary' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $fulfillmentBadges = [
                                    'fulfilled' => 'bg-label-success',
                                    'unfulfilled' => 'bg-label-secondary',
                                    'partially_fulfilled' => 'bg-label-info',
                                    'cancelled' => 'bg-label-danger'
                                ];
                            @endphp
                            <span class="badge {{ $fulfillmentBadges[$order->fulfillment_status] ?? 'bg-label-secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $order->fulfillment_status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-label-primary">
                                {{ ucfirst($order->delivery_status) }}
                            </span>
                        </td>
                        <td><span class="text-body">{{ $order->delivery_method }}</span></td>
                        <td>
                            <span class="badge rounded-pill bg-light text-dark border">
                                {{ $order->items->count() }} item(s)
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-icon btn-outline-info" title="View">
                                    <i class="bx bx-show"></i> View
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-icon btn-outline-warning" title="Edit">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Delete">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="bx bx-package fs-1 d-block mb-2"></i>
                            No orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-end">
            {{ $orders->links() }}
        </div>
    </div>
</div>




@include('admin.footer')

@include('admin.js')

  </body>
</html>
