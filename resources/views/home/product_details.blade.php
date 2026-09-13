<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}">

    <title>{{ $product->name }} | Kaira</title>


    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    {{-- jQuery --}}
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js">
    </script>


    {{-- Google Font --}}
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">


    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #151515;
            font-family: 'DM Sans', Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }


        /* =========================================================
           PRODUCT PAGE
        ========================================================= */

        .product-page {
            max-width: 1440px;
            margin: auto;
            padding: 28px 45px 70px;
        }


        /* =========================================================
           BREADCRUMB
        ========================================================= */

        .breadcrumb-area {
            display: flex;
            align-items: center;
            gap: 13px;

            color: #777777;
            font-size: 14px;

            margin-bottom: 27px;
        }

        .breadcrumb-area a {
            color: #666666;
        }

        .breadcrumb-area i {
            font-size: 12px;
            color: #aaaaaa;
        }

        .breadcrumb-current {
            color: #555555;
        }


        /* =========================================================
           PRODUCT LAYOUT
        ========================================================= */

        .product-layout {
            display: grid;
            grid-template-columns: 54% 46%;
            gap: 70px;
        }

        .gallery-area {
            min-width: 0;
        }


        /* =========================================================
           MAIN IMAGE
        ========================================================= */

        .main-image-container {
            position: relative;

            width: 100%;
            height: 515px;

            background: #f4f1ed;

            border-radius: 15px;

            overflow: hidden;
        }

        .main-product-image {
            width: 100%;
            height: 100%;

            object-fit: cover;

            display: block;

            transition: 0.3s ease;
        }


        /* =========================================================
           THUMBNAILS
        ========================================================= */

        .thumbnail-area {
            display: flex;
            align-items: center;

            gap: 15px;

            margin-top: 20px;
        }

        .thumbnails {
            display: flex;
            gap: 14px;

            overflow-x: auto;

            scrollbar-width: none;

            flex: 1;
        }

        .thumbnails::-webkit-scrollbar {
            display: none;
        }

        .thumbnail {
            width: 125px;
            height: 125px;

            flex-shrink: 0;

            object-fit: cover;

            border-radius: 11px;

            border: 2px solid transparent;

            cursor: pointer;

            transition: 0.25s;
        }

        .thumbnail:hover {
            border-color: #888888;
        }

        .thumbnail.active {
            border-color: #111111;
        }


        /* =========================================================
           PRODUCT INFORMATION
        ========================================================= */

        .product-information {
            padding-top: 2px;
            padding-right: 10px;
        }

        .collection-label {
            display: inline-block;

            padding: 7px 12px;

            border-radius: 7px;

            background: #fff1df;
            color: #71491d;

            font-size: 13px;
            font-weight: 600;

            text-transform: uppercase;

            margin-bottom: 15px;
        }

        .product-title {
            font-size: 44px;
            line-height: 1.12;

            font-weight: 700;

            letter-spacing: -1.5px;

            margin: 0 0 17px;

            color: #151515;
        }


        /* =========================================================
           RATING
        ========================================================= */

        .rating-area {
            display: flex;
            align-items: center;

            gap: 10px;

            margin-bottom: 22px;
        }

        .stars {
            color: #f4a900;
            font-size: 20px;
            letter-spacing: 1px;
        }

        .review-count {
            color: #777777;
            font-size: 14px;
        }


        /* =========================================================
           PRICE
        ========================================================= */

        .product-price-large {
            font-size: 32px;

            font-weight: 700;

            margin-bottom: 18px;

            color: #111111;
        }


        /* =========================================================
           DESCRIPTION
        ========================================================= */

        .short-description {
            color: #6d6d6d;

            font-size: 16px;

            line-height: 1.75;

            max-width: 620px;

            margin-bottom: 27px;
        }


        /* =========================================================
           STOCK
        ========================================================= */

        .stock-information {
            display: flex;
            align-items: center;

            gap: 16px;

            margin-bottom: 25px;
        }

        .stock-status {
            display: flex;
            align-items: center;

            gap: 9px;

            font-size: 16px;
            font-weight: 600;
        }

        .stock-status.in-stock {
            color: #1c8517;
        }

        .stock-status.out-stock {
            color: #df2735;
        }

        .stock-icon {
            width: 23px;
            height: 23px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            color: white;

            font-size: 13px;
        }

        .in-stock .stock-icon {
            background: #2c921f;
        }

        .out-stock .stock-icon {
            background: #df2735;
        }

        .stock-divider {
            width: 1px;
            height: 23px;

            background: #dddddd;
        }

        .stock-left {
            font-size: 16px;
            color: #444444;
        }

        .stock-left strong {
            color: #dc9200;
        }


        /* =========================================================
           QUANTITY
        ========================================================= */

        .quantity-title {
            display: block;

            font-size: 15px;

            font-weight: 500;

            margin-bottom: 10px;
        }

        .quantity-control {
            display: flex;

            width: 162px;
            height: 52px;

            border: 1px solid #dddddd;

            border-radius: 10px;

            overflow: hidden;

            margin-bottom: 25px;
        }

        .quantity-control button {
            width: 52px;

            border: none;

            background: #ffffff;

            font-size: 22px;

            cursor: pointer;

            transition: 0.2s;
        }

        .quantity-control button:hover {
            background: #f5f5f5;
        }

        .quantity-control input {
            width: 58px;

            border: none;

            border-left: 1px solid #eeeeee;
            border-right: 1px solid #eeeeee;

            outline: none;

            text-align: center;

            font-size: 16px;

            font-weight: 500;
        }

        .quantity-control input::-webkit-outer-spin-button,
        .quantity-control input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }


        /* =========================================================
           CART BUTTONS
        ========================================================= */

        .cart-actions {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 22px;

            margin-bottom: 27px;
        }

        .add-cart-button {
            height: 59px;

            border: none;

            border-radius: 11px;

            background: #151515;

            color: #ffffff;

            font-size: 16px;

            font-weight: 600;

            display: flex;
            align-items: center;
            justify-content: center;

            gap: 10px;

            cursor: pointer;

            transition: 0.3s;
        }

        .add-cart-button:hover {
            background: #333333;

            transform: translateY(-2px);
        }

        .add-cart-button:disabled {
            opacity: 0.7;

            cursor: wait;

            transform: none;
        }



        .disabled-cart {
            width: 100%;

            height: 59px;

            border: none;

            border-radius: 11px;

            background: #aaaaaa;

            color: white;

            font-size: 16px;

            font-weight: 600;

            cursor: not-allowed;

            margin-bottom: 27px;
        }


        /* =========================================================
           AJAX MESSAGES
        ========================================================= */

        #cartSuccessMessage,
        #cartErrorMessage {
            display: none;

            position: fixed;

            top: 100px;

            right: 30px;

            z-index: 99999;

            min-width: 330px;

            padding: 17px 20px;

            background: #ffffff;

            border-radius: 10px;

            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);

            color: #222222;

            font-size: 15px;

            font-weight: 600;
        }

        #cartSuccessMessage {
            border: 1px solid #d9ead7;

            border-left: 5px solid #2c921f;
        }

        #cartErrorMessage {
            border: 1px solid #f0cccc;

            border-left: 5px solid #dc3545;
        }

        #cartSuccessMessage .success-icon {
            width: 27px;
            height: 27px;

            border-radius: 50%;

            background: #2c921f;

            color: #ffffff;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            margin-right: 10px;
        }


        /* =========================================================
           BENEFITS
        ========================================================= */

        .benefits {
            border-top: 1px solid #eeeeee;

            border-bottom: 1px solid #eeeeee;

            padding: 22px 0;

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 20px;

            margin-bottom: 25px;
        }

        .benefit {
            display: flex;

            align-items: flex-start;

            gap: 10px;
        }

        .benefit-icon {
            font-size: 22px;

            color: #222222;

            flex-shrink: 0;
        }

        .benefit-title {
            display: block;

            font-size: 14px;

            font-weight: 600;

            margin-bottom: 4px;
        }

        .benefit-text {
            display: block;

            color: #777777;

            font-size: 12px;

            line-height: 1.4;
        }


        /* =========================================================
           DESCRIPTION SECTION
        ========================================================= */

        .description-section {
            margin-top: 55px;

            max-width: 750px;
        }

        .tabs {
            display: flex;

            gap: 55px;

            border-bottom: 1px solid #dddddd;

            margin-bottom: 24px;
        }

        .tab {
            position: relative;

            padding-bottom: 13px;

            color: #333333;

            font-size: 15px;

            cursor: pointer;
        }

        .tab.active {
            font-weight: 600;
        }

        .tab.active::after {
            content: "";

            position: absolute;

            bottom: -1px;

            left: 0;

            width: 100%;

            height: 3px;

            background: #111111;
        }

        .description-text {
            color: #6d6d6d;

            font-size: 15px;

            line-height: 1.8;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 991px) {

            .product-layout {
                grid-template-columns: 1fr;

                gap: 45px;
            }

        }


        @media (max-width: 767px) {

            .product-page {
                padding: 25px 20px 50px;
            }

            .main-image-container {
                height: 400px;
            }

            .product-title {
                font-size: 34px;
            }

            .cart-actions {
                grid-template-columns: 1fr;
            }

            .benefits {
                grid-template-columns: 1fr;
            }

            #cartSuccessMessage,
            #cartErrorMessage {
                left: 20px;

                right: 20px;

                min-width: auto;
            }

        }

    </style>

