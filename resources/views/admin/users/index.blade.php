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

                <!-- Main Content  -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">User Management</h4>
   <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createUserModal">
    <i class="bx bx-plus me-1"></i> Create User
</button>
</div>
              <table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="table-border-bottom-0">
        @forelse($users as $user)
            <tr>
                <td>
                    <i class="bx bx-user text-primary me-3"></i>
                    <strong>{{ $user->name }}</strong>
                </td>

                <td>{{ $user->email }}</td>

                <td>
                    @if($user->status === 'active' || !isset($user->status))
                        <span class="badge bg-label-primary me-1">Active</span>
                    @else
                        <span class="badge bg-label-danger me-1">Inactive</span>
                    @endif
                </td>

                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button type="button" class="dropdown-item btnEditUser" data-id="{{ $user->id }}">
    <i class="bx bx-edit-alt me-1"></i> Edit
</button>

                            <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bx bx-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-muted">
                    No users found in database records.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
                 <!-- //Main Content  -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="createUserModalLabel">Add New User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formAuthentication" action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required autofocus />
                        <div class="invalid-feedback id-error-name"></div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com" required />
                        <div class="invalid-feedback id-error-email"></div>
                    </div>

                    <div class="mb-3 form-password-toggle">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            <div class="invalid-feedback id-error-password"></div>
                        </div>
                    </div>

                    <div class="mb-4 form-password-toggle">
                        <label class="form-label" for="password-confirm">Confirm Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password-confirm" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save User Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editUserModalLabel">Modify User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formEditUser" method="POST">
                    @csrf
                   <input type="hidden" id="edit_user_id" name="user_id">

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required />
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required />
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3 bg-light p-3 rounded">
                        <small class="text-muted d-block mb-2">Leave blank if you do not want to alter their current password.</small>

                        <label for="edit_password" class="form-label">New Password</label>
                        <div class="input-group input-group-merge mb-2">
                            <input type="password" id="edit_password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            <div class="invalid-feedback"></div>
                        </div>

                        <label for="edit_password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="edit_password_confirmation" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Details</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@include('admin.js')
<script>
$(document).ready(function() {
    // 1. Initialize Notyf
    const notyf = new Notyf({
        duration: 4000,
        position: { x: 'right', y: 'top' },
        ripple: true
    });

    // 2. Handle Form Processing via AJAX
    $('#formAuthentication').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let submitBtn = form.find('button[type="submit"]');

        // Reset bootstrap programmatic error layouts before running validation
        form.find('.form-control').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        submitBtn.prop('disabled', true).text('Processing...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                // Flash success toast notification
                notyf.success('User generated successfully!');

                // Hide the modal container completely
                $('#createUserModal').modal('hide');

                // Clear out text values inside the modal input form
                form[0].reset();
                submitBtn.prop('disabled', false).text('Save User Account');

                // Reload the page smoothly to fetch the new database dataset,
                // or you can alternatively use `location.reload()` if you want an absolute structural state check.
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).text('Save User Account');

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(key, value) {
                        // Toast alert error via Notyf
                        notyf.error(value[0]);

                        // Highlight targeted layout field elements directly inside the modal interface
                        let inputField = form.find('[name="' + key + '"]');
                        inputField.addClass('is-invalid');

                        // Set the text of the error element next to the field
                        inputField.siblings('.invalid-feedback').text(value[0]);
                        // Handle password input-group wraps
                        inputField.closest('.input-group').find('.invalid-feedback').text(value[0]);
                    });
                } else {
                    notyf.error('An unexpected backend structural communication error occurred.');
                }
            }
        });
    });
});
</script>


<script>


    // ==========================================
// 1. FETCH USER PROFILE DATA INTO EDIT MODAL
// ==========================================
$(document).on('click', '.btnEditUser', function() {
    let userId = $(this).data('id');

    // Clear out residual validation designs
    $('#formEditUser').find('.form-control').removeClass('is-invalid');
    $('#formEditUser').find('.invalid-feedback').text('');
    $('#formEditUser')[0].reset();

    // Construct url dynamically using individual route parameters
    let fetchUrl = "{{ url('admin/users') }}/" + userId + "/fetch";

    $.ajax({
        url: fetchUrl,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                // Populate inputs with current database settings
                $('#edit_user_id').val(response.user.id);
                $('#edit_name').val(response.user.name);
                $('#edit_email').val(response.user.email);

                // Fire up modal target interface
                $('#editUserModal').modal('show');
            }
        },
        error: function() {
            notyf.error('Could not fetch user record.');
        }
    });
});

// ==========================================
// 2. SUBMIT MODIFIED USER CHANGES VIA AJAX
// ==========================================
$('#formEditUser').on('submit', function(e) {
    e.preventDefault();

    let form = $(this);
    let userId = $('#edit_user_id').val();
    let submitBtn = form.find('button[type="submit"]');

    form.find('.form-control').removeClass('is-invalid');
    form.find('.invalid-feedback').text('');

    submitBtn.prop('disabled', true).text('Updating...');

    // Point form payload to the individual UPDATE route structure
    let updateUrl = "{{ url('admin/users') }}/" + userId;

    $.ajax({
        url: updateUrl,
        type: 'POST', // Handled as POST because data holds @method('PUT')
        data: form.serialize(),
        success: function(response) {
            notyf.success(response.message);
            $('#editUserModal').modal('hide');

            setTimeout(function() {
                location.reload(); // Refresh viewport dynamically
            }, 1000);
        },
        error: function(xhr) {
            submitBtn.prop('disabled', false).text('Update Details');

            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    notyf.error(value[0]);

                    let inputField = form.find('[name="' + key + '"]');
                    inputField.addClass('is-invalid');
                    inputField.siblings('.invalid-feedback').text(value[0]);
                    inputField.closest('.input-group').find('.invalid-feedback').text(value[0]);
                });
            } else {
                notyf.error('An unexpected transmission update error occurred.');
            }
        }
    });
});
</script>

  </body>
</html>
