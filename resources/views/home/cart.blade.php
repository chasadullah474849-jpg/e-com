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

    <title>Shopping Cart | Kaira</title>


    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;

            background: #f8f9fa;

            color: #212529;

            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }


        /* =====================================================
           MAIN CONTAINER
        ===================================================== */

        .cart-wrapper {

            max-width: 1400px;

            margin: 0 auto;

            padding: 60px 25px;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .cart-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 35px;
        }


        .cart-title {

            margin: 0;

            font-size: 48px;

            font-weight: 700;

            color: #212529;
        }


        .continue-shopping {

            display: inline-block;

            padding: 12px 20px;

            border: 1px solid #6c757d;

            border-radius: 10px;

            color: #5c7083;

            background: white;

            text-decoration: none;

            font-size: 18px;

            transition: all 0.2s ease;
        }


        .continue-shopping:hover {

            background: #212529;

            color: white;

            border-color: #212529;
        }


        /* =====================================================
           CART CARD
        ===================================================== */

        .cart-card {

            background: #ffffff;

            border-radius: 20px;

            padding: 20px;

            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.07);
        }


        /* =====================================================
           CART ITEM
        ===================================================== */

        .cart-item {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 25px;

            padding: 20px 0;

            border-bottom: 1px solid #dee2e6;
        }


        .cart-item:last-child {

            border-bottom: none;
        }


        /* =====================================================
           PRODUCT INFORMATION
        ===================================================== */

        .product-info {

            display: flex;

            align-items: center;

            gap: 20px;

            min-width: 0;

            flex: 1;
        }


        /* =====================================================
           PRODUCT IMAGE
        ===================================================== */

        .product-image-wrapper {

            width: 115px;

            height: 115px;

            min-width: 115px;

            border-radius: 16px;

            overflow: hidden;

            background: #dbe2e8;

            display: flex;

            align-items: center;

            justify-content: center;

            border: 1px solid #e1e5e8;
        }


        .product-image {

            width: 100%;

            height: 100%;

            display: block;

            object-fit: cover;
        }


        /* =====================================================
           PLACEHOLDER
        ===================================================== */

        .image-placeholder {

            width: 100%;

            height: 100%;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #dbe2e8;
        }


        .image-placeholder svg {

            width: 60px;

            height: 60px;
        }


        /* =====================================================
           PRODUCT NAME
        ===================================================== */

        .product-name {

            margin: 0 0 7px 0;

            font-size: 22px;

            font-weight: 700;

            color: #17202a;
        }


        .product-price {

            margin: 0;

            font-size: 17px;

            color: #52616f;
        }


        /* =====================================================
           RIGHT SIDE
        ===================================================== */

        .cart-actions {

            display: flex;

            align-items: center;

            justify-content: flex-end;

            gap: 30px;
        }


        /* =====================================================
           QUANTITY
        ===================================================== */

        .quantity-box {

            display: flex;

            align-items: center;

            gap: 5px;

            padding: 4px 7px;

            border: 1px solid #dee2e6;

            border-radius: 50px;

            background: #ffffff;

            box-shadow:
                0 2px 7px rgba(0, 0, 0, 0.05);
        }


        .quantity-button {

            width: 36px;

            height: 36px;

            padding: 0;

            border-radius: 50%;

            border: 1px solid #adb5bd;

            background: white;

            color: #52616f;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 20px;

            cursor: pointer;

            transition: all 0.2s ease;
        }


        .quantity-button:hover {

            background: #212529;

            color: white;

            border-color: #212529;
        }


        .quantity-button:disabled {

            opacity: 0.5;

            cursor: not-allowed;
        }


        .quantity-value {

            width: 38px;

            text-align: center;

            border: none;

            outline: none;

            background: transparent;

            font-size: 17px;

            font-weight: 600;

            color: #212529;
        }


        /* =====================================================
           ITEM TOTAL
        ===================================================== */

        .item-total {

            min-width: 130px;

            text-align: right;

            font-size: 21px;

            font-weight: 700;

            color: #17202a;
        }


        /* =====================================================
           REMOVE BUTTON
        ===================================================== */

        .remove-button {

            min-width: 95px;

            padding: 9px 16px;

            border-radius: 8px;

            border: 1px solid #dc3545;

            background: white;

            color: #dc3545;

            cursor: pointer;

            font-size: 16px;

            transition: all 0.2s ease;
        }


        .remove-button:hover {

            background: #dc3545;

            color: white;
        }


        /* =====================================================
           SUMMARY
        ===================================================== */

        .summary-card {

            background: white;

            border-radius: 20px;

            padding: 32px;

            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.07);

            position: sticky;

            top: 25px;
        }


        .summary-title {

            margin: 0 0 35px 0;

            font-size: 29px;

            font-weight: 700;
        }


        .summary-row {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;
        }


        .summary-label {

            font-size: 21px;

            font-weight: 700;
        }


        .summary-total {

            font-size: 22px;

            font-weight: 700;
        }


        /* =====================================================
           CHECKOUT
        ===================================================== */

        .checkout-button {

            display: block;

            width: 100%;

            padding: 17px 20px;

            border: none;

            border-radius: 10px;

            background: #212529;

            color: white;

            text-align: center;

            text-decoration: none;

            font-size: 18px;

            font-weight: 700;

            transition: all 0.2s ease;
        }


        .checkout-button:hover {

            background: #000000;

            color: white;
        }


        .checkout-button.disabled {

            opacity: 0.5;

            pointer-events: none;
        }


        /* =====================================================
           EMPTY CART
        ===================================================== */

        .empty-cart {

            text-align: center;

            padding: 70px 20px;
        }


        .empty-cart h3 {

            margin-bottom: 20px;

            font-size: 28px;

            color: #6c757d;
        }


        .shop-now-button {

            display: inline-block;

            padding: 12px 25px;

            background: #212529;

            color: white;

            text-decoration: none;

            border-radius: 8px;
        }


        .shop-now-button:hover {

            background: #000;

            color: white;
        }


        /* =====================================================
           ALERT
        ===================================================== */

        #cart-message {

            position: fixed;

            top: 25px;

            right: 25px;

            z-index: 99999;

            min-width: 300px;

            display: none;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1200px) {

            .cart-actions {

                gap: 15px;
            }

            .item-total {

                min-width: 110px;
            }
        }


        @media (max-width: 991px) {

            .cart-header {

                align-items: flex-start;

                gap: 20px;
            }


            .cart-title {

                font-size: 40px;
            }


            .summary-card {

                position: static;
            }


            .cart-item {

                align-items: flex-start;

                flex-direction: column;
            }


            .cart-actions {

                width: 100%;

                justify-content: space-between;
            }
        }


        @media (max-width: 600px) {

            .cart-wrapper {

                padding: 30px 15px;
            }


            .cart-header {

                flex-direction: column;
            }


            .cart-title {

                font-size: 32px;
            }


            .continue-shopping {

                font-size: 15px;
            }


            .product-image-wrapper {

                width: 90px;

                height: 90px;

                min-width: 90px;
            }


            .product-name {

                font-size: 18px;
            }


            .product-price {

                font-size: 15px;
            }


            .cart-actions {

                flex-wrap: wrap;

                gap: 15px;
            }


            .item-total {

                min-width: auto;

                font-size: 18px;
            }
        }

    </style>

