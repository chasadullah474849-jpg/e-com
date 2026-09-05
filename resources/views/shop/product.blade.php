<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products - Kaira</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5 mt-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-4 rounded-4 shadow-sm">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 text-uppercase font-monospace small">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Products</li>
                </ol>
            </nav>
            <h2 class="fw-bold m-0 text-uppercase">All Products</h2>
        </div>
        <div>
            <span class="badge bg-dark px-3 py-2 rounded-pill fs-6 fw-normal">
                Total Products: {{ method_exists($products, 'total') ? $products->total() : $products->count() }}
            </span>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        @forelse($products as $product)
            @php
                $imagePath = $product->image ?? $product->thumbnail ?? null;
                if ($imagePath) {
                    $imagePath = ltrim($imagePath, '/');
                    $finalImage = str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/' . $imagePath);
                } else {
                    $finalImage = asset('users/images/no-image.png');
                }
            @endphp

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="position-relative overflow-hidden" style="height: 320px;">
                        <a href="{{ route('shop.product', $product->id) }}">
                            <img src="{{ $finalImage }}" class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                        </a>
                    </div>
                    <div class="card-body d-flex flex-column p-4">
                        <h5 class="card-title fw-bold text-uppercase mb-2">
                            <a href="{{ route('shop.product', $product->id) }}" class="text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                        </h5>
                        <p class="text-danger fw-bold fs-5 mb-3">
                            Rs. {{ number_format($product->price, 2) }}
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('shop.product', $product->id) }}" class="btn btn-dark w-100 rounded-pill py-2 fw-semibold">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                <i class="bi bi-box-seam text-muted display-3 mb-3 d-block"></i>
                <h4 class="fw-bold text-dark">No Products Available</h4>
                <p class="text-muted mb-4">Check back later for new arrivals.</p>
                <a href="{{ url('/') }}" class="btn btn-dark rounded-pill px-4 py-2">Back to Home</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-5">
        {{ method_exists($products, 'links') ? $products->links() : '' }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
