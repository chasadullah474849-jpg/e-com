@php
    $cart = session('cart', []);
    $total = 0;
    $cartCount = 0;

    foreach ($cart as $item) {
        $price = (float) ($item['price'] ?? 0);
        $quantity = (int) ($item['quantity'] ?? 1);
        $total += $price * $quantity;
        $cartCount += $quantity;
    }
@endphp

<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasCart" aria-labelledby="My Cart">
    <div class="offcanvas-header justify-content-between border-bottom">
        <h5 class="offcanvas-title" id="My Cart">Your Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Selected Items</span>
                <span class="badge bg-primary rounded-pill" id="cart-count-badge">{{ $cartCount }}</span>
            </h4>

            <ul class="list-group mb-3" id="cart-item-list">
                @forelse($cart as $key => $details)
                    @php
                        $itemTotal = ($details['price'] ?? 0) * ($details['quantity'] ?? 1);
                    @endphp
                    <li class="list-group-item d-flex justify-content-between lh-sm align-items-center" id="cart-item-{{ $key }}">
                        <div class="d-flex align-items-center">
                            @if(!empty($details['image']))
                                <img src="{{ Str::startsWith($details['image'], 'http') ? $details['image'] : asset('storage/' . $details['image']) }}"
                                     onerror="this.onerror=null;this.src='{{ asset('uploads/products/' . $details['image']) }}';"
                                     alt="{{ $details['name'] ?? 'Product' }}"
                                     style="width: 48px; height: 48px; object-fit: cover; margin-right: 12px;" class="rounded">
                            @endif
                            <div>
                                <h6 class="my-0">{{ $details['name'] ?? 'Product' }}</h6>
                                <small class="text-body-secondary">Qty: {{ $details['quantity'] ?? 1 }} &times; ${{ number_format($details['price'] ?? 0, 2) }}</small>
                            </div>
                        </div>
                        <span class="text-body-secondary fw-semibold">${{ number_format($itemTotal, 2) }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-center py-4 text-muted" id="empty-cart-msg">
                        Your cart is empty.
                    </li>
                @endforelse

                @if(!empty($cart))
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (USD)</span>
                        <strong id="cart-total-price">${{ number_format($total, 2) }}</strong>
                    </li>
                @endif
            </ul>

            @if(!empty($cart))
                <a href="{{ url('/checkout') }}" class="w-100 btn btn-primary btn-lg">Continue to Checkout</a>
            @else
                <a href="{{ url('/shop') }}" class="w-100 btn btn-outline-primary btn-lg">Explore Shop</a>
            @endif
        </div>
    </div>
</div>
