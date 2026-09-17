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

    #layout-navbar .navbar-search {
        position: relative;
        width: 100%;
        max-width: 650px;
    }

    #layout-navbar .admin-search-field {
        position: relative;
        width: 100%;
        min-height: 46px;
        padding: 0 12px;
        border: 1px solid transparent;
        border-radius: 10px;
        transition: 0.2s ease;
    }

    #layout-navbar .admin-search-field:focus-within {
        border-color: rgba(105, 108, 255, 0.45);
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(105, 108, 255, 0.1);
    }

    #layout-navbar .admin-search-clear {
        display: none;
        width: 32px;
        height: 32px;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: 0;
        border-radius: 50%;
        background: transparent;
        color: #697a8d;
        font-size: 20px;
        cursor: pointer;
    }

    #layout-navbar .admin-search-clear.show {
        display: inline-flex;
    }

    #layout-navbar .admin-search-clear:hover {
        color: #696cff;
        background: rgba(105, 108, 255, 0.1);
    }

    #adminSearchResults {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        left: 0;
        z-index: 1090;
        display: none;
        max-height: 360px;
        overflow-y: auto;
        padding: 8px;
        border: 1px solid #e7e7eb;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 14px 40px rgba(67, 89, 113, 0.2);
    }

    #adminSearchResults.show {
        display: block;
    }

    #layout-navbar .admin-search-result {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 11px 13px;
        border-radius: 9px;
        color: #566a7f;
        text-decoration: none;
        transition: 0.15s ease;
    }

    #layout-navbar .admin-search-result:hover,
    #layout-navbar .admin-search-result.active {
        color: #696cff;
        background: rgba(105, 108, 255, 0.09);
    }

    #layout-navbar .admin-result-icon {
        display: inline-flex;
        width: 36px;
        height: 36px;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        color: #696cff;
        background: rgba(105, 108, 255, 0.12);
        font-size: 18px;
    }

    #layout-navbar .admin-result-content {
        min-width: 0;
        flex: 1;
    }

    #layout-navbar .admin-result-title {
        display: block;
        font-size: 14px;
        font-weight: 600;
    }

    #layout-navbar .admin-result-path {
        display: block;
        margin-top: 2px;
        overflow: hidden;
        color: #a1acb8;
        font-size: 11px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    #layout-navbar .admin-search-empty {
        padding: 24px 15px;
        color: #a1acb8;
        text-align: center;
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

        #adminSearchResults {
            position: fixed;
            top: 78px;
            right: 15px;
            left: 15px;
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
            <div class="nav-item d-flex align-items-center w-100 admin-search-field">
                <i class="bx bx-search fs-4 lh-0"></i>

                <input
                    type="search"
                    id="adminNavbarSearch"
                    class="form-control border-0 shadow-none"
                    placeholder="Search..."
                    aria-label="Search admin panel"
                    autocomplete="off"
                >

                <button
                    type="button"
                    id="clearAdminSearch"
                    class="admin-search-clear"
                    aria-label="Clear search"
                >
                    <i class="bx bx-x"></i>
                </button>
            </div>

            <div
                id="adminSearchResults"
                role="listbox"
                aria-label="Admin search results"
            ></div>
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

                    <li>
                        <a class="dropdown-item" href="{{ $profileUrl }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ $settingsUrl }}">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ $billingUrl }}">
                            <i class="bx bx-credit-card me-2"></i>
                            <span class="align-middle">Billing</span>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <li>
                        @if(Route::has('admin.logout'))
                            <a
                                class="dropdown-item"
                                href="{{ $logoutUrl }}"
                                onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();"
                            >
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>

                            <form
                                id="admin-logout-form"
                                action="{{ $logoutUrl }}"
                                method="POST"
                                class="d-none"
                            >
                                @csrf
                            </form>
                        @else
                            <a class="dropdown-item" href="{{ url('/') }}">
                                <i class="bx bx-home me-2"></i>
                                <span class="align-middle">Return to Website</span>
                            </a>
                        @endif
                    </li>
                </ul>
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
        const searchResults = document.getElementById('adminSearchResults');
        const clearSearch = document.getElementById('clearAdminSearch');
        let activeSearchIndex = -1;

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

        function escapeHtml(value) {
            const element = document.createElement('div');
            element.textContent = String(value || '');
            return element.innerHTML;
        }

        function getAdminPages() {
            const links = document.querySelectorAll(
                '#layout-menu a.menu-link[href], ' +
                '.menu-inner a.menu-link[href], ' +
                'aside a[href]'
            );

            const pages = Array.from(links).map(function (link) {
                const href = link.getAttribute('href');
                const label = link.querySelector(
                    '.menu-text, [data-i18n], span, div'
                );
                const icon = link.querySelector('i');

                return {
                    title: (
                        label?.textContent ||
                        link.textContent ||
                        ''
                    ).trim().replace(/\s+/g, ' '),
                    href: href,
                    icon: icon?.className || 'bx bx-link'
                };
            }).filter(function (page) {
                return page.title &&
                    page.href &&
                    page.href !== '#' &&
                    !page.href.toLowerCase().startsWith('javascript:');
            });

            return pages.filter(function (page, index, allPages) {
                return index === allPages.findIndex(function (item) {
                    return item.href === page.href;
                });
            });
        }

        function closeAdminSearch() {
            if (!searchResults) return;

            searchResults.classList.remove('show');
            searchResults.innerHTML = '';
            activeSearchIndex = -1;
        }

        function renderAdminSearch() {
            if (!searchInput || !searchResults) return;

            const term = searchInput.value.trim().toLowerCase();

            if (clearSearch) {
                clearSearch.classList.toggle(
                    'show',
                    searchInput.value.length > 0
                );
            }

            if (!term) {
                closeAdminSearch();
                return;
            }

            const matches = getAdminPages().filter(function (page) {
                return page.title.toLowerCase().includes(term);
            }).slice(0, 10);

            searchResults.innerHTML = '';
            activeSearchIndex = -1;

            if (!matches.length) {
                searchResults.innerHTML =
                    '<div class="admin-search-empty">' +
                        '<i class="bx bx-search-alt fs-2 d-block mb-2"></i>' +
                        'No admin page found for “' +
                        escapeHtml(searchInput.value.trim()) +
                        '”' +
                    '</div>';

                searchResults.classList.add('show');
                return;
            }

            matches.forEach(function (page) {
                const result = document.createElement('a');
                result.className = 'admin-search-result';
                result.href = page.href;
                result.setAttribute('role', 'option');
                result.innerHTML =
                    '<span class="admin-result-icon">' +
                        '<i class="' + escapeHtml(page.icon) + '"></i>' +
                    '</span>' +
                    '<span class="admin-result-content">' +
                        '<span class="admin-result-title">' +
                            escapeHtml(page.title) +
                        '</span>' +
                        '<span class="admin-result-path">' +
                            escapeHtml(page.href) +
                        '</span>' +
                    '</span>' +
                    '<i class="bx bx-right-arrow-alt"></i>';

                searchResults.appendChild(result);
            });

            searchResults.classList.add('show');
        }

        if (searchInput && searchResults) {
            searchInput.addEventListener('input', renderAdminSearch);

            searchInput.addEventListener('focus', function () {
                if (this.value.trim()) renderAdminSearch();
            });

            searchInput.addEventListener('keydown', function (event) {
                const results = Array.from(
                    searchResults.querySelectorAll('.admin-search-result')
                );

                if (event.key === 'ArrowDown' && results.length) {
                    event.preventDefault();
                    activeSearchIndex =
                        (activeSearchIndex + 1) % results.length;
                } else if (event.key === 'ArrowUp' && results.length) {
                    event.preventDefault();
                    activeSearchIndex =
                        (activeSearchIndex - 1 + results.length) %
                        results.length;
                } else if (event.key === 'Enter' && results.length) {
                    event.preventDefault();
                    const selected =
                        results[activeSearchIndex] || results[0];
                    window.location.href = selected.href;
                    return;
                } else if (event.key === 'Escape') {
                    closeAdminSearch();
                    this.blur();
                    return;
                } else {
                    return;
                }

                results.forEach(function (result, index) {
                    result.classList.toggle(
                        'active',
                        index === activeSearchIndex
                    );
                });

                results[activeSearchIndex]?.scrollIntoView({
                    block: 'nearest'
                });
            });
        }

        if (clearSearch) {
            clearSearch.addEventListener('click', function () {
                searchInput.value = '';
                this.classList.remove('show');
                closeAdminSearch();
                searchInput.focus();
            });
        }

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.navbar-search')) {
                closeAdminSearch();
            }
        });
    });
</script>
