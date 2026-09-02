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

                
<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<div class="container-xxl flex-grow-1 container-p-y py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Add New Blog</h4>
            <p class="text-muted small mb-0">Create a new blog post for your website.</p>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> Back to Blogs
        </a>
    </div>

    <!-- Error Alert Box -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading fw-bold mb-1">Please fix the following errors:</h6>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="card-title fw-bold mb-1">Blog Information</h5>
            <p class="text-muted small mb-4">Enter the details of your new blog post below.</p>

            <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Blog Name -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Blog Name <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Fashion / Jul 11, 2022"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Blog Title -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Blog Title <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('title') is-invalid @enderror"
                           id="title"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="Top 10 fashion trend for summer"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="5"
                              placeholder="Write your blog content here..."
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Blog Image -->
                <div class="mb-3">
                    <label for="image" class="form-label fw-semibold">Blog Image</label>
                    <input type="file"
                           class="form-control @error('image') is-invalid @enderror"
                           id="image"
                           name="image"
                           accept="image/*">
                    <small class="text-muted d-block mt-1">Recommended formats: JPG, PNG, WEBP, max 2MB.</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status Radio Buttons -->
                <div class="mb-4">
                    <label class="form-label fw-semibold d-block">Status <span class="text-danger">*</span></label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('status') is-invalid @enderror"
                               type="radio"
                               name="status"
                               id="status_published"
                               value="Published"
                               {{ old('status', 'Published') == 'Published' || old('status') == '1' || old('status') == 'active' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_published">Published</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('status') is-invalid @enderror"
                               type="radio"
                               name="status"
                               id="status_draft"
                               value="Draft"
                               {{ old('status') == 'Draft' || old('status') == '0' || old('status') == 'inactive' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_draft">Draft</label>
                    </div>
                    @error('status')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Buttons -->
                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check me-1"></i> Save Blog
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



@include('admin.footer')

@include('admin.js')

  </body>
</html>
