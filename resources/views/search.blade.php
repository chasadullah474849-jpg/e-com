
<div class="container py-5">

    <!-- SEARCH BAR SECTION -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 text-center">
            <form action="{{ route('search') }}" method="GET">
                <div class="input-group input-group-lg border-bottom border-dark">
                    <input
                        type="text"
                        name="s"
                        class="form-control border-0 bg-transparent shadow-none fs-3 text-center"
                        placeholder="Type and press enter"
                        value="{{ $searchQuery ?? '' }}"
                        autofocus
                    >
                    <button class="btn border-0 bg-transparent" type="submit">
                        <i class="fa-solid fa-magnifying-glass fs-3"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DYNAMIC CATEGORIES -->
    <div class="row text-center mb-5">
        <div class="col-12">
            <p class="text-uppercase text-muted small fw-bold tracking-widest mb-3">BROWSE CATEGORIES</p>
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 fs-3 fw-light">
                @forelse($categories as $category)
                    <a href="{{ route('search', ['s' => $category->name]) }}" class="text-decoration-none text-dark hover-primary">
                        {{ $category->name }}
                    </a>
                    @if(!$loop->last) <span class="text-muted">/</span> @endif
                @empty
                    <span class="fs-6 text-muted">No categories available.</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- SEARCH RESULTS SECTION -->
    @if(isset($searchQuery) && $searchQuery != '')
        <hr class="my-5">
        <div class="row">
            <div class="col-12 mb-4">
                <h4>Search Results for: <span class="text-primary">"{{ $searchQuery }}"</span></h4>
            </div>

            <!-- PRODUCT RESULTS -->
            @forelse($products as $product)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm text-center">
                        <a href="{{ url('product/details/' . $product->id) }}">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 230px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top" alt="{{ $product->name }}" style="height: 230px; object-fit: cover;">
                            @endif
                        </a>
                        <div class="card-body d-flex flex-column text-center">
                            <h5 class="card-title fs-6">
                                <a href="{{ url('product/details/' . $product->id) }}" class="text-decoration-none text-dark">
                                    {{ $product->name }}
                                </a>
                            </h5>
                            <p class="card-text fw-bold text-dark mt-auto">${{ number_format($product->price, 2) }}</p>
                            <a href="{{ url('product/details/' . $product->id) }}" class="btn btn-outline-dark btn-sm rounded-0 text-uppercase">
                                View Product
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted fs-5">No items found for "{{ $searchQuery }}".</p>
                </div>
            @endforelse

            <!-- BLOG RESULTS -->
            @if(isset($blogs) && $blogs->count() > 0)
                <div class="col-12 mt-5 mb-3">
                    <h4>Matching Blog Posts</h4>
                </div>
                @foreach($blogs as $blog)
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm p-3">
                            <h5>
                                <a href="{{ url('blog/details/' . $blog->id) }}" class="text-decoration-none text-dark">
                                    {{ $blog->title }}
                                </a>
                            </h5>
                            <a href="{{ url('blog/details/' . $blog->id) }}" class="btn btn-link p-0 text-uppercase">Read More &rarr;</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @endif

</div>
