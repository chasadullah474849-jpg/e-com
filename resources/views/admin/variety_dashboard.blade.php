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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container my-5" style="max-width: 1200px;">
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
            <div>
                <h1 class="h3 mb-1 fw-bold text-dark">Variety Dynamic Management</h1>
                <p class="text-muted small mb-0">Manage and organize your e-commerce product varieties, hierarchical chains, and inventory statuses.</p>
            </div>
            <button type="button" class="btn btn-primary px-4 py-2 fw-semibold rounded-3 shadow-sm d-inline-flex align-items-center gap-2" id="openAddModalBtn">
                <i class="bi bi-plus-lg"></i> Add Variety
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="varietiesTable">
                <thead class="table-light border-bottom">
                    <tr class="text-uppercase text-muted fs-7 fw-bold tracking-wider">
                        <th class="py-3 px-4 text-center" style="width: 80px;">ID</th>
                        <th class="py-3 px-4">Hierarchy <span class="text-lowercase text-muted fw-normal">(category &gt; sub &gt; prod)</span></th>
                        <th class="py-3 px-4">Variety Name</th>
                        <th class="py-3 px-4">Description</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-secondary fs-6">
                    @forelse($varieties as $variety)
                    <tr id="row-{{ $variety->id }}">
                        <td class="py-3 px-4 text-center text-muted fw-medium">#{{ $variety->id }}</td>
                        <td class="py-3 px-4">
                            <div class="d-flex align-items-center gap-2 flex-wrap dynamic-hierarchy-tags">
                                <span class="badge bg-light text-primary border px-2 py-1.5 rounded">{{ $variety->product->subcategory->category->name ?? 'N/A' }}</span>
                                <span class="text-muted small">&rarr;</span>
                                <span class="badge bg-light text-secondary border px-2 py-1.5 rounded">{{ $variety->product->subcategory->name ?? 'N/A' }}</span>
                                <span class="text-muted small">&rarr;</span>
                                <span class="fw-bold text-dark">{{ $variety->product->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 fw-bold text-dark name-cell">{{ $variety->name }}</td>
                        <td class="py-3 px-4 text-muted text-truncate desc-cell" style="max-width: 250px;">{{ $variety->description ?? 'No description provided.' }}</td>
                        <td class="py-3 px-4 text-center status-cell">
                            @if(($variety->status ?? 'active') == 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fs-7">Active</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fs-7">Inactive</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-end">
                            <div class="d-inline-flex gap-2">
                                <button type="button" class="btn btn-sm btn-light text-primary border-0 p-2 rounded-3 edit-btn" data-id="{{ $variety->id }}" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light text-danger border-0 p-2 rounded-3 delete-btn" data-id="{{ $variety->id }}" title="Delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="empty-table-notice">
                        <td colspan="6" class="text-center py-5 text-muted">No varieties found. Click "Add Variety" to get started.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="varietyModal" tabindex="-1" aria-labelledby="varietyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark" id="varietyModalLabel">Create New Variety</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="varietyForm">
                @csrf
                <input type="hidden" id="variety_id" name="variety_id">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="category_select" class="form-label fw-semibold text-secondary">Category</label>
                        <select id="category_select" class="form-select border-gray-300 rounded-3 py-2" required>
                            <option value="">Choose Category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="subcategory_select" class="form-label fw-semibold text-secondary">Subcategory</label>
                        <select id="subcategory_select" class="form-select border-gray-300 rounded-3 py-2" disabled required>
                            <option value="">Choose item...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="product_select" class="form-label fw-semibold text-secondary">Product</label>
                        <select id="product_select" name="product_id" class="form-select border-gray-300 rounded-3 py-2" disabled required>
                            <option value="">Choose item...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="variety_name" class="form-label fw-semibold text-secondary">Variety Name</label>
                        <input type="text" id="variety_name" name="name" class="form-control border-gray-300 rounded-3 py-2" placeholder="e.g., XL Titanium Black" required>
                    </div>

                    <div class="mb-3">
                        <label for="variety_description" class="form-label fw-semibold text-secondary">Description</label>
                        <textarea id="variety_description" name="description" rows="3" class="form-control border-gray-300 rounded-3" placeholder="Optional variant features details..."></textarea>
                    </div>

                    <div>
                        <label for="variety_status" class="form-label fw-semibold text-secondary">Status</label>
                        <select id="variety_status" name="status" class="form-select border-gray-300 rounded-3 py-2">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light rounded-3 border px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4" id="saveFormBtn">Save Variety</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    const bsModal = new bootstrap.Modal(document.getElementById('varietyModal'));

    // A. Clear form layout setup when adding a record
    $('#openAddModalBtn').on('click', function() {
        $('#varietyForm')[0].reset();
        $('#variety_id').val('');
        $('#varietyModalLabel').text('Create New Variety');
        $('#subcategory_select').html('<option value="">Choose item...</option>').prop('disabled', true);
        $('#product_select').html('<option value="">Choose item...</option>').prop('disabled', true);
        bsModal.show();
    });

    // B. Handle Category selection change to chain subcategories
    $('#category_select').on('change', function () {
        const categoryId = this.value;
        $('#subcategory_select').html('<option value="">Choose item...</option>').prop('disabled', true);
        $('#product_select').html('<option value="">Choose item...</option>').prop('disabled', true);

        if (categoryId) {
            $.get(`/admin/get-subcategories/${categoryId}`, function (data) {
                if(data.length > 0) {
                    $('#subcategory_select').prop('disabled', false);
                    data.forEach(sub => {
                        $('#subcategory_select').append(`<option value="${sub.id}">${sub.name}</option>`);
                    });
                }
            });
        }
    });

    // C. Handle Subcategory selection change to chain products
    $('#subcategory_select').on('change', function () {
        const subcategoryId = this.value;
        $('#product_select').html('<option value="">Choose item...</option>').prop('disabled', true);

        if (subcategoryId) {
            $.get(`/admin/get-products/${subcategoryId}`, function (data) {
                if(data.length > 0) {
                    $('#product_select').prop('disabled', false);
                    data.forEach(prod => {
                        $('#product_select').append(`<option value="${prod.id}">${prod.name}</option>`);
                    });
                }
            });
        }
    });

    // D. AJAX CRUD: Save or Update Record
    $('#varietyForm').on('submit', function(e) {
        e.preventDefault();
        $('#saveFormBtn').prop('disabled', true).text('Saving...');

        $.ajax({
            url: "{{ route('admin.varieties.store') }}", // Fixed named route reference matching web.php
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    const item = response.data;
                    $('#empty-table-notice').remove();

                    const statusBadge = item.status === 'active'
                        ? `<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fs-7">Active</span>`
                        : `<span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fs-7">Inactive</span>`;

                    const newRowHtml = `
                        <tr id="row-${item.id}">
                            <td class="py-3 px-4 text-center text-muted fw-medium">#${item.id}</td>
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge bg-light text-primary border px-2 py-1.5 rounded">${item.product.subcategory.category.name}</span>
                                    <span class="text-muted small">&rarr;</span>
                                    <span class="badge bg-light text-secondary border px-2 py-1.5 rounded">${item.product.subcategory.name}</span>
                                    <span class="text-muted small">&rarr;</span>
                                    <span class="fw-bold text-dark">${item.product.name}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 fw-bold text-dark name-cell">${item.name}</td>
                            <td class="py-3 px-4 text-muted text-truncate desc-cell" style="max-width: 250px;">${item.description || 'No description provided.'}</td>
                            <td class="py-3 px-4 text-center status-cell">${statusBadge}</td>
                            <td class="py-3 px-4 text-end">
                                <div class="d-inline-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-light text-primary border-0 p-2 rounded-3 edit-btn" data-id="${item.id}"><i class="bi bi-pencil-square"></i></button>
                                    <button type="button" class="btn btn-sm btn-light text-danger border-0 p-2 rounded-3 delete-btn" data-id="${item.id}"><i class="bi bi-trash3"></i></button>
                                </div>
                            </td>
                        </tr>`;

                    if($('#variety_id').val()) {
                        $(`#row-${item.id}`).replaceWith(newRowHtml);
                    } else {
                        $('#varietiesTable tbody').prepend(newRowHtml);
                    }
                    bsModal.hide();
                }
            },
            error: function(err) { console.error("Saving failed", err); },
            complete: function() { $('#saveFormBtn').prop('disabled', false).text('Save Variety'); }
        ]);
    });

    // E. AJAX CRUD: Fetch record details and load into Modal
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.get(`/admin/edit-variety/${id}`, function(data) { // Fixed matching route syntax path URL
            $('#variety_id').val(data.variety.id);
            $('#variety_name').val(data.variety.name);
            $('#variety_description').val(data.variety.description);
            $('#variety_status').val(data.variety.status);
            $('#category_select').val(data.category_id);

            $('#subcategory_select').html('<option value="">Choose item...</option>').prop('disabled', false);
            data.subcategories.forEach(sub => {
                $('#subcategory_select').append(`<option value="${sub.id}" ${sub.id == data.subcategory_id ? 'selected' : ''}>${sub.name}</option>`);
            });

            $('#product_select').html('<option value="">Choose item...</option>').prop('disabled', false);
            data.products.forEach(prod => {
                $('#product_select').append(`<option value="${prod.id}" ${prod.id == data.product_id ? 'selected' : ''}>${prod.name}</option>`);
            });

            $('#varietyModalLabel').text('Modify Variety Attributes');
            bsModal.show();
        });
    });

    // F. AJAX CRUD: Delete processing row item dynamically
    $(document).on('click', '.delete-btn', function() {
        if(confirm('Are you completely sure you want to remove this variety?')) {
            const id = $(this).data('id');
            $.ajax({
                url: `/admin/delete-variety/${id}`, // Fixed matching route syntax path URL
                method: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(res) {
                    if(res.success) $(`#row-${id}`).remove();
                }
            });
        }
    });
});
</script>



@include('admin.footer')

@include('admin.js')

  </body>
</html>
