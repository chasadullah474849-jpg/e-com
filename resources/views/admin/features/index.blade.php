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


                <div class="card">

    <div class="card-header d-flex justify-content-between">
        <h4>Features</h4>

        <a href="{{ route('admin.features.create') }}"
           class="btn btn-primary">
            Add Feature
        </a>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>#</th>
                    <th>UUID</th>
                    <th>Icon</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
    @foreach($features as $key => $feature)
        <tr>
            <td>{{ $key + 1 }}</td>

            <td>{{ $feature->uuid }}</td>

            <td>{{ $feature->icon }}</td>

            <td>{{ $feature->title }}</td>

            <td>{{ $feature->description }}</td>

            <td>
                @if($feature->status == 1)
                    <span class="badge bg-label-success">Active</span>
                @else
                    <span class="badge bg-label-danger">Inactive</span>
                @endif
            </td>

            <td>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.features.edit', $feature->uuid) }}" class="btn btn-sm btn-success">Edit</a>

                    <form action="{{ route('admin.features.destroy', $feature->uuid) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
</tbody>

        </table>

    </div>

</div>


@include('admin.footer')

@include('admin.js')

  </body>
</html>
