<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collections - Kaira</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-white">

<div class="container py-5 mt-4">
    @if(isset($collection))
        <!-- Professional Immersive Collection Hero Banner with Image -->
        <div class="position-relative rounded-4 overflow-hidden mb-5 shadow-sm bg-dark text-white p-4 p-md-5 d-flex align-items-center" style="min-height: 320px; background: linear-gradient(135deg, #111 0%, #222 100%);">

            @php
                $activeImage = $collection->image ?? $collection->photo ?? $collection->banner ?? null;
            @endphp

            @if($activeImage)
                <!-- Background Image with Overlay for Professional Depth -->
                <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="opacity: 0.35;">
                    <img src="{{ asset('storage/' . $activeImage) }}" class="w-100 h-100 object-fit-cover" alt="{{ $collection->name }}">
                </div>
            @endif

            <div class="position-relative z-index-2 w-100">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3 text-uppercase font-monospace small text-light opacity-75">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-light">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ Route::has('collections') ? route('collections') : url('/collections') }}" class="text-decoration-none text-light">Collections</a></li>
                        <li class="breadcrumb-item active text-white fw-bold" aria-current="page">{{ $collection->name }}</li>
                    </ol>
                </nav>
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-semibold mb-3 text-uppercase tracking-wider small shadow-sm">
                            Exclusive Collection Showcase
                        </span>
                        <h1 class="fw-bold display-5 mb-2 text-white">{{ $collection->name }}</h1>
                        <p class="text-light opacity-90 lead fs-6 mb-0" style="max-width: 600px;">
                            {{ $collection->description ?? 'Explore our handpicked curation designed to elevate your everyday style with unmatched elegance.' }}
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        <div class="d-inline-block bg-dark bg-opacity-75 backdrop-blur px-4 py-3 rounded-4 border border-light border-opacity-25 text-center shadow">
                            <span class="d-block text-uppercase small opacity-75 font-monospace">Status</span>
                            <span class="fs-4 fw-bold text-warning">Coming Soon</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coming Soon Professional Banner Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center py-5 bg-light rounded-4 border border-dashed shadow-sm">
                @if($activeImage)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $activeImage) }}" class="rounded-circle shadow-sm object-fit-cover" style="width: 120px; height: 120px;" alt="{{ $collection->name }}">
                    </div>
                @else
                    <div class="mb-3">
                        <i class="bi bi-clock-history text-dark display-3"></i>
                    </div>
                @endif
                <h3 class="fw-bold text-dark mb-2">Exclusive Curation Coming Soon</h3>
                <p class="text-muted px-4 mb-4">
                    We are currently crafting and organizing the finest pieces for the <strong>{{ $collection->name }}</strong> collection. Stay tuned for the official launch!
                </p>
                <a href="{{ Route::has('collections') ? route('collections') : url('/collections') }}" class="btn btn-dark rounded-pill px-5 py-2 fw-semibold">
                    <i class="bi bi-arrow-left me-2"></i> Back to All Collections
                </a>
            </div>
        </div>
    @else
        <!-- All Collections List View -->
        <div class="row mb-5 align-items-center bg-light p-4 rounded-4 shadow-sm border-0">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2 text-uppercase font-monospace small">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Home</a></li>
                        <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Collections</li>
                    </ol>
                </nav>
                <h1 class="fw-bold display-6 mb-1 text-dark">
                    @if(request('search'))
                        Search Results for: <span class="text-danger">"{{ request('search') }}"</span>
                    @else
                        All Collections
                    @endif
                </h1>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-dark px-3 py-2 rounded-pill fs-6 fw-normal">
                    Total Collections: {{ method_exists($collections, 'total') ? $collections->total() : $collections->count() }}
                </span>
            </div>
        </div>

        <!-- Collections Grid Layout -->
        <div class="row g-4">
            @if($collections->count() > 0)
                @foreach($collections as $col)
                    @php
                        $colImage = $col->image ?? $col->photo ?? $col->banner ?? null;
                    @endphp
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 collection-card bg-white overflow-hidden">
                            @if($colImage)
                                <div class="position-relative bg-light" style="height: 240px; width: 100%; overflow: hidden;">
                                    <img src="{{ asset('storage/' . $colImage) }}" class="w-100 h-100 object-fit-cover transition-zoom" alt="{{ $col->name }}">
                                    <span class="badge bg-dark text-white position-absolute top-0 start-0 m-3 shadow-sm px-3 py-2 rounded-pill fw-semibold small">
                                        Coming Soon
                                    </span>
                                </div>
                            @else
                                <div class="position-relative bg-light d-flex align-items-center justify-content-center" style="height: 240px; width: 100%;">
                                    <i class="bi bi-collection text-muted fs-1"></i>
                                </div>
                            @endif
                            <div class="card-body p-4 d-flex flex-column">
                                <h4 class="card-title fw-bold text-dark fs-5 mb-2">{{ $col->name }}</h4>
                                <p class="card-text text-muted small mb-4">{{ Str::limit($col->description ?? 'Explore this exclusive trending collection.', 80) }}</p>
                                <div class="mt-auto">
                                    <a href="{{ Route::has('collection.details') ? route('collection.details', $col->uuid) : url('/collection/' . $col->uuid) }}" class="btn btn-dark w-100 rounded-pill py-2 fw-semibold shadow-none">
                                        Discover Collection <i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center py-5 bg-light rounded-4 border border-dashed">
                    <div class="mb-3">
                        <i class="bi bi-search text-muted display-4"></i>
                    </div>
                    <h4 class="fw-bold text-dark">No Collections Found for "{{ request('search') }}"</h4>
                    <p class="text-muted mb-4">We couldn't find any collections matching your search criteria.</p>
                    <a href="{{ Route::has('collections') ? route('collections') : url('/collections') }}" class="btn btn-dark rounded-pill px-4 py-2">View All Collections</a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ method_exists($collections, 'links') ? $collections->links() : '' }}
        </div>
    @endif
</div>

<style>
    .transition-zoom {
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .collection-card:hover .transition-zoom {
        transform: scale(1.08);
    }
    .collection-card {
        transition: all 0.3s ease;
    }
    .collection-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.08) !important;
    }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
