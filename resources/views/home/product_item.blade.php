<div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm border-0 product-card">
        <!-- Product Image -->
        <div class="position-relative overflow-hidden">
            @php
                $imagePath = $product->image ?? ($product->primary_image ?? null);
            @endphp

            @if($imagePath)
                <img src="{{ Str::startsWith($imagePath, 'http') ? $imagePath : asset('storage/' . $imagePath) }}"
                     onerror="this.onerror=null;this.src='{{ asset('uploads/products/' . $imagePath) }}';"
                     class="card-img-top product-image"
                     alt="{{ $product->title ?? $product->name }}"
                     style="height: 250px; object-fit: cover;">
            @else
                <img src="https://via.placeholder.com/300x250?text=No+Image"
                     class="card-img-top product-image"
                     alt="Placeholder"
                     style="height: 250px; object-fit: cover;">
            @endif
        </div>

        <!-- Product Details -->
        <div class="card-body d-flex flex-column justify-content-between text-center">
            <div>
                <h5 class="card-title text-uppercase fw-bold text-truncate" style="font-size: 1.1rem;">
                    {{ $product->title ?? $product->name }}
                </h5>
                <p class="card-text text-muted fs-6 mb-2">
                    ${{ number_format($product->price, 2) }}
                </p>
            </div>

            <!-- Add to Cart Action -->
            <div class="mt-3">
                <button type="button"
                        class="btn btn-dark w-100 text-uppercase add-to-cart-btn"
                        data-id="{{ $product->id }}">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>
