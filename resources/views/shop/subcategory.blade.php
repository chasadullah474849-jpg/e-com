
<section class="single-product-section py-5">
    <div class="container">
        <div class="row align-items-center">

            {{-- LEFT SIDE: PRODUCT IMAGE --}}
            <div class="col-md-6 mb-4">
                <div class="product-image-container text-center">
                    <img
                        src="{{ asset('storage/categories/DNTgVYv8YROkhrh6mSL.jpg') }}"
                        alt="Men Perfumes"
                        class="img-fluid w-100"
                        style="max-height: 550px; object-fit: cover;"
                    >
                </div>
            </div>

            {{-- RIGHT SIDE: PRODUCT DETAILS --}}
            <div class="col-md-6 ps-md-5">
                <span class="text-uppercase text-muted small fw-bold tracking-wider">
                    PERFUME COLLECTION
                </span>

                <h1 class="display-4 text-uppercase fw-bold mt-2 mb-3">
                    MEN PERFUMES
                </h1>

                <p class="text-secondary mb-4 fs-6" style="line-height: 1.8;">
                    A luxury blend of long-lasting, aromatic notes crafted for modern sophistication. Designed to make a distinct statement wherever you go.
                </p>

                <div class="product-price h3 fw-bold mb-4 text-dark">
                    RS. 2,000.00
                </div>

                {{-- QUANTITY & ADD TO CART --}}
                <div class="d-flex align-items-center gap-3">
                    <input
                        type="number"
                        value="1"
                        min="1"
                        class="form-control text-center rounded-0"
                        style="width: 70px; height: 48px;"
                    >
                    <button class="btn btn-dark btn-lg px-4 text-uppercase fw-semibold rounded-0">
                        ADD TO CART
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>
