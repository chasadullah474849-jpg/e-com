
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

            <h4>Edit Testimonial</h4>

        </div>

        <div class="card-body">

            <form action="{{ route('admin.testimonials.update',$testimonial->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label>Main Title</label>

                    <input
                        type="text"
                        name="main_title"
                        class="form-control"
                        value="{{ old('main_title',$testimonial->main_title) }}"
                        required>

                </div>

                <div class="mb-3">

                    <label>Name</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name',$testimonial->name) }}"
                        required>

                </div>

                <div class="mb-3">

                    <label>Description</label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control"
                        required>{{ old('description',$testimonial->description) }}</textarea>

                </div>

                <div class="mb-3">

                    <label>Status</label>

                    <select
                        name="status"
                        class="form-control">

                        <option value="active"
                            {{ $testimonial->status=='active' ? 'selected' : '' }}>

                            Active

                        </option>

                        <option value="inactive"
                            {{ $testimonial->status=='inactive' ? 'selected' : '' }}>

                            Inactive

                        </option>

                    </select>

                </div>

                <button class="btn btn-primary">

                    Update Testimonial

                </button>

                <a href="{{ route('admin.testimonials.index') }}"
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
