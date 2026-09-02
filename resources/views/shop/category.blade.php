

<section class="product-store py-5">
    <div class="container">

        {{-- CATEGORY TITLE --}}
        <div class="row">
            <div class="col-12">
                <div class="section-header mb-5">
                    <h2 class="section-title text-uppercase">
                        {{ $category->name }}
                    </h2>
                </div>
            </div>
        </div>


        {{-- PRODUCTS ROW --}}
        <div class="row g-4">

            @forelse($products as $product)

                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">

                    <div class="product-item">

                        {{-- PRODUCT IMAGE --}}
                        <div class="image-holder">

                            <a href="{{ route('shop.product', $product->id) }}">

                                @php

                                    $imagePath = null;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | CHECK PRODUCT IMAGES RELATION
                                    |--------------------------------------------------------------------------
                                    */

                                    if (
                                        isset($product->images) &&
                                        $product->images->count() > 0
                                    ) {

                                        $firstImage = $product->images->first();

                                        // Try common database column names
                                        $imagePath =
                                            $firstImage->image
                                            ?? $firstImage->image_path
                                            ?? $firstImage->path
                                            ?? $firstImage->filename
                                            ?? null;
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | CHECK IMAGE DIRECTLY ON PRODUCTS TABLE
                                    |--------------------------------------------------------------------------
                                    */

                                    if (!$imagePath) {

                                        $imagePath =
                                            $product->image
                                            ?? $product->image_path
                                            ?? $product->thumbnail
                                            ?? null;
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | BUILD FINAL URL
                                    |--------------------------------------------------------------------------
                                    */

                                    $finalImage = null;

                                    if ($imagePath) {

                                        // Remove leading slash
                                        $imagePath = ltrim($imagePath, '/');

                                        if (
                                            str_starts_with($imagePath, 'http://') ||
                                            str_starts_with($imagePath, 'https://')
                                        ) {

                                            $finalImage = $imagePath;

                                        } elseif (str_starts_with($imagePath, 'storage/')) {

                                            $finalImage = asset($imagePath);

                                        } elseif (str_starts_with($imagePath, 'products/')) {

                                            $finalImage = asset('storage/' . $imagePath);

                                        } else {

                                            $finalImage = asset('storage/' . $imagePath);

                                        }
                                    }

                                @endphp


                                @if($finalImage)

                                    <img
                                        src="{{ $finalImage }}"
                                        alt="{{ $product->name }}"
                                        class="img-fluid"
                                        style="
                                            width:100%;
                                            height:420px;
                                            object-fit:cover;
                                            display:block;
                                        "
                                    >

                                @else

                                    <img
                                        src="{{ asset('users/images/no-image.png') }}"
                                        alt="No Image Available"
                                        class="img-fluid"
                                        style="
                                            width:100%;
                                            height:420px;
                                            object-fit:cover;
                                            display:block;
                                        "
                                    >

                                @endif

                            </a>

                        </div>


                        {{-- PRODUCT INFORMATION --}}
                        <div class="product-detail pt-3">

                            <h3 class="product-title text-uppercase">

                                <a
                                    href="{{ route('shop.product', $product->id) }}"
                                    class="text-decoration-none text-dark"
                                >
                                    {{ $product->name }}
                                </a>

                            </h3>


                            <div class="item-price mb-2">

                                Rs. {{ number_format($product->price, 2) }}

                            </div>


                            <a
                                href="{{ route('shop.product', $product->id) }}"
                                class="btn btn-dark"
                            >
                                View Details
                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="text-center py-5">

                        <h4>
                            No products found in {{ $category->name }}
                        </h4>

                    </div>

                </div>

            @endforelse

        </div>

    </div>
</section>

