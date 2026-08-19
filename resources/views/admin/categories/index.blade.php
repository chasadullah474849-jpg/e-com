<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================
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
        <!-- Layout container -->
        <div class="layout-page">
          @include('admin.nav')

          <div class="container-xxl flex-grow-1 container-p-y">
              <div class="d-flex justify-content-between align-items-center mb-3">
                  <h4 class="fw-bold py-3 mb-0">Categories</h4>
                  <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add New Category</a>
              </div>

              @if(session('success'))
                  <div class="alert alert-success alert-dismissible" role="alert">
                      {{ session('success') }}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
              @endif

              <div class="card">
                  <div class="table-responsive text-nowrap">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th>Name</th>
                                  <th>Description</th>
                                  <th>Image</th>
                                  <th>Status</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody class="table-border-bottom-0">
                              @forelse($categories as $category)
                              <tr id="category-row-{{ $category->uuid }}">
                                  <td><strong>{{ $category->name }}</strong></td>
                                  <td>{{ Str::limit($category->description, 50) }}</td>
                                  <td>
                                      @if($category->image)
                                          <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" width="50" height="50" class="rounded object-fit-cover">
                                      @else
                                          <span class="badge bg-label-secondary">No Image</span>
                                      @endif
                                  </td>
                                  <td>
                                      <span class="badge {{ $category->status === 'active' ? 'bg-label-success' : 'bg-label-danger' }} me-1">
                                          {{ ucfirst($category->status) }}
                                      </span>
                                  </td>
                                  <td>
                                      <a href="{{ route('admin.categories.edit', $category->uuid) }}" class="btn btn-sm btn-icon btn-outline-primary me-1">
                                          <i class="bx bx-edit-alt"></i>
                                      </a>
                                      <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-category" data-uuid="{{ $category->uuid }}">
                                          <i class="bx bx-trash"></i>
                                      </button>
                                  </td>
                              </tr>
                              @empty
                              <tr>
                                  <td colspan="5" class="text-center">No categories found.</td>
                              </tr>
                              @endforelse
                          </tbody>
                      </table>
                  </div>
                  <div class="px-3 pt-3">
                      {{ $categories->links() }}
                  </div>
              </div>
          </div>

         <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(function () {

    $('.delete-category').click(function () {

        let uuid = $(this).data('uuid');

        if (!confirm('Are you sure you want to delete this category?')) {
            return;
        }

        $.ajax({

            url: "{{ url('admin/categories/destroy') }}/" + uuid,

            type: "DELETE",

            data: {
                _token: "{{ csrf_token() }}"
            },

            success: function (response) {

                if (response.success) {

                    $('#category-row-' + uuid).remove();

                    alert(response.message);

                } else {

                    alert(response.message);

                }

            },

            error: function (xhr) {

                console.log(xhr.responseText);

                alert("Delete failed");

            }

        });

    });

});
</script>

          @include('admin.footer')
          @include('admin.js')
        </div>
      </div>
    </div>
  </body>
</html>
