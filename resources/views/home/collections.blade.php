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

    <title>Collections | Kaira</title>

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
            position: relative;
            z-index: 1000;
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
        | Collections Page
        |--------------------------------------------------------------------------
        */

        .collections-page {
            padding: 70px 0 90px;
        }

        .page-header {
            margin-bottom: 70px;
            padding: 45px 55px;
            border-radius: 22px;
            background-color: #f7f8f9;
            box-shadow: 0 3px 7px rgba(0, 0, 0, 0.08);
        }

        .breadcrumb-text {
            margin-bottom: 25px;
            color: #333333;
            font-size: 16px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .breadcrumb-text a {
            color: #333333;
            text-decoration: none;
        }

        .breadcrumb-text span {
            margin: 0 12px;
        }

        .page-title {
            margin: 0;
            font-size: 58px;
            font-weight: 700;
            line-height: 1.1;
        }

        .collection-count {
            display: inline-block;
            padding: 11px 27px;
            border-radius: 50px;
            background-color: #212529;
            color: #ffffff;
            font-size: 18px;
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        .search-form {
            max-width: 500px;
            margin: 0 auto 50px;
        }

        .search-form .form-control {
            height: 50px;
            border-color: #dddddd;
            border-radius: 0;
        }

        .search-form .btn {
            min-width: 110px;
            border-radius: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Collection Cards
        |--------------------------------------------------------------------------
        */

        .collection-card {
            position: relative;
            display: block;
            height: 100%;
            overflow: hidden;
            border: 1px solid #eeeeee;
            border-radius: 22px;
            background-color: #ffffff;
            color: #212529;
            text-decoration: none;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.06);
            transition: all 0.35s ease;
        }

        .collection-card:hover {
            color: #212529;
            transform: translateY(-7px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.13);
        }

        .collection-image-wrapper {
            position: relative;
            overflow: hidden;
            background-color: #f3f3f3;
        }

        .collection-image {
            display: block;
            width: 100%;
            height: 360px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .collection-card:hover .collection-image {
            transform: scale(1.05);
        }

        .coming-soon-badge {
            position: absolute;
            top: 24px;
            left: 24px;
            z-index: 2;
            padding: 11px 24px;
            border-radius: 30px;
            background-color: #212529;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
        }

        .collection-content {
            padding: 30px 35px 35px;
        }

        .collection-title {
            margin-bottom: 13px;
            font-size: 27px;
            font-weight: 700;
            line-height: 1.3;
        }

        .collection-description {
            margin-bottom: 20px;
            color: #777777;
            font-size: 16px;
            line-height: 1.7;
        }

        .view-collection {
            display: inline-block;
            padding-bottom: 4px;
            border-bottom: 1px solid #111111;
            color: #111111;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /*
        |--------------------------------------------------------------------------
        | Empty State
        |--------------------------------------------------------------------------
        */

        .empty-collections {
            padding: 80px 20px;
            border-radius: 20px;
            background-color: #f8f8f8;
            text-align: center;
        }

        .empty-collections h3 {
            font-family: Georgia, serif;
            font-size: 38px;
            font-weight: 400;
            text-transform: uppercase;
        }

        .empty-collections p {
            color: #777777;
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        .pagination {
            justify-content: center;
            margin-top: 55px;
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
        | Footer
        |--------------------------------------------------------------------------
        */

        .footer {
            padding: 30px 0;
            border-top: 1px solid #eeeeee;
            color: #777777;
            font-size: 14px;
            text-align: center;
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

            .page-title {
                font-size: 48px;
            }

            .collection-count {
                margin-top: 25px;
            }

        }

        @media (max-width: 768px) {

            .collections-page {
                padding: 45px 0 65px;
            }

            .page-header {
                margin-bottom: 45px;
                padding: 35px 25px;
            }

            .page-title {
                font-size: 40px;
            }

            .collection-image {
                height: 330px;
            }

        }

        @media (max-width: 576px) {

            .navbar-brand {
                font-size: 24px;
            }

            .page-title {
                font-size: 34px;
            }

            .collection-image {
                height: 300px;
            }

            .collection-content {
                padding: 25px;
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
         * collections/image.jpg
         * uploads/collections/image.jpg
         * storage/collections/image.jpg
         * public/collections/image.jpg
         * Full external URL
         */
        $resolveImage = function (
            $image,
            array $possibleFolders = []
        ) {
            $fallbackImage = asset(
                'users/images/no-image.png'
            );

            if (empty($image)) {
                return $fallbackImage;
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

            return $fallbackImage;
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
                            class="nav-link"
                            href="{{ route('productss') }}"
                        >
                            Products
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link active"
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
    {{-- COLLECTIONS --}}
    {{-- ====================================================== --}}

    <main class="collections-page">

        <div class="container">

            <div class="page-header">

                <div class="row align-items-center">

                    <div class="col-lg-8">

                        <div class="breadcrumb-text">

                            <a href="{{ route('home') }}">
                                Home
                            </a>

                            <span>/</span>

                            <strong>Collections</strong>

                        </div>

                        <h1 class="page-title">
                            All Collections
                        </h1>

                    </div>

                    <div class="col-lg-4 text-lg-end">

                        <span class="collection-count">
                            Total Collections:
                            {{ $collections->total() }}
                        </span>

                    </div>

                </div>

            </div>


            {{-- Search Form --}}
            <form
                action="{{ route('collections') }}"
                method="GET"
                class="search-form"
            >

                <div class="input-group">

                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Search collections..."
                    >

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >
                        Search
                    </button>

                </div>

            </form>


            <div class="row g-4">

                @forelse($collections as $collection)

                    @php
                        $collectionImageUrl = $resolveImage(
                            $collection->image ?? null,
                            [
                                'uploads/collections',
                                'storage/collections',
                            ]
                        );

                        $collectionIdentifier =
                            !empty($collection->uuid)
                                ? $collection->uuid
                                : $collection->id;
                    @endphp

                    <div class="col-lg-4 col-md-6">

                        <a
                            href="{{ route(
                                'collection.details',
                                ['uuid' => $collectionIdentifier]
                            ) }}"
                            class="collection-card"
                        >

                            <div class="collection-image-wrapper">

                                <span class="coming-soon-badge">
                                    Coming Soon
                                </span>

                                <img
                                    src="{{ $collectionImageUrl }}"
                                    alt="{{ $collection->name }}"
                                    class="collection-image"
                                    onerror="this.onerror=null; this.src='{{ asset('users/images/no-image.png') }}';"
                                >

                            </div>

                            <div class="collection-content">

                                <h2 class="collection-title">
                                    {{ $collection->name }}
                                </h2>

                                @if(!empty($collection->description))

                                    <p class="collection-description">
                                        {{ \Illuminate\Support\Str::limit(
                                            strip_tags($collection->description),
                                            110
                                        ) }}
                                    </p>

                                @endif

                                <span class="view-collection">
                                    View Collection
                                </span>

                            </div>

                        </a>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="empty-collections">

                            <h3>
                                No Collections Found
                            </h3>

                            <p>
                                New collections will be available soon.
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>


            @if($collections->hasPages())

                <div>
                    {{ $collections->links() }}
                </div>

            @endif

        </div>

    </main>


    {{-- ====================================================== --}}
    {{-- FOOTER --}}
    {{-- ====================================================== --}}

    <footer class="footer">

        <div class="container">

            <p class="mb-0">
                &copy; {{ date('Y') }} Kaira.
                All rights reserved.
            </p>

        </div>

    </footer>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>

</html>
