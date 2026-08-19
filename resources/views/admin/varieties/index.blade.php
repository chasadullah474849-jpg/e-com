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
<div class="mb-3 text-end">

    <a href="{{ route('admin.varieties.create') }}"
       class="btn btn-primary">
       Add Variety
    </a>

</div>
  <table class="table">
                    <thead>
                      <tr>
                        <th>UUid</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($varieties as $variety)

                      <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ $variety->uuid }}</strong></td>
                        <td>{{ $variety->name }}</td>
                        <td>
                        {{ $variety->description }}
                        </td>
                        <td>    @if($variety->status)
            <span class="badge bg-label-success">
                Active
            </span>
        @else
            <span class="badge bg-label-danger">
                Inactive
            </span>
        @endif
    </td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a href="{{ route('admin.varieties.edit',$variety->id) }}"
           class="btn btn-sm btn-primary">
            Edit
        </a>

                                <form action="{{ route('admin.varieties.destroy',$variety->id) }}"
              method="POST"
              style="display:inline-block">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="btn btn-sm btn-danger"
                    onclick="return confirm('Delete this variety?')">
                Delete
            </button>

        </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                      @endforeach


                    </tbody>
                  </table>

@include('admin.footer')

@include('admin.js')

  </body>
</html>