</head>


<body>


<!-- =========================================================
     MESSAGE
========================================================= -->

<div
    id="cart-message"
    class="alert shadow"
>
</div>



<!-- =========================================================
     MAIN
========================================================= -->

<div class="cart-wrapper">


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="cart-header">

        <h1 class="cart-title">
            Shopping Cart
        </h1>


        <a
            href="{{ url('/') }}"
            class="continue-shopping"
        >
            Continue Shopping
        </a>

    </div>



    <!-- =====================================================
         SESSION SUCCESS
    ====================================================== -->

    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
        >

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif



    <!-- =====================================================
         SESSION ERROR
    ====================================================== -->

    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show"
        >

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif



    <div class="row g-4">


        <!-- =================================================
             LEFT SIDE
        ================================================== -->

        <div class="col-lg-8">


            <div class="cart-card">


                @if(isset($cart) && count($cart) > 0)


                    @foreach($cart as $key => $item)


                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | BASIC CART DATA
                            |--------------------------------------------------------------------------
                            */

                            $itemName = 'Product';

                            $itemPrice = 0;

                            $itemQuantity = 1;

                            $product = null;

                            $productId = null;

                            $productUuid = null;

                            $rawImage = null;


                            /*
                            |--------------------------------------------------------------------------
                            | OBJECT CART ITEM
                            |--------------------------------------------------------------------------
                            */

                            if (is_object($item)) {

                                $itemName =
                                    $item->name
                                    ?? $item->title
                                    ?? 'Product';

                                $itemPrice =
                                    $item->price
                                    ?? 0;

                                $itemQuantity =
                                    $item->quantity
                                    ?? 1;

                                $product =
                                    $item->product
                                    ?? null;

                                $productId =
                                    $item->product_id
                                    ?? $item->id
                                    ?? null;

                                $productUuid =
                                    $item->uuid
                                    ?? null;

                                $rawImage =
                                    $item->image
                                    ?? null;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | ARRAY CART ITEM
                            |--------------------------------------------------------------------------
                            */

                            if (is_array($item)) {

                                $itemName =
                                    $item['name']
                                    ?? $item['title']
                                    ?? 'Product';

                                $itemPrice =
                                    $item['price']
                                    ?? 0;

                                $itemQuantity =
                                    $item['quantity']
                                    ?? 1;

                                $product =
                                    $item['product']
                                    ?? null;

                                $productId =
                                    $item['product_id']
                                    ?? $item['id']
                                    ?? null;

                                $productUuid =
                                    $item['uuid']
                                    ?? null;

                                $rawImage =
                                    $item['image']
                                    ?? null;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | PRODUCT OBJECT
                            |--------------------------------------------------------------------------
                            */

                            if (!$product && $productId) {

                                try {

                                    $product =
                                        \App\Models\Product::find($productId);

                                } catch (\Throwable $e) {

                                    $product = null;
                                }
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | FIND PRODUCT USING UUID
                            |--------------------------------------------------------------------------
                            */

                            if (!$product && $productUuid) {

                                try {

                                    $product =
                                        \App\Models\Product::where(
                                            'uuid',
                                            $productUuid
                                        )->first();

                                } catch (\Throwable $e) {

                                    $product = null;
                                }
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | GET PRODUCT IMAGE COLUMN
                            |--------------------------------------------------------------------------
                            */

                            if (!$rawImage && $product) {

                                $rawImage =
                                    $product->image
                                    ?? null;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | GET FIRST PRODUCT IMAGE
                            |--------------------------------------------------------------------------
                            */

                            if (!$rawImage && $product) {

                                try {

                                    if (
                                        isset($product->images)
                                        &&
                                        $product->images->count() > 0
                                    ) {

                                        $firstImage =
                                            $product->images->first();

                                        $rawImage =
                                            $firstImage->image
                                            ?? null;
                                    }

                                } catch (\Throwable $e) {

                                    $rawImage = null;
                                }
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | IMAGE URL
                            |--------------------------------------------------------------------------
                            */

                            $imageUrl = null;


                            if ($rawImage) {

                                $rawImage =
                                    trim($rawImage);


                                $filename =
                                    basename($rawImage);


                                /*
                                |--------------------------------------------------------------------------
                                | EXTERNAL IMAGE
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    filter_var(
                                        $rawImage,
                                        FILTER_VALIDATE_URL
                                    )
                                ) {

                                    $imageUrl =
                                        $rawImage;
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | PUBLIC/UPLOADS/PRODUCTS
                                |--------------------------------------------------------------------------
                                */

                                elseif (
                                    file_exists(
                                        public_path(
                                            'uploads/products/'
                                            . $filename
                                        )
                                    )
                                ) {

                                    $imageUrl =
                                        asset(
                                            'uploads/products/'
                                            . $filename
                                        );
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | PUBLIC/STORAGE/PRODUCTS
                                |--------------------------------------------------------------------------
                                */

                                elseif (
                                    file_exists(
                                        public_path(
                                            'storage/products/'
                                            . $filename
                                        )
                                    )
                                ) {

                                    $imageUrl =
                                        asset(
                                            'storage/products/'
                                            . $filename
                                        );
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | PUBLIC/UPLOADS
                                |--------------------------------------------------------------------------
                                */

                                elseif (
                                    file_exists(
                                        public_path(
                                            'uploads/'
                                            . $filename
                                        )
                                    )
                                ) {

                                    $imageUrl =
                                        asset(
                                            'uploads/'
                                            . $filename
                                        );
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | STORAGE PATH
                                |--------------------------------------------------------------------------
                                */

                                elseif (
                                    str_starts_with(
                                        $rawImage,
                                        'storage/'
                                    )
                                ) {

                                    $imageUrl =
                                        asset(
                                            $rawImage
                                        );
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | DIRECT PUBLIC PATH
                                |--------------------------------------------------------------------------
                                */

                                elseif (
                                    file_exists(
                                        public_path(
                                            $rawImage
                                        )
                                    )
                                ) {

                                    $imageUrl =
                                        asset(
                                            $rawImage
                                        );
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | LAST FALLBACK
                                |--------------------------------------------------------------------------
                                */

                                else {

                                    $imageUrl =
                                        asset(
                                            'uploads/products/'
                                            . $filename
                                        );
                                }
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | SVG PLACEHOLDER
                            |--------------------------------------------------------------------------
                            */

                            $placeholder =

                                "data:image/svg+xml;utf8,"
                                .
                                rawurlencode(

                                    "<svg
                                        xmlns='http://www.w3.org/2000/svg'
                                        width='115'
                                        height='115'
                                        viewBox='0 0 115 115'
                                    >

                                        <rect
                                            width='115'
                                            height='115'
                                            fill='#dbe2e8'
                                        />

                                        <circle
                                            cx='43'
                                            cy='39'
                                            r='8'
                                            fill='#8fa3b0'
                                        />

                                        <path
                                            d='M20 82
                                               L43 53
                                               L59 70
                                               L73 54
                                               L97 82
                                               Z'
                                            fill='#8fa3b0'
                                        />

                                    </svg>"
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | USE PLACEHOLDER IF NO IMAGE
                            |--------------------------------------------------------------------------
                            */

                            if (!$imageUrl) {

                                $imageUrl =
                                    $placeholder;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | LINE TOTAL
                            |--------------------------------------------------------------------------
                            */

                            $lineTotal =
                                (float) $itemPrice
                                *
                                (int) $itemQuantity;

                        @endphp



                        <!-- =================================================
                             CART ITEM
                        ================================================== -->

                        <div
                            class="cart-item"
                            data-cart-id="{{ $key }}"
                        >


                            <!-- =============================================
                                 PRODUCT
                            ============================================== -->

                            <div class="product-info">


                                <div class="product-image-wrapper">


                                    <img
                                        src="{{ $imageUrl }}"
                                        alt="{{ $itemName }}"
                                        class="product-image"
                                        loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ $placeholder }}';"
                                    >


                                </div>



                                <div>

                                    <h3 class="product-name">
                                        {{ $itemName }}
                                    </h3>


                                    <p class="product-price">
                                        Rs
                                        {{ number_format((float) $itemPrice, 2) }}
                                    </p>

                                </div>


                            </div>



                            <!-- =============================================
                                 ACTIONS
                            ============================================== -->

                            <div class="cart-actions">


                                <!-- QUANTITY -->

                                <div class="quantity-box">


                                    <button
                                        type="button"
                                        class="quantity-button quantity-minus"
                                    >
                                        −
                                    </button>


                                    <input
                                        type="text"
                                        class="quantity-value"
                                        value="{{ $itemQuantity }}"
                                        readonly
                                    >


                                    <button
                                        type="button"
                                        class="quantity-button quantity-plus"
                                    >
                                        +
                                    </button>


                                </div>



                                <!-- ITEM TOTAL -->

                                <div class="item-total">

                                    Rs
                                    <span class="line-total">
                                        {{ number_format($lineTotal, 2) }}
                                    </span>

                                </div>



                                <!-- REMOVE -->

                                <form
                                    action="{{ route('cart.remove', $key) }}"
                                    method="POST"
                                    class="remove-form"
                                >

                                    @csrf

                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="remove-button"
                                    >
                                        Remove
                                    </button>

                                </form>


                            </div>


                        </div>


                    @endforeach


                @else


                    <!-- =================================================
                         EMPTY CART
                    ================================================== -->

                    <div class="empty-cart">


                        <h3>
                            Your cart is empty.
                        </h3>


                        <a
                            href="{{ url('/') }}"
                            class="shop-now-button"
                        >
                            Continue Shopping
                        </a>


                    </div>


                @endif


            </div>


        </div>



        <!-- =================================================
             RIGHT SIDE
        ================================================== -->

        <div class="col-lg-4">


            @php

                /*
                |--------------------------------------------------------------------------
                | CALCULATE TOTAL
                |--------------------------------------------------------------------------
                */

                $cartTotal = 0;


                if (isset($cart) && count($cart) > 0) {

                    foreach ($cart as $cartItem) {

                        $price = 0;

                        $quantity = 1;


                        if (is_object($cartItem)) {

                            $price =
                                $cartItem->price
                                ?? 0;

                            $quantity =
                                $cartItem->quantity
                                ?? 1;

                        } elseif (is_array($cartItem)) {

                            $price =
                                $cartItem['price']
                                ?? 0;

                            $quantity =
                                $cartItem['quantity']
                                ?? 1;
                        }


                        $cartTotal +=
                            (float) $price
                            *
                            (int) $quantity;
                    }
                }

            @endphp



            <!-- =================================================
                 SUMMARY CARD
            ================================================== -->

            <div class="summary-card">


                <h2 class="summary-title">
                    Cart Summary
                </h2>


                <div class="summary-row">


                    <span class="summary-label">
                        Total
                    </span>


                    <span class="summary-total">

                        Rs
                        <span id="cart-total">
                            {{ number_format($cartTotal, 2) }}
                        </span>

                    </span>


                </div>



                @if(isset($cart) && count($cart) > 0)


                    <a
                        href="{{ url('/checkout') }}"
                        class="checkout-button"
                        id="checkout-button"
                    >
                        Proceed to Checkout
                    </a>


                @else


                    <a
                        href="{{ url('/checkout') }}"
                        class="checkout-button disabled"
                    >
                        Proceed to Checkout
                    </a>


                @endif


            </div>


        </div>


    </div>


</div>



<!-- =========================================================
     BOOTSTRAP JS
========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | CSRF TOKEN
        |--------------------------------------------------------------------------
        */

        const csrfTokenElement =
            document.querySelector(
                'meta[name="csrf-token"]'
            );


        const csrfToken =
            csrfTokenElement
                ? csrfTokenElement.getAttribute('content')
                : '';



        /*
        |--------------------------------------------------------------------------
        | CART ITEMS
        |--------------------------------------------------------------------------
        */

        const cartItems =
            document.querySelectorAll(
                '.cart-item'
            );


        cartItems.forEach(
            function (item) {


                const cartId =
                    item.dataset.cartId;


                const quantityInput =
                    item.querySelector(
                        '.quantity-value'
                    );


                const minusButton =
                    item.querySelector(
                        '.quantity-minus'
                    );


                const plusButton =
                    item.querySelector(
                        '.quantity-plus'
                    );


                const lineTotal =
                    item.querySelector(
                        '.line-total'
                    );



                /*
                |--------------------------------------------------------------------------
                | PLUS
                |--------------------------------------------------------------------------
                */

                if (plusButton) {

                    plusButton.addEventListener(
                        'click',
                        function () {


                            let quantity =
                                parseInt(
                                    quantityInput.value
                                ) || 1;


                            quantity++;


                            updateQuantity(
                                cartId,
                                quantity,
                                item,
                                quantityInput,
                                lineTotal
                            );

                        }
                    );
                }



                /*
                |--------------------------------------------------------------------------
                | MINUS
                |--------------------------------------------------------------------------
                */

                if (minusButton) {

                    minusButton.addEventListener(
                        'click',
                        function () {


                            let quantity =
                                parseInt(
                                    quantityInput.value
                                ) || 1;


                            if (quantity <= 1) {

                                return;
                            }


                            quantity--;


                            updateQuantity(
                                cartId,
                                quantity,
                                item,
                                quantityInput,
                                lineTotal
                            );

                        }
                    );
                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | UPDATE QUANTITY
        |--------------------------------------------------------------------------
        */

        function updateQuantity(
            cartId,
            quantity,
            item,
            quantityInput,
            lineTotal
        ) {


            const buttons =
                item.querySelectorAll(
                    '.quantity-button'
                );


            buttons.forEach(
                function (button) {

                    button.disabled = true;

                }
            );



            fetch(
                "{{ route('cart.update-quantity') }}",
                {

                    method: 'POST',

                    headers: {

                        'Content-Type':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken,

                        'Accept':
                            'application/json'
                    },


                    body: JSON.stringify({

                        item_id:
                            cartId,

                        quantity:
                            quantity

                    })

                }
            )


            .then(
                function (response) {

                    if (!response.ok) {

                        throw new Error(
                            'HTTP Error: '
                            + response.status
                        );
                    }


                    return response.json();

                }
            )


            .then(
                function (data) {


                    if (data.success) {


                        /*
                        |--------------------------------------------------------------------------
                        | UPDATE QUANTITY
                        |--------------------------------------------------------------------------
                        */

                        quantityInput.value =
                            quantity;



                        /*
                        |--------------------------------------------------------------------------
                        | UPDATE LINE TOTAL
                        |--------------------------------------------------------------------------
                        */

                        if (
                            lineTotal
                            &&
                            data.item_total !== undefined
                        ) {

                            lineTotal.textContent =
                                data.item_total;
                        }



                        /*
                        |--------------------------------------------------------------------------
                        | UPDATE CART TOTAL
                        |--------------------------------------------------------------------------
                        */

                        const totalElement =
                            document.getElementById(
                                'cart-total'
                            );


                        if (
                            totalElement
                            &&
                            data.total !== undefined
                        ) {

                            totalElement.textContent =
                                data.total;
                        }


                        showMessage(
                            data.message
                            ||
                            'Cart updated successfully.',
                            'success'
                        );


                    } else {


                        showMessage(
                            data.message
                            ||
                            'Unable to update cart.',
                            'danger'
                        );

                    }

                }
            )


            .catch(
                function (error) {

                    console.error(
                        'Cart Error:',
                        error
                    );


                    showMessage(
                        'Something went wrong while updating the cart.',
                        'danger'
                    );

                }
            )


            .finally(
                function () {

                    buttons.forEach(
                        function (button) {

                            button.disabled = false;

                        }
                    );

                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | REMOVE CONFIRMATION
        |--------------------------------------------------------------------------
        */

        const removeForms =
            document.querySelectorAll(
                '.remove-form'
            );


        removeForms.forEach(
            function (form) {

                form.addEventListener(
                    'submit',
                    function (event) {


                        const confirmed =
                            confirm(
                                'Are you sure you want to remove this product from the cart?'
                            );


                        if (!confirmed) {

                            event.preventDefault();

                        }

                    }
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | SHOW MESSAGE
        |--------------------------------------------------------------------------
        */

        function showMessage(
            message,
            type
        ) {


            const messageBox =
                document.getElementById(
                    'cart-message'
                );


            if (!messageBox) {

                return;
            }


            messageBox.className =
                'alert shadow alert-'
                + type;


            messageBox.textContent =
                message;


            messageBox.style.display =
                'block';



            setTimeout(
                function () {

                    messageBox.style.display =
                        'none';

                },
                2500
            );

        }

    }
);

</script>


</body>

</html>
