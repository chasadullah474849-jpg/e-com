
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

<div class="card mb-4">
  <h5 class="card-header">Profile Details</h5>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.profile.update') }}">
      @csrf @method('PUT')
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
      </div>
      <button class="btn btn-primary">Save changes</button>
    </form>
  </div>
</div>

<div class="card">
  <h5 class="card-header">Change Password</h5>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.profile.password') }}">
      @csrf @method('PUT')
      <div class="mb-3"><label class="form-label">Current password</label><input type="password" name="current_password" class="form-control"></div>
      <div class="mb-3"><label class="form-label">New password</label><input type="password" name="password" class="form-control"></div>
      <div class="mb-3"><label class="form-label">Confirm new password</label><input type="password" name="password_confirmation" class="form-control"></div>
      <button class="btn btn-primary">Change password</button>
    </form>
  </div>
</div>
