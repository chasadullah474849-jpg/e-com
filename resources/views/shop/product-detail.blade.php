<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $product->name }}</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <style>

        .main-product-image {

            width: 100%;
            height: 600px;

            object-fit: cover;

            background: #f5f5f5;

        }


        .product-title {

            font-size: 36px;
            font-weight: 500;

        }


        .product-price {

            font-size: 25px;
            font-weight: 600;

        }


        .thumbnail {

            width: 90px;
            height: 110px;

            object-fit: cover;

            cursor: pointer;

            border: 1px solid #ddd;

        }


        .add-cart-btn {

            border-radius: 0;

            padding: 14px 40px;

        }

    </style>


</head>


<body>


<div class="container py-5">


    <div class="row g-5">


        {{-- ======================= --}}
        {{-- LEFT SIDE --}}
        {{-- ======================= --}}

        <div class="col-lg-6">


            @if($product->images->count() > 0)


                @php

                    $firstImage =
                        $product->images->first();

                @endphp


                <img
                    id="mainImage"

                    src="{{ asset(
                        'storage/' .
                        $firstImage->image
                    ) }}"

                    class="main-product-image"

                    alt="{{ $product->name }}"
                >



                {{-- THUMBNAILS --}}

                @if($product->images->count() > 1)


                    <div class="d-flex gap-2 mt-3">


                        @foreach($product->images as $image)


                            <img
                                src="{{ asset(
                                    'storage/' .
                                    $image->image
                                ) }}"

                                class="thumbnail"

                                onclick="
                                    document
                                    .getElementById('mainImage')
                                    .src=this.src
                                "

                                alt="{{ $product->name }}"
                            >


                        @endforeach


                    </div>


                @endif


            @elseif($product->image)


                <img
                    src="{{ asset(
                        'storage/' .
                        $product->image
                    ) }}"

                    class="main-product-image"

                    alt="{{ $product->name }}"
                >


            @else


                <div
                    class="
                        main-product-image
                        d-flex
                        align-items-center
                        justify-content-center
                        text-muted
                    "
                >

                    Image not available

                </div>


            @endif


        </div>



        {{-- ======================= --}}
        {{-- RIGHT SIDE --}}
        {{-- ======================= --}}

        <div class="col-lg-6">


            @if($product->category)

                <p class="text-muted text-uppercase">

                    {{ $product->category->name }}

                </p>

            @endif


            <h1 class="product-title mb-3">

                {{ $product->name }}

            </h1>


            <div class="product-price mb-4">

                Rs.
                {{ number_format($product->price) }}

            </div>


            @if($product->description)

                <p class="text-muted mb-4">

                    {{ $product->description }}

                </p>

            @endif



            @if(isset($product->stock))


                <p>

                    <strong>
                        Availability:
                    </strong>


                    @if($product->stock > 0)

                        <span class="text-success">
                            In Stock
                        </span>

                    @else

                        <span class="text-danger">
                            Out of Stock
                        </span>

                    @endif


                </p>


            @endif



            <div class="mb-4">


                <label class="form-label">

                    Quantity

                </label>


                <input
                    type="number"
                    value="1"
                    min="1"

                    class="form-control"

                    style="width:100px;"
                >


            </div>



            <button
                class="btn btn-dark add-cart-btn"
            >

                ADD TO CART

            </button>



            <div class="mt-4">


                <a
                    href="{{ url()->previous() }}"
                    class="text-dark"
                >

                    ← Back to Collection

                </a>


            </div>


        </div>


    </div>


</div>


</body>

</html>
