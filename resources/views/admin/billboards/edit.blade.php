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


                <div class="container">

<form action="{{ route('billboards.update',$billboard->id) }}"
      method="POST">

    @csrf
    @method('PUT')

    <div class="mb-3">
    <label>Title</label>
    <input type="text"
           name="title"
           value="{{ $billboard->title }}"
           class="form-control">
</div>

<div class="mb-3">
    <label>Description</label>
    <textarea name="description"
              class="form-control"
              rows="4">{{ $billboard->description }}</textarea>
</div>

    <div class="mb-3">
        <label>Status</label>

        <select name="status"
                class="form-control">

            <option value="1" {{ $billboard->status == 1 ? 'selected' : '' }}>
                Active
            </option>

            <option value="0" {{ $billboard->status == 0 ? 'selected' : '' }}>
                Inactive
            </option>

        </select>
    </div>

    <button class="btn btn-success">
        Update Billboard
    </button>

</form>

</div>



@include('admin.footer')

@include('admin.js')

  </body>
</html>



