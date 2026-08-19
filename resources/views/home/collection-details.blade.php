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
        {{ $collection->name }} | Kaira
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #fff;
            color: #111;
            font-family: Arial, sans-serif;
        }

        /* HERO */

        .collection-hero {
            padding: 80px 0;
        }

        .collection-hero-image {
            width: 100%;
            height: 650px;
            object-fit: cover;
        }

        .collection-info {
            padding: 50px;
        }

        .collection-info h1 {
            font-family: Georgia, serif;
            font-size: 55px;
            font-weight: 400;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 25px;
        }

        .collection-info p {
            color: #777;
            font-size: 18px;
            line-height: 1.8;
        }

        .category-label {
            display: inline-block;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 13px;
            color: #888;
        }

        /* CATEGORY IMAGE */

        .category-section {
            padding: 80px 0;
            background: #f8f8f8;
        }

        .category-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .category-title {
            font-family: Georgia, serif;
            font-size: 42px;
            font-weight: 400;
            margin-bottom: 20px;
        }

        /* PRODUCTS */

        .products-section {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            font-family: Georgia, serif;
            font-size: 45px;
            font-weight: 400;
            text-transform: uppercase;
            margin-bottom: 50px;
        }

        .product-card {
            text-decoration: none;
            color: #111;
            display: block;
        }

        .product-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            background: #f4f4f4;
            transition: transform 0.5s ease;
        }

        .product-image-wrapper {
            overflow: hidden;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-name {
            font-size: 19px;
            margin-top: 18px;
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 17px;
            color: #777;
        }

        .back-btn {
            display: inline-block;
            padding: 12px 25px;
            border: 1px solid #111;
            color: #111;
            text-decoration: none;
            margin-top: 25px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #111;
            color: white;
        }

        @media (max-width: 768px) {

            .collection-hero {
                padding: 40px 0;
            }

            .collection-hero-image {
                height: 450px;
            }

            .collection-info {
                padding: 30px 10px;
            }

            .collection-info h1 {
                font-size: 38px;
            }

            .category-image {
                height: 350px;
            }

            .section-title {
                font-size: 35px;
            }

        }

    </style>

</head>

<body>


{{-- ========================================================= --}}
{{-- COLLECTION HERO --}}
{{-- ========================================================= --}}

<section class="collection-hero">

    <div class="container">

        <div class="row align-items-center g-5">

            {{-- Collection Image --}}
            <div class="col-lg-7">

                @if($collection->image)

                    <img
                        src="{{ asset('uploads/collections/' . $collection->image) }}"
                        alt="{{ $collection->name }}"
                        class="collection-hero-image"
                    >

                @elseif($collection->category && $collection->category->image)

                    <img
                        src="{{ asset('uploads/categories/' . $collection->category->image) }}"
                        alt="{{ $collection->category->name }}"
                        class="collection-hero-image"
                    >

                @else

                    <div
                        class="collection-hero-image d-flex align-items-center justify-content-center bg-light"
                    >
                        No Image Available
                    </div>

                @endif

            </div>


            {{-- Collection Information --}}
            <div class="col-lg-5">

                @if($collection->category)

                    <span class="category-label">
                        {{ $collection->category->name }}
                    </span>

                @endif

                <h1>
                    {{ $collection->name }}
                </h1>

                @if($collection->description)

                    <p>
                        {{ $collection->description }}
                    </p>

                @endif

                <a
                    href="{{ url('/') }}"
                    class="back-btn"
                >
                    ← Back to Home
                </a>

            </div>

        </div>

    </div>

</section>


{{-- ========================================================= --}}
{{-- CATEGORY IMAGE --}}
{{-- ========================================================= --}}

@if($collection->category)

<section class="category-section">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                @if($collection->category->image)

                    <img
                        src="{{ asset('uploads/categories/' . $collection->category->image) }}"
                        alt="{{ $collection->category->name }}"
                        class="category-image"
                    >

                @endif

            </div>

            <div class="col-lg-6">

                <h2 class="category-title">
                    {{ $collection->category->name }}
                </h2>

                @if($collection->category->description)

                    <p class="text-muted fs-5">
                        {{ $collection->category->description }}
                    </p>

                @endif

            </div>

        </div>

    </div>

</section>

@endif


{{-- ========================================================= --}}
{{-- PRODUCTS --}}
{{-- ========================================================= --}}

@if($collection->category)

<section class="products-section">

    <div class="container">

        <h2 class="section-title">
            {{ $collection->name }} Products
        </h2>

        <div class="row g-4">

            @php
                $products = collect();

                foreach ($collection->category->subcategories as $subcategory) {
                    foreach ($subcategory->products as $product) {
                        $products->push($product);
                    }
                }

                $products = $products->unique('id');
            @endphp


            @forelse($products as $product)

                <div class="col-lg-3 col-md-4 col-sm-6">

                    <a
                        href="{{ route('product.details', $product->uuid) }}"
                        class="product-card"
                    >

                        <div class="product-image-wrapper">

                            @if($product->images->first())

                                <img
                                    src="{{ asset('uploads/products/' . $product->images->first()->image) }}"
                                    alt="{{ $product->name }}"
                                    class="product-image"
                                >

                            @else

                                <div
                                    class="product-image d-flex align-items-center justify-content-center"
                                >
                                    No Image
                                </div>

                            @endif

                        </div>

                        <div class="product-name">
                            {{ $product->name }}
                        </div>

                        <div class="product-price">
                            Rs. {{ number_format($product->price, 2) }}
                        </div>

                    </a>

                </div>

            @empty

                <div class="col-12 text-center">

                    <h4>
                        No products available in this collection.
                    </h4>

                </div>

            @endforelse

        </div>

    </div>

</section>

@endif


</body>

</html>
