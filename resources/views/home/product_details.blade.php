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
           NAVBAR
        ========================================================= */

        .kaira-navbar {
            height: 82px;
            border-bottom: 1px solid #eeeeee;
            background: #ffffff;
            display: flex;
            align-items: center;
        }

        .navbar-inner {
            width: 100%;
            max-width: 1440px;
            margin: auto;
            padding: 0 45px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .kaira-logo {
            font-family: Georgia, serif;
            font-size: 38px;
            font-style: italic;
            color: #111111;
            letter-spacing: -2px;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 55px;
            margin-left: 80px;
        }

        .nav-menu a {
            color: #111111;
            font-size: 15px;
            font-weight: 500;
            text-transform: uppercase;
            transition: 0.3s;
        }

        .nav-menu a:hover {
            color: #777777;
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .nav-icon {
            position: relative;
            color: #111111;
            font-size: 25px;
        }

        .nav-count {
            position: absolute;
            top: -8px;
            right: -9px;

            min-width: 18px;
            height: 18px;

            padding: 0 4px;

            border-radius: 50%;

            background: #111111;
            color: #ffffff;

            font-size: 10px;

            display: flex;
            align-items: center;
            justify-content: center;
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

            .nav-menu {
                display: none;
            }

            .product-layout {
                grid-template-columns: 1fr;

                gap: 45px;
            }

        }


        @media (max-width: 767px) {

            .navbar-inner {
                padding: 0 20px;
            }

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


    {{-- =========================================================
         NAVBAR
    ========================================================= --}}

    <header class="kaira-navbar">

        <div class="navbar-inner">

            <a
                href="{{ url('/') }}"
                class="kaira-logo">
                Kaira
            </a>


            <nav class="nav-menu">

                <a href="{{ url('/') }}">
                    Home
                </a>

                <a href="{{ url('/product') }}">
                    Shop
                </a>

                <a href="#">
                    Collections
                </a>

                <a href="#">
                    About Us
                </a>

                <a href="#">
                    Contact
                </a>

            </nav>


            <div class="nav-icons">

                <a
                    href="#"
                    class="nav-icon">

                    <i class="bi bi-search"></i>

                </a>


                <a
                    href="#"
                    class="nav-icon">

                    <i class="bi bi-heart"></i>

                    <span class="nav-count">
                        0
                    </span>

                </a>


                <a
                    href="{{ route('cart') }}"
                    class="nav-icon">

                    <i class="bi bi-bag"></i>

                    @php

                        $cartCount = 0;

                        $cartItems = session('cart', []);

                        foreach ($cartItems as $cartItem) {

                            $cartCount +=
                                $cartItem['quantity'] ?? 0;

                        }

                    @endphp


                    <span
                        class="nav-count"
                        id="cartCount"
                        style="{{ $cartCount > 0 ? '' : 'display:none;' }}">

                        {{ $cartCount }}

                    </span>

                </a>

            </div>

        </div>

    </header>


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

            <a href="{{ url('/product') }}">
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
