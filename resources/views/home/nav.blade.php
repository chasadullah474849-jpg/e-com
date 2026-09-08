<header
    class="navbar navbar-expand-lg navbar-light fixed-top professional-kaira-nav"
    id="kairaHeader"
>
    <div class="container-fluid px-4 py-2 align-items-center">

        <!-- Brand Logo -->
        <a
            class="navbar-brand text-uppercase brand-title m-0"
            href="{{ route('home') }}"
        >
            Kaira<span class="text-danger">.</span>
        </a>

        <!-- Mobile Toggle -->
        <button
            class="navbar-toggler border-0 shadow-none ms-auto me-2"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#kairaNavbar"
            aria-controls="kairaNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div
            class="collapse navbar-collapse justify-content-center"
            id="kairaNavbar"
        >
            <ul class="navbar-nav mb-2 mb-lg-0 gap-lg-4">

                <!-- Home -->
                <li class="nav-item">
                    <a
                        class="nav-link nav-item-link
                        {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}"
                    >
                        Home
                    </a>
                </li>

                <!-- Shop Dropdown -->
                <li class="nav-item dropdown shop-dropdown">
                    <a
                        class="nav-link nav-item-link dropdown-toggle
                        {{ request()->routeIs('productss') ? 'active' : '' }}"
                        href="#"
                        id="shopDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        Shop
                    </a>

                    <ul
                        class="dropdown-menu border-0 rounded-4 p-2 animate-dropdown"
                        aria-labelledby="shopDropdown"
                    >
                        <li>
                            <a
                                class="dropdown-item py-2 px-3 rounded-3 fw-medium"
                                href="{{ route('productss', [
                                    'category' => 'SHOP FOR MEN'
                                ]) }}"
                            >
                                <i class="bi bi-person me-2 text-muted"></i>
                                Shop for Men
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item py-2 px-3 rounded-3 fw-medium"
                                href="{{ route('productss', [
                                    'category' => 'SHOP FOR WOMEN'
                                ]) }}"
                            >
                                <i class="bi bi-person-heart me-2 text-muted"></i>
                                Shop for Women
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item py-2 px-3 rounded-3 fw-medium"
                                href="{{ route('productss', [
                                    'category' => 'ACCESSORIES'
                                ]) }}"
                            >
                                <i class="bi bi-gem me-2 text-muted"></i>
                                Accessories
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Collections -->
                <li class="nav-item">
                    <a
                        class="nav-link nav-item-link
                        {{ request()->routeIs(
                            'collections',
                            'collection.details',
                            'collection-pro.details'
                        ) ? 'active' : '' }}"
                        href="{{ route('collections') }}"
                    >
                        Collection
                    </a>
                </li>

                <!-- Products -->
                <li class="nav-item">
                    <a
                        class="nav-link nav-item-link
                        {{ request()->routeIs(
                            'productss',
                            'product.details',
                            'shop.product'
                        ) ? 'active' : '' }}"
                        href="{{ route('productss') }}"
                    >
                        Products
                    </a>
                </li>

                <!-- Contact -->
                <li class="nav-item">
                    <a
                        class="nav-link nav-item-link
                        {{ request()->routeIs('home.contact') ? 'active' : '' }}"
                        href="{{ route('home.contact') }}"
                    >
                        Contact
                    </a>
                </li>

            </ul>
        </div>

        <!-- Right Icons -->
        <div class="d-flex align-items-center gap-3 ms-lg-auto">

            <!-- Search Icon -->
            <button
                type="button"
                class="btn p-0 border-0 bg-transparent nav-action-icon shadow-none"
                data-bs-toggle="collapse"
                data-bs-target="#inlineSearchBar"
                aria-expanded="false"
                aria-controls="inlineSearchBar"
                title="Search"
            >
                <i class="bi bi-search fs-5"></i>
            </button>

            <!-- Cart Icon -->
            <a
                href="{{ route('cart') }}"
                class="nav-action-icon position-relative text-dark"
                title="Cart"
            >
                <i class="bi bi-bag fs-5"></i>

                @php
                    $cartCount = 0;
                    $cartItems = session('cart', []);

                    foreach ($cartItems as $cartItem) {
                        $cartCount += (int) ($cartItem['quantity'] ?? 0);
                    }
                @endphp

                <span
                    id="cartCount"
                    class="position-absolute top-0 start-100 translate-middle
                           badge rounded-pill bg-danger"
                    style="
                        font-size: 9px;
                        min-width: 17px;
                        height: 17px;
                        display: {{ $cartCount > 0 ? 'inline-flex' : 'none' }};
                        align-items: center;
                        justify-content: center;
                    "
                >
                    {{ $cartCount }}
                </span>
            </a>

        </div>
    </div>

    <!-- Search Bar -->
    <div
        class="collapse w-100 border-top shadow-sm py-3 px-3
               position-absolute start-0 top-100 rounded-bottom-4"
        id="inlineSearchBar"
    >
        <div class="container">
            <form action="{{ route('productss') }}" method="GET">
                <div class="input-group">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control rounded-start-pill border-end-0
                               shadow-none ps-4 py-2"
                        placeholder="Search products..."
                        autocomplete="off"
                        required
                    >

                    <button
                        class="btn btn-dark px-4 rounded-end-pill"
                        type="submit"
                    >
                        <i class="bi bi-search me-1"></i>
                        Search
                    </button>

                </div>
            </form>
        </div>
    </div>
