<header class="navbar navbar-expand-lg navbar-light fixed-top professional-kaira-nav">
    <div class="container py-1">
        <!-- Brand Logo -->
        <a class="navbar-brand fw-bold fs-3 text-uppercase brand-title" href="{{ url('/') }}">
            Kaira<span class="text-danger">.</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#kairaNavbar" aria-controls="kairaNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="kairaNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4">
                <li class="nav-item">
                    <a class="nav-link fw-semibold nav-item-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                </li>

                <!-- SHOP DROPDOWN -->
                <li class="nav-item dropdown shop-dropdown">
                    <a class="nav-link fw-semibold nav-item-link dropdown-toggle" href="#" id="shopDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Shop
                    </a>
                    <ul class="dropdown-menu border-0 shadow-lg rounded-3 p-2 animate-dropdown" aria-labelledby="shopDropdown" style="min-width: 200px;">
                        <li>
                            <a class="dropdown-item py-2 px-3 rounded-2 fw-medium" href="{{ Route::has('products') ? route('products', ['category' => 'men']) : url('/products?category=men') }}">
                                Shop for Men
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 rounded-2 fw-medium" href="{{ Route::has('products') ? route('products', ['category' => 'women']) : url('/products?category=women') }}">
                                Shop for Women
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 rounded-2 fw-medium" href="{{ Route::has('products') ? route('products', ['category' => 'accessories']) : url('/products?category=accessories') }}">
                                Men Perfume
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Collection Link -->
                <li class="nav-item">
                    <a class="nav-link fw-semibold nav-item-link {{ request()->is('collections*') ? 'active' : '' }}" href="{{ Route::has('collections') ? route('collections') : url('/collections') }}">
                        Collection
                    </a>
                </li>

                <!-- Products Link -->
                <li class="nav-item">
                    <a class="nav-link fw-semibold nav-item-link {{ request()->is('products*') ? 'active' : '' }}" href="{{ Route::has('products') ? route('products') : url('/products') }}">
                        Products
                    </a>
                </li>

                <!-- Contact Link -->
                <li class="nav-item">
                    <a class="nav-link fw-semibold nav-item-link {{ request()->is('contact*') ? 'active' : '' }}" href="{{ Route::has('home.contact') ? route('home.contact') : url('/contact') }}">
                        Contact
                    </a>
                </li>
            </ul>

            <!-- Right Action Icons -->
            <div class="d-flex align-items-center gap-4 mt-3 mt-lg-0">
                <!-- Search Icon (Toggles Inline Search Bar) -->
                <button type="button" class="btn p-0 border-0 bg-transparent nav-action-icon fs-6 shadow-none" data-bs-toggle="collapse" data-bs-target="#inlineSearchBar" aria-expanded="false" aria-controls="inlineSearchBar" title="Search">
                    <i class="bi bi-search"></i>
                </button>

                <!-- Cart Icon -->
                <a href="{{ Route::has('cart') ? route('cart') : url('/cart') }}" class="nav-action-icon fs-6 position-relative" title="Cart">
                    <i class="bi bi-bag"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount" style="font-size: 8px; display:none;">
                        0
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Professional Inline Search Bar Dropdown -->
    <div class="collapse w-100 bg-white border-top shadow-sm py-3 px-3 position-absolute start-0 top-100" id="inlineSearchBar">
        <div class="container">
            <form action="{{ Route::has('products') ? route('products') : url('/products') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-start-pill border-end-0 shadow-none ps-4 py-2" placeholder="Search for soft leather jackets, products..." required autocomplete="off">
                    <button class="btn btn-dark px-4 rounded-end-pill" type="submit">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</header>

<!-- Professional Styling CSS -->
<style>
    .professional-kaira-nav {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.04) !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        transition: all 0.4s ease-in-out;
    }

    .brand-title {
        font-family: 'Playfair Display', serif, sans-serif;
        letter-spacing: 1.2px;
        color: #111111 !important;
        font-size: 1.5rem !important;
    }

    .nav-item-link {
        color: #333333 !important;
        font-size: 14px;
        letter-spacing: 0.2px;
        transition: color 0.3s ease;
    }

    .nav-item-link:hover,
    .nav-item-link.active {
        color: #000000 !important;
    }

    .dropdown-menu {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.04);
        margin-top: 8px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.06) !important;
    }

    .dropdown-item {
        font-size: 13px;
        color: #333;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #000;
        transform: translateX(3px);
    }

    .nav-action-icon {
        color: #333333;
        transition: all 0.2s ease-in-out;
    }

    .nav-action-icon:hover {
        color: #d9534f;
        transform: translateY(-2px);
    }

    body {
        padding-top: 60px;
    }
</style>
