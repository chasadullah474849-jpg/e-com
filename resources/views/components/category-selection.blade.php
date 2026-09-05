<div class="container py-5">
    <div class="row g-4">

        {{-- CARD 1: MEN PERFUMES --}}
        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="category-item text-center">
                <div class="image-holder">
                    <a href="{{ url('/subcategory/men-perfumes') }}">
                        <img
                            src="{{ asset('storage/categories/DNTgVYv8YROkhrh6mSL.jpg') }}"
                            alt="Men Perfumes"
                            class="img-fluid"
                            style="width: 100%; height: 420px; object-fit: cover; display: block;"
                        >
                    </a>
                </div>
                <div class="category-content pt-3">
                    <h3 class="category-title text-uppercase fs-5 fw-bold">
                        <a href="{{ url('/subcategory/men-perfumes') }}" class="text-decoration-none text-dark">
                            MEN PERFUMES
                        </a>
                    </h3>
                </div>
            </div>
        </div>

        {{-- CARD 2: SHOP FOR WOMEN --}}
        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="category-item text-center">
                <div class="image-holder">
                    <a href="{{ url('/subcategory/women') }}">
                        <img
                            src="{{ asset('users/images/cat-item2.jpg') }}"
                            alt="Shop For Women"
                            class="img-fluid"
                            style="width: 100%; height: 420px; object-fit: cover; display: block;"
                        >
                    </a>
                </div>
                <div class="category-content pt-3">
                    <h3 class="category-title text-uppercase fs-5 fw-bold">
                        <a href="{{ url('/subcategory/women') }}" class="text-decoration-none text-dark">
                            SHOP FOR WOMEN
                        </a>
                    </h3>
                </div>
            </div>
        </div>

        {{-- CARD 3: SHOP FOR MEN --}}
        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
            <div class="category-item text-center">
                <div class="image-holder">
                    <a href="{{ url('/subcategory/men') }}">
                        <img
                            src="{{ asset('users/images/cat-item3.jpg') }}"
                            alt="Shop For Men"
                            class="img-fluid"
                            style="width: 100%; height: 420px; object-fit: cover; display: block;"
                        >
                    </a>
                </div>
                <div class="category-content pt-3">
                    <h3 class="category-title text-uppercase fs-5 fw-bold">
                        <a href="{{ url('/subcategory/men') }}" class="text-decoration-none text-dark">
                            SHOP FOR MEN
                        </a>
                    </h3>
                </div>
            </div>
        </div>

    </div>
</div>
