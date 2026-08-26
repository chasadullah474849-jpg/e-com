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


                <div class="container-fluid">

<div class="card">

<div class="card-header">

<h4>Create Blog</h4>

</div>

<div class="card-body">

<form action="{{ route('admin.blogs.store') }}"
      method="POST"
      enctype="multipart/form-data">

@csrf

<div class="mb-3">

<label>Category</label>

<select name="category_id" class="form-control">

<option value="">Select Category</option>

@foreach($categories as $category)

<option value="{{ $category->id }}">
{{ $category->name }}
</option>

@endforeach

</select>

</div>

<div class="mb-3">

<label>Name</label>

<input type="text"
       name="name"
       class="form-control"
       value="{{ old('name') }}">

</div>

<div class="mb-3">

<label>Title</label>

<input type="text"
       name="title"
       class="form-control"
       value="{{ old('title') }}">

</div>

<div class="mb-3">

<label>Description</label>

<textarea name="description"
          class="form-control"
          rows="4">{{ old('description') }}</textarea>

</div>


<div class="mb-3">

<label>Image</label>

<input type="file"
       name="image"
       class="form-control">

</div>

<div class="mb-3">

<label>Status</label>

<select name="status" class="form-control">

<option value="1">Active</option>

<option value="0">Inactive</option>

</select>

</div>

<button class="btn btn-success">

Save Blog

</button>

<a href="{{ route('admin.blogs.index') }}"
class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>


@include('admin.footer')

@include('admin.js')

  </body>
</html>
