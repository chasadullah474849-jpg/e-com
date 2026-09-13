@php
    use Illuminate\Support\Facades\Route;

    $adminUser = auth()->user();

    $adminName = $adminUser?->name ?? 'Administrator';
    $adminRole = $adminUser?->role ?? 'Admin';

    $defaultAvatar = asset('admins/assets/img/avatars/1.png');

    $adminAvatar = !empty($adminUser?->avatar)
        ? asset('storage/' . ltrim($adminUser->avatar, '/'))
        : $defaultAvatar;

    $profileUrl = Route::has('admin.profile')
        ? route('admin.profile')
        : url('/admin/profile');

    $settingsUrl = Route::has('admin.settings')
        ? route('admin.settings')
        : url('/admin/settings');

    $billingUrl = Route::has('admin.billing')
        ? route('admin.billing')
        : url('/admin/billing');

    $logoutUrl = Route::has('admin.logout')
        ? route('admin.logout')
        : url('/admin/logout');
@endphp

<style>
    #layout-navbar {
        min-height: 64px;
        padding: 0.625rem 1.5rem;
        border-radius: 0.5rem;
        background: #ffffff;
        box-shadow: 0 0 0.375rem 0.25rem rgba(161, 172, 184, 0.15);
        z-index: 10;
    }

    #layout-navbar .form-control {
        min-height: 40px;
        background: transparent;
    }

    #layout-navbar .form-control:focus {
        box-shadow: none;
    }

    #layout-navbar .nav-link {
        color: #697a8d;
    }

    #layout-navbar .navbar-nav-right {
        width: 100%;
    }

    #layout-navbar .avatar {
        position: relative;
        width: 40px;
        height: 40px;
    }

    #layout-navbar .avatar img {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }

    #layout-navbar .avatar-online::after {
        content: "";
        position: absolute;
        right: 1px;
        bottom: 1px;
        width: 10px;
        height: 10px;
        background: #71dd37;
        border: 2px solid #ffffff;
        border-radius: 50%;
    }

    #layout-navbar .dropdown-menu {
        min-width: 230px;
        padding: 0.5rem 0;
        border: 0;
        border-radius: 0.5rem;
        box-shadow: 0 0.25rem 1rem rgba(67, 89, 113, 0.16);
    }

    #layout-navbar .dropdown-item {
        display: flex;
        align-items: center;
        padding: 0.65rem 1.25rem;
        color: #697a8d;
    }

    #layout-navbar .dropdown-item:hover {
        color: #696cff;
        background: rgba(105, 108, 255, 0.08);
    }

    #layout-navbar .dropdown-item i {
        font-size: 1.2rem;
    }

    #layout-navbar .dropdown-divider {
        margin: 0.5rem 0;
    }

    #layout-navbar .badge-center {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    #layout-navbar .w-px-20 {
        width: 20px !important;
    }

    #layout-navbar .h-px-20 {
        height: 20px !important;
    }

    #layout-navbar .w-px-40 {
        width: 40px !important;
    }

    @media (max-width: 767.98px) {
        #layout-navbar {
            padding: 0.5rem 0.75rem;
        }

        #layout-navbar .github-wrapper {
            display: none;
        }

        #layout-navbar .navbar-search {
            max-width: calc(100vw - 155px);
        }
    }
</style>

<nav
    class="layout-navbar container-xxl navbar navbar-expand-xl
           navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar"
>
    <!-- Mobile sidebar button -->
    <div
        class="layout-menu-toggle navbar-nav
               align-items-xl-center me-3 me-xl-0 d-xl-none"
    >
        <a
            class="nav-item nav-link px-0 me-xl-4"
            href="javascript:void(0);"
            id="mobileMenuToggle"
            aria-label="Open sidebar"
        >
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div
        class="navbar-nav-right d-flex align-items-center"
        id="navbar-collapse"
    >
        <!-- Search -->
        <div class="navbar-nav align-items-center navbar-search">
            <div class="nav-item d-flex align-items-center w-100">
                <i class="bx bx-search fs-4 lh-0"></i>

                <input
                    type="search"
                    id="adminNavbarSearch"
                    class="form-control border-0 shadow-none"
                    placeholder="Search..."
                    aria-label="Search admin panel"
                    autocomplete="off"
                >
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- GitHub -->
            <li class="nav-item lh-1 me-3 github-wrapper">
                <a
                    class="github-button"
                    href="https://github.com/chasadullah474849-jpg/e-com"
                    data-icon="octicon-star"
                    data-size="large"
                    data-show-count="true"
                    aria-label="Star chasadullah474849-jpg/e-com on GitHub"
                >
                    Star
                </a>
            </li>

            <!-- User dropdown -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a
                    class="nav-link dropdown-toggle hide-arrow"
                    href="#"
                    id="adminUserDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <div class="avatar avatar-online">
                        <img
                            src="{{ $adminAvatar }}"
                            alt="{{ $adminName }}"
                            class="w-px-40 h-auto rounded-circle"
                            onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';"
                        >
                    </div>
                </a>

                <ul
                    class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="adminUserDropdown"
                >
                    <!-- User information -->
                    <li>
                        <div class="dropdown-item-text px-3 py-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img
                                            src="{{ $adminAvatar }}"
                                            alt="{{ $adminName }}"
                                            class="w-px-40 h-auto rounded-circle"
                                            onerror="this.onerror=null;this.src='{{ $defaultAvatar }}';"
                                        >
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">
                                        {{ $adminName }}
                                    </span>

                                    <small class="text-muted">
                                        {{ ucfirst($adminRole) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <!-- Profile -->
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
    <i class="bx bx-user me-2"></i>
    <span class="align-middle">My Profile</span>
</a>

                    <!-- Settings -->
                    <a class="dropdown-item" href="{{ route('admin.settings') }}">
    <i class="bx bx-cog me-2"></i>
    <span class="align-middle">Settings</span>
</a>

                    <!-- Billing -->
                  <a class="dropdown-item" href="{{ route('admin.billing') }}">
    <i class="bx bx-credit-card me-2"></i>
    <span class="align-middle">Billing</span>
</a>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <!-- Logout -->
                   <!-- Logout -->
<li>
    @if(Route::has('admin.logout'))
        <a
            class="dropdown-item"
            href="{{ route('admin.logout') }}"
            onclick="
                event.preventDefault();
                document.getElementById(
                    'admin-logout-form'
                ).submit();
            "
        >
            <i class="bx bx-power-off me-2"></i>
            <span class="align-middle">Log Out</span>
        </a>

        <form
            id="admin-logout-form"
            action="{{ route('admin.logout') }}"
            method="POST"
            class="d-none"
        >
            @csrf
        </form>
    @else
        <a class="dropdown-item" href="{{ url('/') }}">
            <i class="bx bx-home me-2"></i>
            <span class="align-middle">
                Return to Website
            </span>
        </a>
    @endif
</li>
            <!-- /User dropdown -->
        </ul>
    </div>
</nav>

<!-- GitHub button script -->
<script
    async
    defer
    src="https://buttons.github.io/buttons.js"
></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuButton = document.getElementById('mobileMenuToggle');
        const layoutMenu = document.getElementById('layout-menu');
        const searchInput = document.getElementById('adminNavbarSearch');

        if (menuButton) {
            menuButton.addEventListener('click', function () {
                document.documentElement.classList.toggle(
                    'layout-menu-expanded'
                );

                if (layoutMenu) {
                    layoutMenu.classList.toggle('show');
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();

                    const searchValue = this.value.trim();

                    if (searchValue !== '') {
                        console.log('Admin search:', searchValue);
                    }
                }
            });
        }
    });
</script>
