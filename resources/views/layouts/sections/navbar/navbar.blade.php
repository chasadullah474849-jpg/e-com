<!-- User Dropdown Menu -->
<li class="nav-item navbar-dropdown dropdown-user dropdown">
  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
    <div class="avatar avatar-online">
      <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
    </div>
  </a>
  <ul class="dropdown-menu dropdown-menu-end">
    <li>
      <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
        <div class="d-flex">
          <div class="flex-shrink-0 me-3">
            <div class="avatar avatar-online">
              <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
            </div>
          </div>
          <div class="flex-grow-1">
            <span class="fw-semibold d-block">{{ Auth::user()->name ?? 'Admin User' }}</span>
            <small class="text-muted">{{ Auth::user()->role ?? 'Admin' }}</small>
          </div>
        </div>
      </a>
    </li>
    <li>
      <div class="dropdown-divider"></div>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
        <i class="bx bx-user me-2"></i>
        <span class="align-middle">My Profile</span>
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
        <i class="bx bx-cog me-2"></i>
        <span class="align-middle">Settings</span>
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route('admin.billing') }}">
        <span class="d-flex align-items-center align-middle">
          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
          <span class="flex-grow-1 align-middle">Billing</span>
          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
        </span>
      </a>
    </li>
    <li>
      <div class="dropdown-divider"></div>
    </li>
    <li>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="dropdown-item">
          <i class="bx bx-power-off me-2"></i>
          <span class="align-middle">Log Out</span>
        </button>
      </form>
    </li>
  </ul>
</li>
<!--/ User Dropdown Menu -->
