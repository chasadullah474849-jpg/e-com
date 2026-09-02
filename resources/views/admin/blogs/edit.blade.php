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

<!-- Fallback Stylesheet CDNs -->
<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<style>
    .edit-blog-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        background: #ffffff;
        overflow: hidden;
    }

    .edit-blog-card .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #eef2f7;
        padding: 1.25rem 1.75rem;
    }

    .edit-blog-card .form-label {
        font-weight: 600;
        color: #344054;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        margin-bottom: 0.4rem;
    }

    .edit-blog-card .form-control,
    .edit-blog-card .form-select {
        border-radius: 8px;
        border: 1px solid #d0d5dd;
        padding: 0.625rem 0.875rem;
        font-size: 0.95rem;
        transition: all 0.2s ease-in-out;
    }

    .edit-blog-card .form-control:focus,
    .edit-blog-card .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    .img-preview-box {
        position: relative;
        width: 110px;
        height: 110px;
        border-radius: 10px;
        overflow: hidden;
        border: 2px dashed #d0d5dd;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .img-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-primary-custom {
        background-color: #696cff;
        border-color: #696cff;
        color: #ffffff;
        border-radius: 8px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-primary-custom:hover {
        background-color: #5f61e6;
        border-color: #5f61e6;
        color: #ffffff;
    }

    .btn-secondary-custom {
        background-color: #f3f4f6;
        border-color: #f3f4f6;
        color: #374151;
        border-radius: 8px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
    }

    .btn-secondary-custom:hover {
        background-color: #e5e7eb;
        color: #1f2937;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y py-4">
    <div class="card edit-blog-card">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold text-dark">Edit Blog</h4>
                <p class="mb-0 text-muted small">Update article details and metadata</p>
            </div>
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary-custom d-inline-flex align-items-center gap-1 text-decoration-none">
                <i class="bx bx-arrow-back fs-5"></i> Back to List
            </a>
        </div>

        <!-- Form Body -->
        <div class="card-body p-4">
            <form action="{{ route('admin.blogs.update', ['id' => $blog->uuid ?? $blog->id]) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Author Field -->
                    <div class="col-md-6">
                        <label for="name" class="form-label text-uppercase">Author / Subtitle Name</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $blog->name ?? '') }}"
                               placeholder="Enter author or short name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title Field -->
                    <div class="col-md-6">
                        <label for="title" class="form-label text-uppercase">Blog Title <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title', $blog->title ?? '') }}"
                               required
                               placeholder="Enter blog title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Field -->
                    <div class="col-md-6">
                        <label for="status" class="form-label text-uppercase">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="1" {{ old('status', $blog->status) == '1' || old('status', $blog->status) == 'active' || old('status', $blog->status) == 'Active' || old('status', $blog->status) == 'published' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $blog->status) == '0' || old('status', $blog->status) == 'inactive' || old('status', $blog->status) == 'Inactive' || old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Featured Image Field -->
                    <div class="col-md-6">
                        <label for="image" class="form-label text-uppercase">Featured Image</label>
                        <input type="file"
                               class="form-control @error('image') is-invalid @enderror"
                               id="image"
                               name="image"
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if(!empty($blog->image))
                            <div class="mt-3 d-flex align-items-center gap-3">
                                <div class="img-preview-box">
                                    <img src="{{ asset('uploads/blogs/' . $blog->image) }}"
                                         alt="{{ $blog->title ?? 'Blog image' }}"
                                         onerror="this.onerror=null;this.src='https://via.placeholder.com/100?text=No+Image';">
                                </div>
                                <div>
                                    <span class="badge bg-light text-dark border">CURRENT IMAGE</span>
                                    <p class="mb-0 text-muted small mt-1">Upload a new file to replace</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Description Field -->
                    <div class="col-12">
                        <label for="description" class="form-label text-uppercase">Description / Content</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="6"
                                  placeholder="Write blog post content here...">{{ old('description', $blog->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-12 text-end pt-3 border-top mt-4">
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary-custom me-2 text-decoration-none">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bx bx-save me-1"></i> Update Blog
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@include('admin.footer')

@include('admin.js')

  </body>
</html>
