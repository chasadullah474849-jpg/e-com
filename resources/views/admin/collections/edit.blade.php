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
    <div class="card mb-4">
        <h5 class="card-header">Edit Collection</h5>
        <div class="card-body">
            <!-- enctype added for file uploads, and routing points to update method with the collection id -->
            <form action="{{ route('admin.collections.update', $collection->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Crucial for Laravel update routing -->

                <!-- Collection Name -->
                <div class="mb-4">
                    <label for="collectionName" class="form-label">COLLECTION NAME <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="collectionName" name="name" value="{{ old('name', $collection->name) }}" placeholder="Enter collection name" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="form-label">DESCRIPTION (OPTIONAL)</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Describe this collection...">{{ old('description', $collection->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Category Dropdown (Cleaned of subcategories) -->
                <div class="mb-4">
                    <label for="category" class="form-label">CATEGORY <span class="text-danger">*</span></label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="category" name="category_id" required>
                        <option value="" disabled>Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $collection->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Main category for this collection</div>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status Dropdown -->
                <div class="mb-4">
                    <label for="status" class="form-label">STATUS <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="active" {{ old('status', strtolower($collection->status)) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', strtolower($collection->status)) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <div class="form-text">Set the visibility status of this collection</div>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Collection Image Edit & Preview -->
                <div class="mb-4">
                    <label for="collectionImage" class="form-label">COLLECTION IMAGE</label>

                    <!-- Current Image Preview if it exists -->
                    @if($collection->image)
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">Current Image:</small>
                            <img src="{{ asset('storage/' . $collection->image) }}" alt="Current Image" class="rounded border" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    @endif

                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="collectionImage" name="image" accept="image/*">
                    <div class="form-text">Leave empty if you do not want to change the image. Accepted formats: JPG, PNG, WEBP.</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Action Buttons -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Update Collection</button>
                    <a href="{{ route('admin.collections.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>



@include('admin.footer')

@include('admin.js')

  </body>
</html>