</head>


<body>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================= --}}

    <div id="cartSuccessMessage">

        <span class="success-icon">
            <i class="bi bi-check-lg"></i>
        </span>

        <span id="cartSuccessText">
            Product added to cart successfully.
        </span>

    </div>


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================= --}}

    <div id="cartErrorMessage">

        <span style="color:#dc3545; margin-right:10px;">
            <i class="bi bi-x-circle-fill"></i>
        </span>

        <span id="cartErrorText">
            Something went wrong.
        </span>

    </div>
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

                            <span
                                class="kaira-cart-count"
                                id="cartCount"
                            >
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
    {{-- =========================================================
         PRODUCT PAGE
    ========================================================= --}}

    <main class="product-page">


        {{-- BREADCRUMB --}}

        <div class="breadcrumb-area">

            <a href="{{ url('/') }}">
                Home
            </a>

            <i class="bi bi-chevron-right"></i>

            <a href="{{ route('productss') }}">
                Shop
            </a>

            <i class="bi bi-chevron-right"></i>

            <span class="breadcrumb-current">
                {{ $product->name }}
            </span>

        </div>


        <div class="product-layout">


            {{-- =====================================================
                 LEFT SIDE
            ====================================================== --}}

            <div class="gallery-area">


                {{-- MAIN IMAGE --}}

                <div class="main-image-container">

                    @if($product->images->count() > 0)

                        <img
                            id="mainProductImage"
                            src="{{ asset('uploads/products/' . $product->images->first()->image) }}"
                            alt="{{ $product->name }}"
                            class="main-product-image">

                    @else

                        <img
                            id="mainProductImage"
                            src="{{ asset('images/no-image.png') }}"
                            alt="No Image"
                            class="main-product-image">

                    @endif

                </div>


                {{-- THUMBNAILS --}}

                @if($product->images->count() > 1)

                    <div class="thumbnail-area">

                        <div
                            class="thumbnails"
                            id="thumbnailContainer">

                            @foreach($product->images as $key => $image)

                                <img
                                    src="{{ asset('uploads/products/' . $image->image) }}"
                                    alt="{{ $product->name }}"
                                    class="thumbnail {{ $key === 0 ? 'active' : '' }}"
                                    onclick="changeMainImage(this)">

                            @endforeach

                        </div>

                    </div>

                @endif


                {{-- DESCRIPTION --}}

                <div class="description-section">

                    <div class="tabs">

                        <div class="tab active">
                            Description
                        </div>

                        <div class="tab">
                            Additional Information
                        </div>

                        <div class="tab">
                            Reviews
                        </div>

                    </div>


                    <div class="description-text">

                        {{ $product->description ?? 'Discover our stylish item. Designed with comfort, quality and modern fashion in mind.' }}

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 RIGHT SIDE
            ====================================================== --}}

            <div class="product-information">


                <div class="collection-label">
                    Fashion Collection
                </div>


                <h1 class="product-title">
                    {{ $product->name }}
                </h1>


                {{-- RATING --}}

                <div class="rating-area">

                    <div class="stars">
                        ★★★★★
                    </div>

                    <span class="review-count">
                        (24 Reviews)
                    </span>

                </div>


                {{-- PRICE --}}

                <div class="product-price-large">

                    Rs {{ number_format($product->price, 2) }}

                </div>


                {{-- DESCRIPTION --}}

                <p class="short-description">

                    Discover our stylish
                    {{ $product->name }}.

                    Designed with comfort,
                    quality and modern fashion
                    in mind.

                </p>


                {{-- STOCK --}}

                <div class="stock-information">

                    @if($product->stock > 0)

                        <div class="stock-status in-stock">

                            <span class="stock-icon">

                                <i class="bi bi-check-lg"></i>

                            </span>

                            In Stock

                        </div>


                        <div class="stock-divider"></div>


                        <div class="stock-left">

                            Only

                            <strong>
                                {{ $product->stock }}
                            </strong>

                            left

                        </div>

                    @else

                        <div class="stock-status out-stock">

                            <span class="stock-icon">

                                <i class="bi bi-x-lg"></i>

                            </span>

                            Out of Stock

                        </div>

                    @endif

                </div>


                {{-- =================================================
                     QUANTITY + CART
                ================================================== --}}

                @if($product->stock > 0)

                    <span class="quantity-title">
                        Quantity
                    </span>


                    <div class="quantity-control">

                        <button
                            type="button"
                            onclick="decreaseQty()">

                            -

                        </button>


                        <input
                            type="number"
                            id="productQty"
                            value="1"
                            min="1"
                            max="{{ $product->stock }}">


                        <button
                            type="button"
                            onclick="increaseQty()">

                            +

                        </button>

                    </div>


                    <div class="cart-actions">


                        {{-- ADD TO CART --}}

                        <button
                            type="button"
                            class="add-cart-button"
                            id="addToCartBtn"
                            onclick="addToCart('{{ $product->uuid }}')">

                            <i class="bi bi-bag"></i>

                            Add to Cart

                        </button>






                    </div>

                @else

                    <button
                        type="button"
                        class="disabled-cart"
                        disabled>

                        Out of Stock

                    </button>

                @endif


                {{-- BENEFITS --}}

                <div class="benefits">


                    <div class="benefit">

                        <i class="bi bi-truck benefit-icon"></i>

                        <div>

                            <span class="benefit-title">
                                Free Shipping
                            </span>

                            <span class="benefit-text">
                                On orders over Rs 5,000
                            </span>

                        </div>

                    </div>


                    <div class="benefit">

                        <i class="bi bi-arrow-counterclockwise benefit-icon"></i>

                        <div>

                            <span class="benefit-title">
                                Easy Returns
                            </span>

                            <span class="benefit-text">
                                30 days return policy
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>


    {{-- =========================================================
         JAVASCRIPT
    ========================================================= --}}

    <script>


        /*
        |--------------------------------------------------------------------------
        | CSRF TOKEN
        |--------------------------------------------------------------------------
        */

        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')

            }

        });


        /*
        |--------------------------------------------------------------------------
        | CHANGE MAIN IMAGE
        |--------------------------------------------------------------------------
        */

        function changeMainImage(element) {

            $('#mainProductImage')
                .attr('src', $(element).attr('src'));

            $('.thumbnail')
                .removeClass('active');

            $(element)
                .addClass('active');
        }


        /*
        |--------------------------------------------------------------------------
        | INCREASE QUANTITY
        |--------------------------------------------------------------------------
        */

        function increaseQty() {

            let input = $('#productQty');

            let max =
                parseInt(input.attr('max'));

            let current =
                parseInt(input.val());


            if (isNaN(current)) {

                current = 1;

            }


            if (current < max) {

                input.val(current + 1);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | DECREASE QUANTITY
        |--------------------------------------------------------------------------
        */

        function decreaseQty() {

            let input = $('#productQty');

            let current =
                parseInt(input.val());


            if (isNaN(current) || current < 1) {

                current = 1;

            }


            if (current > 1) {

                input.val(current - 1);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ADD TO CART
        |--------------------------------------------------------------------------
        */

        function addToCart(productUuid) {


            let quantity =
                parseInt($('#productQty').val());


            let maxStock =
                parseInt($('#productQty').attr('max'));


            let btn =
                $('#addToCartBtn');


            /*
            |--------------------------------------------------------------------------
            | Validate Quantity
            |--------------------------------------------------------------------------
            */

            if (
                isNaN(quantity) ||
                quantity < 1
            ) {

                showCartError(
                    'Please select a valid quantity.'
                );

                return;

            }


            if (quantity > maxStock) {

                showCartError(
                    'Only ' + maxStock + ' item(s) are available.'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Disable Button
            |--------------------------------------------------------------------------
            */

            btn
                .prop('disabled', true)
                .html(
                    '<i class="bi bi-hourglass-split"></i> Adding...'
                );


            /*
            |--------------------------------------------------------------------------
            | AJAX REQUEST
            |--------------------------------------------------------------------------
            */

            $.ajax({

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                | Route receives UUID, not ID.
                |--------------------------------------------------------------------------
                */

                url: "{{ url('/cart/add') }}/" + productUuid,

                type: "POST",

                data: {

                    quantity: quantity

                },


                /*
                |--------------------------------------------------------------------------
                | SUCCESS
                |--------------------------------------------------------------------------
                */

                success: function(response) {


                    btn
                        .prop('disabled', false)
                        .html(
                            '<i class="bi bi-bag"></i> Add to Cart'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Update Cart Count
                    |--------------------------------------------------------------------------
                    */

                    if (
                        response.cartCount !== undefined
                    ) {

                        $('#cartCount')
                            .text(response.cartCount)
                            .show();

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Show Success
                    |--------------------------------------------------------------------------
                    */

                    $('#cartSuccessText')
                        .text(
                            response.message ||
                            'Product added to cart successfully.'
                        );


                    $('#cartSuccessMessage')
                        .stop(true, true)
                        .fadeIn(300)
                        .delay(2500)
                        .fadeOut(400);

                },


                /*
                |--------------------------------------------------------------------------
                | ERROR
                |--------------------------------------------------------------------------
                */

                error: function(xhr) {


                    btn
                        .prop('disabled', false)
                        .html(
                            '<i class="bi bi-bag"></i> Add to Cart'
                        );


                    let msg =
                        'Something went wrong. Please try again.';


                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {

                        msg =
                            xhr.responseJSON.message;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Validation Errors
                    |--------------------------------------------------------------------------
                    */

                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.errors
                    ) {

                        let errors =
                            xhr.responseJSON.errors;

                        let firstError =
                            Object.values(errors)[0];

                        if (
                            firstError &&
                            firstError[0]
                        ) {

                            msg =
                                firstError[0];

                        }

                    }


                    showCartError(msg);

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | ERROR TOAST
        |--------------------------------------------------------------------------
        */

        function showCartError(message) {

            $('#cartErrorText')
                .text(message);


            $('#cartErrorMessage')
                .stop(true, true)
                .fadeIn(300)
                .delay(3000)
                .fadeOut(400);

        }


    </script>

</body>

</html>
