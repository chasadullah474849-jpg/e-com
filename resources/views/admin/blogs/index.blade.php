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



<!-- Embedded Custom CSS for Professional Styling Fallback -->
<style>
    /* Card Container Styling */
    .blog-card {
        background: #ffffff;
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* Table Styles */
    .blog-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .blog-table thead th {
        background-color: #f8f9fa;
        color: #566a7f;
        font-weight: 600;
        font-size: 0.825rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        border-bottom: 2px solid #e9ecef;
    }

    .blog-table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
        color: #697a8d;
        font-size: 0.875rem;
    }

    .blog-table tbody tr:hover {
        background-color: #fbfbfc;
    }

    /* Thumbnail Styling */
    .blog-img-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }

    /* Description Truncation */
    .blog-description-truncate {
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        color: #8592a3;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.35em 0.65em;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 50rem;
        display: inline-block;
    }
    .status-active {
        background-color: #e8fadf;
        color: #71dd37;
    }
    .status-inactive {
        background-color: #ffe5e5;
        color: #ff3e1d;
    }

    /* UUID Small Tag */
    .uuid-tag {
        font-size: 0.75rem;
        font-family: monospace;
        color: #a1acb8;
    }

    /* Action Buttons */
    .action-btn-group {
        display: inline-flex;
        gap: 6px;
    }
    .btn-action {
        padding: 6px 12px;
        font-size: 0.8125rem;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
    }
    .btn-edit {
        background-color: rgba(105, 108, 255, 0.08);
        color: #696cff;
        border: 1px solid rgba(105, 108, 255, 0.2);
    }
    .btn-edit:hover {
        background-color: #696cff;
        color: #ffffff;
    }
    .btn-delete {
        background-color: rgba(255, 62, 29, 0.08);
        color: #ff3e1d;
        border: 1px solid rgba(255, 62, 29, 0.2);
    }
    .btn-delete:hover {
        background-color: #ff3e1d;
        color: #ffffff;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card blog-card">
        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3 px-4 border-bottom">
            <div>
                <h4 class="mb-1 fw-bold text-dark">Blogs Management</h4>
                <p class="mb-0 text-muted small">Manage all published and draft blog articles</p>
            </div>
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1">
                <i class="bx bx-plus fs-5"></i> Add New Blog
            </a>
        </div>

        <!-- Table Responsive Container -->
        <div class="table-responsive">
            <table class="table blog-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th style="width: 70px;">Image</th>
                        <th>UUID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th style="width: 90px;">Status</th>
                        <th style="width: 120px;">Created At</th>
                        <th class="text-center" style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $key => $blog)
                    <tr>
                        <td class="fw-semibold">{{ $key + 1 }}</td>
                        <td>
                            @if($blog->image)
                                <img src="{{ asset('uploads/blogs/' . $blog->image) }}"
                                     alt="{{ $blog->title }}"
                                     class="blog-img-thumbnail"
                                     onerror="this.onerror=null;this.src='{{ asset('users/images/no-image.png') }}';">
                            @else
                                <img src="{{ asset('users/images/no-image.png') }}"
                                     alt="No Image"
                                     class="blog-img-thumbnail">
                            @endif
                        </td>
                        <td>
                            <span class="uuid-tag">{{ $blog->uuid ?? $blog->id }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark d-block">{{ $blog->title }}</span>
                        </td>
                        <td>
                            <span class="blog-description-truncate" title="{{ strip_tags($blog->description) }}">
                                {{ strip_tags($blog->description ?? 'No description provided.') }}
                            </span>
                        </td>
                        <td>
                            @if(isset($blog->status) && ($blog->status == 1 || $blog->status == 'active'))
                                <span class="status-badge status-active">Active</span>
                            @else
                                <span class="status-badge status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-nowrap small text-muted">
                                {{ $blog->created_at ? $blog->created_at->format('d M, Y') : 'N/A' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="action-btn-group">
                                <a href="{{ route('admin.blogs.edit', $blog->uuid ?? $blog->id) }}"
                                   class="btn-action btn-edit"
                                   title="Edit Blog">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </a>

                                <form action="{{ route('admin.blogs.destroy', $blog->uuid ?? $blog->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this blog?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete border-0" title="Delete Blog">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bx bx-folder-open display-4 d-block mb-2 text-secondary"></i>
                                <p class="mb-0 fs-6 fw-semibold">No blogs found in the database.</p>
                                <small>Click "Add New Blog" to create your first entry.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($blogs, 'links'))
            <div class="card-footer bg-white d-flex justify-content-end py-3 border-top">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
</div>


@include('admin.footer')

@include('admin.js')

  </body>
</html>
