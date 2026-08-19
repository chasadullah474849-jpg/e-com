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
    <div class="card-header">
        <h4>Add Feature</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.features.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Icon</label>
                <input type="text"
                       name="icon"
                       class="form-control"
                       placeholder="fa-solid fa-truck">
            </div>

            <div class="mb-3">
                <label>Title</label>
                <input type="text"
                       name="title"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description"
                          class="form-control"
                          rows="4"></textarea>
            </div>

            <div class="mb-3">
    <label class="form-label d-block">Status</label>

    <div class="form-check form-check-inline mt-2">
        <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" checked>
        <label class="form-check-input-label text-success" for="statusActive">
            <span class="badge bg-label-success">Active</span>
        </label>
    </div>

    <div class="form-check form-check-inline mt-2">
        <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0">
        <label class="form-check-input-label text-danger" for="statusInactive">
            <span class="badge bg-label-danger">Inactive</span>
        </label>
    </div>
</div>

            <button class="btn btn-primary">
                Save Feature
            </button>

        </form>

    </div>
</div>


@include('admin.footer')

@include('admin.js')

  </body>
</html>
