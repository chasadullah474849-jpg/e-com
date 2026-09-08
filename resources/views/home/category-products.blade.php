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
        | Navbar
        |--------------------------------------------------------------------------
        */

        .main-navbar {
            padding: 20px 0;
            border-bottom: 1px solid #eeeeee;
            background-color: #ffffff;
        }

        .navbar-brand {
            color: #111111;
            font-family: Georgia, serif;
            font-size: 30px;
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        .navbar-brand:hover {
            color: #111111;
        }

        .main-navbar .nav-link {
            position: relative;
            display: inline-block;
            margin: 0 12px;
            padding: 8px 0;
            color: #333333;
            font-size: 14px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .main-navbar .nav-link:hover,
        .main-navbar .nav-link.active {
            color: #000000;
        }

        .main-navbar .nav-link::after {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            margin: auto;
            background-color: #111111;
            content: "";
            transition: width 0.3s ease;
        }

        .main-navbar .nav-link:hover::after,
        .main-navbar .nav-link.active::after {
            width: 100%;
        }

        .navbar-toggler {
            border: 0;
            box-shadow: none !important;
        }

        .cart-link {
            display: inline-block;
            padding: 9px 18px;
            border: 1px solid #111111;
            color: #111111;
            font-size: 13px;
            letter-spacing: 1px;
            text-decoration: none;
            text-transform: uppercase;
            transition: 0.3s;
        }

        .cart-link:hover {
            background-color: #111111;
            color: #ffffff;
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

            .main-navbar .navbar-collapse {
                padding-top: 20px;
            }

            .main-navbar .nav-link {
                margin: 5px 0;
            }

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


    {{-- ====================================================== --}}
    {{-- NAVBAR --}}
    {{-- ====================================================== --}}

    <nav class="navbar navbar-expand-lg main-navbar">

        <div class="container">

            <a
                class="navbar-brand"
                href="{{ route('home') }}"
            >
                Kaira
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div
                class="collapse navbar-collapse"
                id="mainNavbar"
            >

                <ul class="navbar-nav mx-auto">

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('home') }}"
                        >
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            href="{{ route('productss') }}"
                        >
                            Products
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('collections') }}"
                        >
                            Collections
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('home.blogs') }}"
                        >
                            Blogs
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('home.contact') }}"
                        >
                            Contact
                        </a>
                    </li>

                </ul>

                <a
                    href="{{ route('cart') }}"
                    class="cart-link"
                >
                    Cart
                </a>

            </div>

        </div>

    </nav>


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
                                ['uuid' => $product->uuid]
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
