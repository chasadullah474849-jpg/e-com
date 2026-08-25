<style>
    /* =========================================================
   MINI CART
========================================================= */

.mini-cart-wrapper {
    position: relative;
}


/* Cart Dropdown */

.mini-cart-dropdown {
    position: absolute;
    top: 42px;
    right: 0;
    width: 380px;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 10px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    z-index: 9999;
    display: none;
}


/* Open */

.mini-cart-dropdown.show {
    display: block;
}


/* Header */

.mini-cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px;
    border-bottom: 1px solid #eee;
}

.mini-cart-header span {
    font-size: 12px;
    color: #777;
}


/* Items */

.mini-cart-items {
    max-height: 350px;
    overflow-y: auto;
}


/* Item */

.mini-cart-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-bottom: 1px solid #f1f1f1;
}


/* Image */

.mini-cart-image {
    width: 65px;
    height: 70px;
    overflow: hidden;
    border-radius: 6px;
    background: #f5f5f5;
    flex-shrink: 0;
}

.mini-cart-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}


/* Information */

.mini-cart-info {
    flex: 1;
}

.mini-cart-info h6 {
    margin: 0 0 6px;
    font-size: 14px;
    font-weight: 600;
}

.mini-cart-price {
    font-size: 13px;
    color: #666;
}


/* Remove */

.mini-cart-remove {
    border: 0;
    background: transparent;
    font-size: 22px;
    color: #777;
    cursor: pointer;
}

.mini-cart-remove:hover {
    color: #dc3545;
}


/* Footer */

.mini-cart-footer {
    padding: 16px;
}


/* Total */

.mini-cart-total {
    display: flex;
    justify-content: space-between;
    margin-bottom: 14px;
}

.mini-cart-total strong {
    font-size: 16px;
}


/* Empty */

.mini-cart-empty {
    padding: 40px 20px;
    text-align: center;
}

.empty-cart-icon {
    font-size: 35px;
    margin-bottom: 10px;
}

.mini-cart-empty p {
    color: #777;
    margin-bottom: 18px;
}


/* Mobile */

@media(max-width: 500px) {

    .mini-cart-dropdown {
        position: fixed;
        top: 70px;
        right: 10px;
        left: 10px;
        width: auto;
    }

}
</style>

<div class="mini-cart-content">

    @if(!empty($cart))

        <div class="mini-cart-header">
            <strong>Shopping Cart</strong>

            <span>
                {{ collect($cart)->sum('quantity') }}
                item(s)
            </span>
        </div>


        <div class="mini-cart-items">

            @foreach($cart as $item)

                <div
                    class="mini-cart-item"
                    data-cart-id="{{ $item['uuid'] }}"
                >

                    {{-- Product Image --}}
                    <div class="mini-cart-image">

                        @if(!empty($item['image']))

                            <img
                                src="{{ asset('uploads/products/' . $item['image']) }}"
                                alt="{{ $item['name'] }}"
                            >

                        @else

                            <img
                                src="{{ asset('images/no-image.png') }}"
                                alt="No image"
                            >

                        @endif

                    </div>


                    {{-- Product Information --}}
                    <div class="mini-cart-info">

                        <h6>
                            {{ $item['name'] }}
                        </h6>

                        <div class="mini-cart-price">

                            Rs
                            {{ number_format($item['price'], 2) }}

                            ×

                            {{ $item['quantity'] }}

                        </div>

                    </div>


                    {{-- Remove --}}
                    <button
                        type="button"
                        class="mini-cart-remove"
                        data-uuid="{{ $item['uuid'] }}"
                    >
                        ×
                    </button>

                </div>

            @endforeach

        </div>


        {{-- Total --}}
        <div class="mini-cart-footer">

            <div class="mini-cart-total">

                <span>Total</span>

                <strong>
                    Rs
                    {{ number_format(
                        collect($cart)->sum(
                            fn($item) =>
                                $item['price'] * $item['quantity']
                        ),
                        2
                    ) }}
                </strong>

            </div>


            <a
                href="{{ route('cart') }}"
                class="btn btn-dark w-100"
            >
                View Cart
            </a>

        </div>

    @else

        <div class="mini-cart-empty">

            <div class="empty-cart-icon">
                🛒
            </div>

            <p>Your cart is empty.</p>

            <a
                href="{{ url('/shop') }}"
                class="btn btn-dark"
            >
                Continue Shopping
            </a>

        </div>

    @endif

</div>
