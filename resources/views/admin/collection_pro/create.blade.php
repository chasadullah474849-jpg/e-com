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


               
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Collection Pro /</span> Create</h4>
        <a href="{{ route('collection-pro.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add Collection Pro Item</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('collection-pro.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="CLASSIC WINTER COLLECTION" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Enter collection description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="image">Image</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="button_link">Button Link</label>
                            <input type="text" name="button_link" id="button_link" class="form-control @error('button_link') is-invalid @enderror" value="{{ old('button_link') }}" placeholder="https://example.com">
                            @error('button_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Active Status</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Collection Pro</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@include('admin.footer')

@include('admin.js')

  </body>
</html>
