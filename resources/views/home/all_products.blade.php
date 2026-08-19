

<div class="container mt-5">

    <h2 class="text-center mb-5">
        All Products
    </h2>

    <div class="row">

        @foreach($products as $product)

        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

            <div class="card shadow h-100">

                @if($product->images->count())

                <img src="{{ asset('uploads/products/'.$product->images->first()->image) }}"
                     class="card-img-top"
                     style="height:250px;object-fit:cover;">

                @else

                <img src="{{ asset('images/no-image.png') }}"
                     class="card-img-top"
                     style="height:250px;object-fit:cover;">

                @endif

                <div class="card-body text-center">

                    <h5>{{ $product->name }}</h5>

                    <h4 class="text-success">
                        Rs {{ $product->price }}
                    </h4>

                    <a href="{{ route('product.details',$product->uuid) }}"
                       class="btn btn-dark w-100">

                        View Details

                    </a>

                </div>

            </div>

        </div>

        @endforeach

    </div>

    <div class="mt-4 d-flex justify-content-center">

        {{ $products->links() }}

    </div>

</div>

