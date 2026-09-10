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

    <title>Search Results | Kaira</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            margin: 0;
            background-color: #ffffff;
            color: #212529;
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
            margin: 0 12px;
            color: #333333;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .main-navbar .nav-link:hover {
            color: #000000;
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
        | Search Area
        |--------------------------------------------------------------------------
        */

        .search-header {
            padding: 65px 0;
            background-color: #f7f7f7;
        }

        .search-title {
            margin-bottom: 15px;
            font-family: Georgia, serif;
            font-size: 48px;
            font-weight: 400;
            text-transform: uppercase;
        }

        .search-summary {
            color: #777777;
            font-size: 17px;
        }

        .global-search-form {
            max-width: 900px;
            margin-top: 30px;
        }

        .global-search-form .form-control {
            height: 58px;
            padding-right: 20px;
            padding-left: 20px;
            border-color: #dddddd;
            border-radius: 0;
            font-size: 16px;
        }

        .global-search-form .btn {
            min-width: 130px;
            border-radius: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Results
        |--------------------------------------------------------------------------
        */

        .results-area {
            padding: 70px 0;
        }

        .result-section {
            margin-bottom: 80px;
        }

        .result-heading {
            margin-bottom: 35px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dddddd;
            font-family: Georgia, serif;
            font-size: 35px;
            font-weight: 400;
            text-transform: uppercase;
        }

        .result-card {
            display: block;
            height: 100%;
            overflow: hidden;
            border: 1px solid #eeeeee;
            background-color: #ffffff;
            color: #111111;
            text-decoration: none;
            transition: 0.3s;
        }

        .result-card:hover {
            color: #111111;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.10);
        }

        .image-wrapper {
            overflow: hidden;
            background-color: #f4f4f4;
        }

        .result-image {
            display: block;
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .result-card:hover .result-image {
            transform: scale(1.05);
        }

        .result-content {
            padding: 24px;
        }

        .result-type {
            margin-bottom: 8px;
            color: #999999;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .result-name {
            margin-bottom: 10px;
            font-family: Georgia, serif;
            font-size: 22px;
            line-height: 1.4;
        }

        .result-description {
            margin-bottom: 0;
            color: #777777;
            line-height: 1.7;
        }

        .result-price {
            margin-top: 12px;
            color: #555555;
            font-size: 17px;
        }

        /*
        |--------------------------------------------------------------------------
        | Empty Result
        |--------------------------------------------------------------------------
        */

        .no-results {
            padding: 80px 20px;
            background-color: #f8f8f8;
            text-align: center;
        }

        .no-results h2 {
            font-family: Georgia, serif;
            font-size: 38px;
            font-weight: 400;
            text-transform: uppercase;
        }

        .no-results p {
            color: #777777;
        }

        @media (max-width: 991px) {

            .main-navbar .navbar-collapse {
                padding-top: 20px;
            }

            .main-navbar .nav-link {
                margin: 7px 0;
            }

            .cart-link {
                margin-top: 15px;
            }

        }

        @media (max-width: 768px) {

            .search-title {
                font-size: 36px;
            }

            .global-search-form .btn {
                min-width: auto;
            }

            .result-image {
                height: 340px;
            }

        }

    </style>

</head>

<body>

    @php
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


    {{-- Navbar --}}

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
                            class="nav-link"
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


    {{-- Search Header --}}

    <section class="search-header">

        <div class="container">

            <h1 class="search-title">
                Search Results
            </h1>

            @if($search !== '')

                <p class="search-summary">
                    {{ $totalResults }} result(s) found for
                    “{{ $search }}”
                </p>

            @else

                <p class="search-summary">
                    Enter a word to search the website.
                </p>

            @endif

            <form
                action="{{ route('search') }}"
                method="GET"
                class="global-search-form"
            >

                <div class="input-group">

                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Search products, collections, categories or blogs..."
                        required
                    >

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >
                        Search
                    </button>

                </div>

            </form>

        </div>

    </section>


    {{-- Search Results --}}

    <main class="results-area">

        <div class="container">

            @if($search === '')

                <div class="no-results">

                    <h2>Start Searching</h2>

                    <p>
                        Search for products, collections,
                        categories or blog posts.
                    </p>

                </div>

            @elseif($totalResults === 0)

                <div class="no-results">

                    <h2>No Results Found</h2>

                    <p>
                        We could not find anything matching
                        “{{ $search }}”.
                    </p>

                </div>

            @else

                {{-- Products --}}

                @if($products->isNotEmpty())

                    <section class="result-section">

                        <h2 class="result-heading">
                            Products ({{ $products->count() }})
                        </h2>

                        <div class="row g-4">

                            @foreach($products as $product)

                                @php
                                    $productImage =
                                        optional(
                                            $product->images->first()
                                        )->image
                                        ?? $product->image
                                        ?? null;

                                    $productImageUrl = $resolveImage(
                                        $productImage,
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
                                        [
                                            'identifier' =>
                                                $product->uuid ?: $product->id
                                        ]
                                    ) }}"
                                        class="result-card"
                                    >

                                        <div class="image-wrapper">

                                            <img
                                                src="{{ $productImageUrl }}"
                                                alt="{{ $product->name }}"
                                                class="result-image"
                                                onerror="this.onerror=null; this.src='{{ asset('users/images/no-image.png') }}';"
                                            >

                                        </div>

                                        <div class="result-content">

                                            <div class="result-type">
                                                Product
                                            </div>

                                            <h3 class="result-name">
                                                {{ $product->name }}
                                            </h3>

                                            <p class="result-description">
                                                {{ \Illuminate\Support\Str::limit(
                                                    strip_tags($product->description ?? ''),
                                                    85
                                                ) }}
                                            </p>

                                            <div class="result-price">
                                                Rs.
                                                {{ number_format($product->price, 2) }}
                                            </div>

                                        </div>

                                    </a>

                                </div>

                            @endforeach

                        </div>

                    </section>

                @endif


                {{-- Collections --}}

                @if($collections->isNotEmpty())

                    <section class="result-section">

                        <h2 class="result-heading">
                            Collections ({{ $collections->count() }})
                        </h2>

                        <div class="row g-4">

                            @foreach($collections as $collection)

                                @php
                                    $collectionImageUrl = $resolveImage(
                                        $collection->image ?? null,
                                        [
                                            'uploads/collections',
                                            'storage/collections',
                                        ]
                                    );

                                    $collectionIdentifier =
                                        $collection->uuid
                                        ?: $collection->id;
                                @endphp

                                <div class="col-lg-4 col-md-6">

                                    <a
                                        href="{{ route(
                                            'collection.details',
                                            ['uuid' => $collectionIdentifier]
                                        ) }}"
                                        class="result-card"
                                    >

                                        <div class="image-wrapper">

                                            <img
                                                src="{{ $collectionImageUrl }}"
                                                alt="{{ $collection->name }}"
                                                class="result-image"
                                                onerror="this.onerror=null; this.src='{{ asset('users/images/no-image.png') }}';"
                                            >

                                        </div>

                                        <div class="result-content">

                                            <div class="result-type">
                                                Collection
                                            </div>

                                            <h3 class="result-name">
                                                {{ $collection->name }}
                                            </h3>

                                            <p class="result-description">
                                                {{ \Illuminate\Support\Str::limit(
                                                    strip_tags($collection->description ?? ''),
                                                    100
                                                ) }}
                                            </p>

                                        </div>

                                    </a>

                                </div>

                            @endforeach

                        </div>

                    </section>

                @endif


                {{-- Categories --}}

                @if($categories->isNotEmpty())

                    <section class="result-section">

                        <h2 class="result-heading">
                            Categories ({{ $categories->count() }})
                        </h2>

                        <div class="row g-4">

                            @foreach($categories as $category)

                                @php
                                    $categoryImageUrl = $resolveImage(
                                        $category->image ?? null,
                                        [
                                            'uploads/categories',
                                            'storage/categories',
                                        ]
                                    );

                                    $categoryIdentifier =
                                        $category->uuid
                                        ?: $category->id;
                                @endphp

                                <div class="col-lg-4 col-md-6">

                                    <a
                                        href="{{ route(
                                            'shop.category',
                                            ['uuid' => $categoryIdentifier]
                                        ) }}"
                                        class="result-card"
                                    >

                                        <div class="image-wrapper">

                                            <img
                                                src="{{ $categoryImageUrl }}"
                                                alt="{{ $category->name }}"
                                                class="result-image"
                                                onerror="this.onerror=null; this.src='{{ asset('users/images/no-image.png') }}';"
                                            >

                                        </div>

                                        <div class="result-content">

                                            <div class="result-type">
                                                Category
                                            </div>

                                            <h3 class="result-name">
                                                {{ $category->name }}
                                            </h3>

                                            <p class="result-description">
                                                {{ \Illuminate\Support\Str::limit(
                                                    strip_tags($category->description ?? ''),
                                                    100
                                                ) }}
                                            </p>

                                        </div>

                                    </a>

                                </div>

                            @endforeach

                        </div>

                    </section>

                @endif


                {{-- Blogs --}}

                @if($blogs->isNotEmpty())

                    <section class="result-section">

                        <h2 class="result-heading">
                            Blogs ({{ $blogs->count() }})
                        </h2>

                        <div class="row g-4">

                            @foreach($blogs as $blog)

                                @php
                                    $blogImageUrl = $resolveImage(
                                        $blog->image ?? null,
                                        [
                                            'uploads/blogs',
                                            'storage/blogs',
                                        ]
                                    );

                                    $blogIdentifier =
                                        $blog->uuid ?: $blog->id;
                                @endphp

                                <div class="col-lg-4 col-md-6">

                                    <a
                                        href="{{ route(
                                            'home.blog.details',
                                            ['identifier' => $blogIdentifier]
                                        ) }}"
                                        class="result-card"
                                    >

                                        <div class="image-wrapper">

                                            <img
                                                src="{{ $blogImageUrl }}"
                                                alt="{{ $blog->title ?? $blog->name }}"
                                                class="result-image"
                                                onerror="this.onerror=null; this.src='{{ asset('users/images/no-image.png') }}';"
                                            >

                                        </div>

                                        <div class="result-content">

                                            <div class="result-type">
                                                Blog
                                            </div>

                                            <h3 class="result-name">
                                                {{ $blog->title ?? $blog->name }}
                                            </h3>

                                            <p class="result-description">
                                                {{ \Illuminate\Support\Str::limit(
                                                    strip_tags($blog->description ?? ''),
                                                    100
                                                ) }}
                                            </p>

                                        </div>

                                    </a>

                                </div>

                            @endforeach

                        </div>

                    </section>

                @endif

            @endif

        </div>

    </main>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>

</html>
