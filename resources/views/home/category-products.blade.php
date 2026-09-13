<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        {{ $category->name ?? 'Category' }} | Kaira
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            margin: 0;
            background-color: #ffffff;
            color: #111111;
            font-family: Arial, sans-serif;
        }

        /*
        |--------------------------------------------------------------------------
        | Page Header
        |--------------------------------------------------------------------------
        */

        .category-header {
            padding: 70px 0;
            background-color: #f7f7f7;
            text-align: center;
        }

        .category-label {
            display: block;
            margin-bottom: 15px;
            color: #888888;
            font-size: 13px;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        .category-title {
            margin: 0;
            font-family: Georgia, serif;
            font-size: 52px;
            font-weight: 400;
            text-transform: uppercase;
        }

        .category-description {
            max-width: 650px;
            margin: 20px auto 0;
            color: #777777;
            font-size: 17px;
            line-height: 1.8;
        }

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        .products-section {
            padding: 80px 0;
        }

        .product-card {
            display: block;
            height: 100%;
            color: #111111;
            text-decoration: none;
        }

        .product-image-wrapper {
            position: relative;
            overflow: hidden;
            background-color: #f4f4ff4;
        }

        .product-image {
            display: block;
            width: 100%;
            height: 420px;
            object-fit: cover;
            background-color: #f4f4f4;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-content {
            padding: 18px 2px 25px;
        }

        .product-category {
            margin-bottom: 8px;
            color: #999999;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .product-name {
            margin-bottom: 9px;
            font-family: Georgia, serif;
            font-size: 21px;
            line-height: 1.4;
        }

        .product-price {
            color: #555555;
            font-size: 17px;
        }

        .product-stock {
            margin-top: 8px;
            color: #777777;
            font-size: 13px;
        }

        /*
        |--------------------------------------------------------------------------
        | Empty Products
        |--------------------------------------------------------------------------
        */

        .empty-products {
            padding: 80px 20px;
            background-color: #f8f8f8;
            text-align: center;
        }

        .empty-products h3 {
            font-family: Georgia, serif;
            font-size: 38px;
            font-weight: 400;
            text-transform: uppercase;
        }

        .empty-products p {
            color: #777777;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            border: 1px solid #111111;
            color: #111111;
            text-decoration: none;
            text-transform: uppercase;
            transition: 0.3s;
        }

        .back-btn:hover {
            background-color: #111111;
            color: #ffffff;
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        .pagination {
            justify-content: center;
            margin-top: 40px;
        }

        .pagination .page-link {
            border-color: #dddddd;
            color: #111111;
        }

        .pagination .page-item.active .page-link {
            border-color: #111111;
            background-color: #111111;
            color: #ffffff;
        }

        /*
        |--------------------------------------------------------------------------
        | Responsive
        |--------------------------------------------------------------------------
        */

        @media (max-width: 991px) {

            .cart-link {
                margin-top: 15px;
            }

            .product-image {
                height: 380px;
            }

        }

        @media (max-width: 768px) {

            .category-header {
                padding: 50px 15px;
            }

            .category-title {
                font-size: 38px;
            }

            .products-section {
                padding: 55px 0;
            }

            .product-image {
                height: 350px;
            }

        }

    </style>

</head>

<body>

    @php
        /*
         * Supports:
         *
         * image.jpg
         * products/image.jpg
         * uploads/products/image.jpg
         * storage/products/image.jpg
         * public/products/image.jpg
         * External image URL
         */
        $resolveImage = function (
            $image,
            array $possibleFolders = []
        ) {
            $fallback = asset(
                'users/images/no-image.png'
            );

            if (empty($image)) {
                return $fallback;
            }

            $image = ltrim(
                str_replace('\\', '/', $image),
                '/'
            );

            if (
                \Illuminate\Support\Str::startsWith(
                    $image,
                    ['http://', 'https://']
                )
            ) {
                return $image;
            }

            if (
                \Illuminate\Support\Str::startsWith(
                    $image,
                    'public/'
                )
            ) {
                $image = substr(
                    $image,
                    strlen('public/')
                );
            }

            $possiblePaths = [
                $image,
                'storage/' . $image,
                'uploads/' . $image,
            ];

            foreach ($possibleFolders as $folder) {
                $possiblePaths[] =
                    trim($folder, '/') .
                    '/' .
                    basename($image);
            }

            foreach (array_unique($possiblePaths) as $path) {
                if (file_exists(public_path($path))) {
                    return asset($path);
                }
            }

            return $fallback;
        };
    @endphp


    {{-- Complete home-page navbar is embedded below. --}}
<style>

    /*
    |--------------------------------------------------------------------------
    | Kaira Professional Navbar
    |--------------------------------------------------------------------------
    */

    .kaira-header {
        position: relative;
        z-index: 1050;
        width: 100%;
        padding: 14px 24px 10px;
        background: rgba(248, 248, 246, .96);
    }

    .kaira-navbar {
        width: 100%;
        max-width: 1380px;
        min-height: 72px;
        margin: 0 auto;
        padding: 9px 24px;
        border: 1px solid rgba(205, 200, 191, .85);
        border-radius: 24px;
        background: linear-gradient(135deg, #f0eee9 0%, #e8e5df 100%);
        box-shadow: 0 8px 24px rgba(40, 38, 34, .08);
        transition: min-height 0.25s ease, padding 0.25s ease,
            border-radius 0.25s ease, box-shadow 0.25s ease;
    }

    .kaira-navbar.is-sticky {
        position: fixed;
        top: 8px;
        right: auto;
        left: 50%;
        width: calc(100% - 48px);
        max-width: 1380px;
        min-height: 66px;
        margin: 0;
        transform: translateX(-50%);
        padding: 7px 24px;
        border-radius: 22px;
        animation: navbarSlideDown 0.3s ease;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
    }

    @keyframes navbarSlideDown {

        from {
            opacity: 0;
            transform: translate(-50%, -100%);
        }

        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Brand
    |--------------------------------------------------------------------------
    */

    .kaira-brand {
        display: inline-flex;
        align-items: center;
        color: #171717;
        font-family: Georgia, "Times New Roman", serif;
        font-size: 27px;
        font-weight: 700;
        letter-spacing: 4.5px;
        line-height: 1;
        text-decoration: none;
        text-transform: uppercase;
    }

    .kaira-brand:hover {
        color: #171717;
    }

    .kaira-brand-dot {
        margin-left: 2px;
        color: #cf2e3b;
        font-size: 27px;
    }

    /*
    |--------------------------------------------------------------------------
    | Navigation Links
    |--------------------------------------------------------------------------
    */

    .kaira-navigation {
        gap: 28px;
    }

    .kaira-navigation .nav-link {
        position: relative;
        padding: 9px 0 !important;
        color: #353535;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .kaira-navigation .nav-link:hover,
    .kaira-navigation .nav-link.active {
        color: #111111;
    }

    .kaira-navigation .nav-link::after {
        position: absolute;
        right: 0;
        bottom: 1px;
        left: 0;
        width: 0;
        height: 2px;
        margin: auto;
        background-color: #cf2e3b;
        content: "";
        transition: width 0.25s ease;
    }

    .kaira-navigation .nav-link:hover::after,
    .kaira-navigation .nav-link.active::after {
        width: 100%;
    }

    .kaira-navbar .dropdown-menu {
        margin-top: 10px !important;
        border: 1px solid rgba(216, 213, 207, .9) !important;
        background: rgba(255, 255, 255, .98);
        box-shadow: 0 16px 35px rgba(30, 29, 26, .12);
    }

    .kaira-navbar .dropdown-item {
        color: #46433e;
        font-size: 14px;
        font-weight: 500;
        transition: background-color .2s ease, color .2s ease, padding-left .2s ease;
    }

    .kaira-navbar .dropdown-item:hover,
    .kaira-navbar .dropdown-item.active {
        padding-left: 18px !important;
        background: #f5eeee;
        color: #cf2e3b;
    }

    /*
    |--------------------------------------------------------------------------
    | Navbar Buttons
    |--------------------------------------------------------------------------
    */

    .kaira-navbar-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kaira-icon-button {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        padding: 0;
        border: 0;
        border-radius: 50%;
        border: 1px solid #cbc7bf;
        background-color: rgba(255, 255, 255, 0.55);
        color: #222222;
        text-decoration: none;
        cursor: pointer;
        transition:
            color 0.25s ease,
            background-color 0.25s ease,
            transform 0.25s ease;
    }

    .kaira-icon-button:hover {
        background-color: #ffffff;
        color: #cf2e3b;
        transform: translateY(-2px);
    }

    .kaira-icon-button svg {
        width: 25px;
        height: 25px;
        stroke: currentColor;
    }

    .kaira-cart-count {
        position: absolute;
        top: -2px;
        right: -2px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 21px;
        height: 21px;
        padding: 0 5px;
        border: 2px solid #e9e7e2;
        border-radius: 50px;
        background-color: #cf2e3b;
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile Toggle
    |--------------------------------------------------------------------------
    */

    .kaira-navbar-toggler {
        display: flex;
        flex-direction: column;
        gap: 5px;
        padding: 10px;
        border: 1px solid #cbc7bf;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.55);
        box-shadow: none !important;
    }

    .kaira-navbar-toggler span {
        display: block;
        width: 25px;
        height: 2px;
        background-color: #222222;
        transition: 0.25s ease;
    }

    /*
    |--------------------------------------------------------------------------
    | Search Panel
    |--------------------------------------------------------------------------
    */

    .kaira-search-panel {
        position: absolute;
        top: 100%;
        right: 0;
        left: 0;
        display: none;
        padding: 25px 0;
        border-bottom: 1px solid #ededed;
        background-color: #ffffff;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
    }

    .kaira-search-panel.show {
        display: block;
        animation: searchPanelOpen 0.25s ease;
    }

    @keyframes searchPanelOpen {

        from {
            opacity: 0;
            transform: translateY(-12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }

    }

    .kaira-search-form {
        position: relative;
        max-width: 850px;
        margin: auto;
    }

    .kaira-search-input {
        width: 100%;
        height: 58px;
        padding: 0 150px 0 55px;
        border: 1px solid #d7d7d7;
        border-radius: 50px;
        background-color: #ffffff;
        color: #222222;
        font-size: 16px;
        outline: none;
        transition:
            border-color 0.25s ease,
            box-shadow 0.25s ease;
    }

    .kaira-search-input:focus {
        border-color: #222222;
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
    }

    .kaira-search-input::placeholder {
        color: #999999;
    }

    .kaira-search-icon {
        position: absolute;
        top: 50%;
        left: 20px;
        width: 22px;
        height: 22px;
        pointer-events: none;
        stroke: #777777;
        transform: translateY(-50%);
    }

    .kaira-search-submit {
        position: absolute;
        top: 6px;
        right: 6px;
        height: 46px;
        padding: 0 30px;
        border: 0;
        border-radius: 50px;
        background-color: #222222;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.25s ease;
    }

    .kaira-search-submit:hover {
        background-color: #cf2e3b;
    }

    .kaira-search-close {
        position: absolute;
        top: 50%;
        right: -55px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        padding: 0;
        border: 0;
        border-radius: 50%;
        background-color: #f1f1f1;
        color: #222222;
        font-size: 25px;
        cursor: pointer;
        transform: translateY(-50%);
        transition: 0.25s ease;
    }

    .kaira-search-close:hover {
        background-color: #222222;
        color: #ffffff;
    }

    /*
    |--------------------------------------------------------------------------
    | Search Overlay
    |--------------------------------------------------------------------------
    */

    .kaira-search-overlay {
        position: fixed;
        z-index: 1040;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        display: none;
        background-color: rgba(0, 0, 0, 0.35);
        backdrop-filter: blur(2px);
    }

    .kaira-search-overlay.show {
        display: block;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1199px) {

        .kaira-navigation {
            gap: 20px;
        }

    }

    @media (max-width: 991px) {

        .kaira-header {
            padding: 10px 12px 8px;
        }

        .kaira-navbar {
            min-height: 64px;
            padding: 7px 12px;
            border-radius: 20px;
        }

        .kaira-navbar.is-sticky {
            top: 8px;
            right: auto;
            left: 50%;
            width: calc(100% - 24px);
            padding: 7px 12px;
            border-radius: 18px;
        }

        .kaira-brand {
            font-size: 24px;
        }

        .kaira-navbar .navbar-collapse {
            margin-top: 10px;
            padding: 14px 16px 16px;
            border-top: 1px solid #d3d0ca;
            border-radius: 0 0 18px 18px;
            background-color: #e9e7e2;
        }

        .kaira-navigation {
            gap: 0;
        }

        .kaira-navigation .nav-link {
            display: inline-block;
            margin-bottom: 7px;
        }

        .kaira-navbar-actions {
            margin-top: 12px;
        }

        .kaira-search-panel {
            position: fixed;
            top: 68px;
            padding: 20px 15px;
        }

        .kaira-search-close {
            display: none;
        }

    }

    @media (max-width: 576px) {

        .kaira-brand {
            font-size: 22px;
            letter-spacing: 3px;
        }

        .kaira-search-input {
            height: 54px;
            padding-right: 105px;
            padding-left: 47px;
            font-size: 13px;
        }

        .kaira-search-icon {
            left: 16px;
            width: 20px;
            height: 20px;
        }

        .kaira-search-submit {
            top: 5px;
            right: 5px;
            height: 44px;
            padding: 0 18px;
            font-size: 13px;
        }

    }

</style>


@php
    /*
     * Calculate cart item quantity.
     */
    $kairaCart = session('cart', []);
    $kairaCartCount = 0;

    if (is_array($kairaCart)) {
        foreach ($kairaCart as $cartItem) {
            $kairaCartCount += isset($cartItem['quantity'])
                ? (int) $cartItem['quantity']
                : 1;
        }
    }
@endphp


<header
    class="kaira-header"
    id="kairaHeader"
>

    <nav
        class="navbar navbar-expand-lg kaira-navbar"
        id="kairaNavbar"
    >

        <div class="container">

            {{-- Brand --}}
            <a
                href="{{ route('home') }}"
                class="kaira-brand"
            >
                Kaira<span class="kaira-brand-dot">.</span>
            </a>


            {{-- Mobile Toggle --}}
            <button
                class="navbar-toggler kaira-navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#kairaNavbarMenu"
                aria-controls="kairaNavbarMenu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>


            <div
                class="collapse navbar-collapse"
                id="kairaNavbarMenu"
            >

                <ul class="navbar-nav kaira-navigation mx-auto">

                    <li class="nav-item">

                        <a
                            href="{{ route('home') }}"
                            class="nav-link {{
                                request()->routeIs('home')
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Home
                        </a>

                    </li>

                   <li class="nav-item dropdown">

    <a
        href="#"
        class="nav-link dropdown-toggle {{
            request()->routeIs('productss', 'product.details')
                ? 'active'
                : ''
        }}"
        id="shopDropdown"
        role="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        Shop
    </a>

    <ul
        class="dropdown-menu border-0 rounded-4 p-2"
        aria-labelledby="shopDropdown"
        style="min-width: 230px;"
    >
        {{-- Shop for Men --}}
        <li>
            <a
                href="{{ route('productss', [
                    'category' => 'SHOP FOR MEN'
                ]) }}"
                class="dropdown-item py-2 px-3 rounded-3 {{
                    request('category') === 'SHOP FOR MEN'
                        ? 'active'
                        : ''
                }}"
            >
                <i class="bi bi-person me-2"></i>
                Shop for Men
            </a>
        </li>

        {{-- Shop for Women --}}
        <li>
            <a
                href="{{ route('productss', [
                    'category' => 'SHOP FOR WOMEN'
                ]) }}"
                class="dropdown-item py-2 px-3 rounded-3 {{
                    request('category') === 'SHOP FOR WOMEN'
                        ? 'active'
                        : ''
                }}"
            >
                <i class="bi bi-person-heart me-2"></i>
                Shop for Women
            </a>
        </li>

        {{-- Accessories --}}
        <li>
            <a
                href="{{ route('productss', [
                    'category' => 'ACCESSORIES'
                ]) }}"
                class="dropdown-item py-2 px-3 rounded-3 {{
                    request('category') === 'ACCESSORIES'
                        ? 'active'
                        : ''
                }}"
            >
                <i class="bi bi-gem me-2"></i>
                Accessories
            </a>
        </li>
    </ul>

