<!DOCTYPE html>

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

        <div class="layout-page">
          @include('admin.nav')

          <div class="container-fluid">
            <div class="card">
              <div class="card-header d-flex justify-content-between">
                <h4>Testimonials</h4>
                <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
                    Add Testimonial
                </a>
              </div>

              <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-bordered table-hover">
                  <thead class="table-dark">
                    <tr>
                      <th>UUID</th>
                      <th>Main Title</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Status</th>
                      <th width="170">Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    @forelse($testimonials as $testimonial)
                        <tr>
                            <td>{{ $testimonial->id }}</td>

                            <td>{{ $testimonial->main_title }}</td>

                            <td>{{ $testimonial->name }}</td>

                            <td>{{ $testimonial->description }}</td>

                            <td>
                                @if($testimonial->status === 'active' || $testimonial->status == 1)
                                    <span class="badge bg-label-success">Active</span>
                                @else
                                    <span class="badge bg-label-danger">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn btn-sm btn-success">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this testimonial?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Testimonials Found</td>
                        </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          @include('admin.footer')
          @include('admin.js')
        </div>
      </div>
    </div>
  </body>
</html>