</header>

<style>
    .professional-kaira-nav {
        width: calc(100% - 24px) !important;
        max-width: none !important;
        margin: 12px auto 0 !important;
        left: 0;
        right: 0;
        border-radius: 40px !important;
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 1050;
    }

    .professional-kaira-nav.scrolled {
        width: calc(100% - 24px) !important;
        margin-top: 8px !important;
        background: rgba(255, 255, 255, 0.90) !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .brand-title {
        font-family: "Playfair Display", serif;
        letter-spacing: 1.5px;
        color: #1a1a1a !important;
        font-size: 1.8rem !important;
        font-weight: 700;
    }

    .nav-item-link {
        color: #2c2c2c !important;
        font-size: 14.5px;
        font-weight: 600;
        letter-spacing: 0.3px;
        position: relative;
        transition: color 0.3s ease;
        padding-bottom: 2px;
    }

    .nav-item-link::after {
        content: "";
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -2px;
        left: 0;
        background-color: #2c2c2c;
        transition: width 0.3s ease-in-out;
    }

    .nav-item-link:hover::after,
    .nav-item-link.active::after {
        width: 100%;
    }

    .nav-item-link:hover,
    .nav-item-link.active {
        color: #000 !important;
    }

    .dropdown-menu {
        min-width: 220px;
        margin-top: 15px !important;
        background: rgba(253, 252, 249, 0.96);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(200, 190, 175, 0.3) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08) !important;
        border-radius: 20px !important;
    }

    .dropdown-item {
        font-size: 13.5px;
        color: #333;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: rgba(230, 222, 210, 0.5);
        color: #000;
        transform: translateX(4px);
        border-radius: 10px;
    }

    .nav-action-icon {
        color: #2c2c2c;
        transition: all 0.25s ease-in-out;
        text-decoration: none;
    }

    .nav-action-icon:hover {
        color: #b83232;
        transform: translateY(-2px);
    }

    #inlineSearchBar {
        background: rgba(253, 252, 249, 0.97) !important;
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
    }

    body {
        padding-top: 90px;
    }

    @media (max-width: 991.98px) {
        .professional-kaira-nav {
            border-radius: 25px !important;
        }

        #kairaNavbar {
            padding: 15px 5px 5px;
        }

        .nav-item-link {
            padding: 10px 5px;
        }

        .nav-item-link::after {
            display: none;
        }

        .dropdown-menu {
            margin-top: 5px !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.getElementById('kairaHeader');

        function updateNavbar() {
            if (window.scrollY > 20) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }

        updateNavbar();

        window.addEventListener('scroll', updateNavbar);
    });
</script>
