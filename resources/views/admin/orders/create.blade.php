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



<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Create New Order</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.orders.store') }}" method="POST">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Order Number</label>
                    <input type="text" name="order_no" class="form-field form-control" value="ORD-{{ rand(10000, 99999) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Order Date</label>
                    <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Delivery Method</label>
                    <input type="text" name="delivery_method" class="form-control" placeholder="e.g. Standard Delivery" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer_name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Customer Email</label>
                    <input type="email" name="customer_email" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Customer Phone</label>
                    <input type="text" name="customer_phone" class="form-control">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fulfillment Status</label>
                    <select name="fulfillment_status" class="form-select">
                        <option value="unfulfilled">Unfulfilled</option>
                        <option value="partially_fulfilled">Partially Fulfilled</option>
                        <option value="fulfilled">Fulfilled</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Delivery Status</label>
                    <select name="delivery_status" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
            </div>

            <h5 class="mt-4">Order Items</h5>
            <table class="table table-bordered" id="items-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit Price ($)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="items[0][product_name]" class="form-control" required></td>
                        <td><input type="number" name="items[0][quantity]" class="form-control" min="1" value="1" required></td>
                        <td><input type="number" step="0.01" name="items[0][unit_price]" class="form-control" required></td>
                        <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-secondary mb-3" id="add-item">Add Product</button>

            <div class="text-end">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-success">Save Order</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let itemIndex = 1;
    document.getElementById('add-item').addEventListener('click', function () {
        let row = `<tr>
            <td><input type="text" name="items[${itemIndex}][product_name]" class="form-control" required></td>
            <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control" min="1" value="1" required></td>
            <td><input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="form-control" required></td>
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        </tr>`;
        document.querySelector('#items-table tbody').insertAdjacentHTML('beforeend', row);
        itemIndex++;
    });

    document.addEventListener('click', function(e) {
        if(e.target && e.target.classList.contains('remove-row')) {
            if(document.querySelectorAll('#items-table tbody tr').length > 1) {
                e.target.closest('tr').remove();
            }
        }
    });
</script>
@endpush



@include('admin.footer')

@include('admin.js')

  </body>
</html>