</li>
                    <li class="nav-item">

                        <a
                            href="{{ route('collections') }}"
                            class="nav-link {{
                                request()->routeIs(
                                    'collections',
                                    'collection.details',
                                    'collection-pro.details'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Collections
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('productss') }}"
                            class="nav-link {{
                                request()->routeIs(
                                    'productss',
                                    'product.details'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Products
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('home.blogs') }}"
                            class="nav-link {{
                                request()->routeIs(
                                    'home.blogs',
                                    'home.blog.details'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Blogs
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('home.contact') }}"
                            class="nav-link {{
                                request()->routeIs(
                                    'home.contact'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Contact
                        </a>

                    </li>

                </ul>


                {{-- Actions --}}
                <div class="kaira-navbar-actions">

                    {{-- Search --}}
                    <button
                        type="button"
                        class="kaira-icon-button"
                        id="kairaSearchOpen"
                        aria-label="Open search"
                        aria-expanded="false"
                        title="Search"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle
                                cx="11"
                                cy="11"
                                r="7"
                            ></circle>

                            <path
                                d="M20 20L16.5 16.5"
                            ></path>
                        </svg>
                    </button>


                    {{-- Cart --}}
                    <a
                        href="{{ route('cart') }}"
                        class="kaira-icon-button"
                        aria-label="Open shopping cart"
                        title="Cart"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M6 8H18L19 21H5L6 8Z"
                            ></path>

                            <path
                                d="M9 8V6C9 4.34 10.34 3 12 3C13.66 3 15 4.34 15 6V8"
                            ></path>
                        </svg>

                        @if($kairaCartCount > 0)

                            <span class="kaira-cart-count">
                                {{ $kairaCartCount }}
                            </span>

                        @endif

                    </a>

                </div>

            </div>

        </div>

    </nav>


    {{-- Expandable Search Panel --}}
    <div
        class="kaira-search-panel"
        id="kairaSearchPanel"
        aria-hidden="true"
    >

        <div class="container">

            <form
                action="{{ route('search') }}"
                method="GET"
                class="kaira-search-form"
                role="search"
            >

                <svg
                    class="kaira-search-icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle
                        cx="11"
                        cy="11"
                        r="7"
                    ></circle>

                    <path
                        d="M20 20L16.5 16.5"
                    ></path>
                </svg>

                <input
                    type="search"
                    name="search"
                    id="kairaSearchInput"
                    value="{{ request('search') }}"
                    class="kaira-search-input"
                    placeholder="Search products, collections, categories or blogs..."
                    autocomplete="off"
                    required
                >

                <button
                    type="submit"
                    class="kaira-search-submit"
                >
                    Search
                </button>

                <button
                    type="button"
                    class="kaira-search-close"
                    id="kairaSearchClose"
                    aria-label="Close search"
                >
                    &times;
                </button>

            </form>

        </div>

    </div>

</header>


{{-- Background overlay --}}
<div
    class="kaira-search-overlay"
    id="kairaSearchOverlay"
></div>


<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const header =
                document.getElementById('kairaHeader');

            const navbar =
                document.getElementById('kairaNavbar');

            const searchOpenButton =
                document.getElementById('kairaSearchOpen');

            const searchCloseButton =
                document.getElementById('kairaSearchClose');

            const searchPanel =
                document.getElementById('kairaSearchPanel');

            const searchOverlay =
                document.getElementById('kairaSearchOverlay');

            const searchInput =
                document.getElementById('kairaSearchInput');

            const navbarMenu =
                document.getElementById('kairaNavbarMenu');


            /*
            |--------------------------------------------------------------------------
            | Open Search
            |--------------------------------------------------------------------------
            */

            function openSearchPanel() {

                if (!searchPanel) {
                    return;
                }

                searchPanel.classList.add('show');
                searchOverlay.classList.add('show');

                searchPanel.setAttribute(
                    'aria-hidden',
                    'false'
                );

                searchOpenButton.setAttribute(
                    'aria-expanded',
                    'true'
                );

                setTimeout(function () {

                    if (searchInput) {
                        searchInput.focus();
                    }

                }, 150);

            }


            /*
            |--------------------------------------------------------------------------
            | Close Search
            |--------------------------------------------------------------------------
            */

            function closeSearchPanel() {

                if (!searchPanel) {
                    return;
                }

                searchPanel.classList.remove('show');
                searchOverlay.classList.remove('show');

                searchPanel.setAttribute(
                    'aria-hidden',
                    'true'
                );

                searchOpenButton.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Toggle Search
            |--------------------------------------------------------------------------
            */

            if (searchOpenButton) {

                searchOpenButton.addEventListener(
                    'click',
                    function (event) {

                        event.stopPropagation();

                        if (
                            searchPanel.classList.contains('show')
                        ) {
                            closeSearchPanel();
                        } else {
                            openSearchPanel();
                        }

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Close Button
            |--------------------------------------------------------------------------
            */

            if (searchCloseButton) {

                searchCloseButton.addEventListener(
                    'click',
                    closeSearchPanel
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Overlay Click
            |--------------------------------------------------------------------------
            */

            if (searchOverlay) {

                searchOverlay.addEventListener(
                    'click',
                    closeSearchPanel
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Escape Key
            |--------------------------------------------------------------------------
            */

            document.addEventListener(
                'keydown',
                function (event) {

                    if (
                        event.key === 'Escape' &&
                        searchPanel.classList.contains('show')
                    ) {
                        closeSearchPanel();
                        searchOpenButton.focus();
                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Sticky Navbar
            |--------------------------------------------------------------------------
            */

            function updateStickyNavbar() {

                if (!navbar || !header) {
                    return;
                }

                if (window.scrollY > 140) {

                    if (
                        !navbar.classList.contains('is-sticky')
                    ) {
                        header.style.minHeight =
                            navbar.offsetHeight + 'px';

                        navbar.classList.add('is-sticky');
                    }

                } else {

                    navbar.classList.remove('is-sticky');
                    header.style.minHeight = '';

                }

            }

            window.addEventListener(
                'scroll',
                updateStickyNavbar,
                { passive: true }
            );

            updateStickyNavbar();


            /*
            |--------------------------------------------------------------------------
            | Close Mobile Menu After Clicking Link
            |--------------------------------------------------------------------------
            */

            if (navbarMenu) {

                const menuLinks =
                    navbarMenu.querySelectorAll('.nav-link');

                menuLinks.forEach(function (menuLink) {

                    menuLink.addEventListener(
                        'click',
                        function () {

                            if (
                                window.innerWidth < 992 &&
                                !menuLink.classList.contains('dropdown-toggle') &&
                                window.bootstrap
                            ) {
                                const collapseInstance =
                                    bootstrap.Collapse.getOrCreateInstance(
                                        navbarMenu
                                    );

                                collapseInstance.hide();
                            }

                        }
                    );

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Close Search On Window Resize
            |--------------------------------------------------------------------------
            */

            window.addEventListener(
                'resize',
                function () {

                    if (
                        window.innerWidth < 400 &&
                        searchPanel.classList.contains('show')
                    ) {
                        closeSearchPanel();
                    }

                }
            );

        }
    );

</script>


    {{-- ====================================================== --}}
    {{-- CATEGORY HEADER --}}
    {{-- ====================================================== --}}

    <section class="category-header">

        <div class="container">

            <span class="category-label">
                Shop Collection
            </span>

            <h1 class="category-title">
                {{ $category->name }}
            </h1>

            @if(!empty($category->description))

                <p class="category-description">
                    {{ $category->description }}
                </p>

            @endif

        </div>

    </section>


    {{-- ====================================================== --}}
    {{-- PRODUCTS --}}
    {{-- ====================================================== --}}

    <section class="products-section">

        <div class="container">

            <div class="row g-4">

                @forelse($products as $product)

                    @php
                        $firstImage = $product->images->first();

                        /*
                         * Some product tables also have an
                         * image column, so use it as fallback.
                         */
                        $storedProductImage =
                            optional($firstImage)->image
                            ?? $product->image
                            ?? null;

                        $productImageUrl = $resolveImage(
                            $storedProductImage,
                            [
                                'uploads/products',
                                'storage/products',
                            ]
                        );
                    @endphp

                    <div class="col-lg-3 col-md-4 col-sm-6">

                        <a
                            href="{{ route(
                                'product.details',
                                ['identifier' => $product->uuid ?? $product->id]
                            ) }}"
                            class="product-card"
                        >

                            <div class="product-image-wrapper">

                                <img
                                    src="{{ $productImageUrl }}"
                                    alt="{{ $product->name }}"
                                    class="product-image"
                                    onerror="this.onerror=null; this.src='{{ asset('users/images/no-image.png') }}';"
                                >

                            </div>

                            <div class="product-content">

                                <div class="product-category">
                                    {{ $category->name }}
                                </div>

                                <div class="product-name">
                                    {{ $product->name }}
                                </div>

                                <div class="product-price">
                                    Rs. {{ number_format($product->price, 2) }}
                                </div>

                                @if(isset($product->stock))

                                    <div class="product-stock">
                                        @if($product->stock > 0)
                                            In Stock
                                        @else
                                            Out of Stock
                                        @endif
                                    </div>

                                @endif

                            </div>

                        </a>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="empty-products">

                            <h3>
                                Products Coming Soon
                            </h3>

                            <p>
                                New products will be available in this
                                category soon.
                            </p>

                            <a
                                href="{{ route('home') }}"
                                class="back-btn"
                            >
                                Back to Home
                            </a>

                        </div>

                    </div>

                @endforelse

            </div>

            @if($products->hasPages())

                <div class="mt-4">
                    {{ $products->links() }}
                </div>

            @endif

        </div>

    </section>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>

</html>
