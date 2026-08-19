<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout | Complete Your Order</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .order-summary-card {
            position: sticky;
            top: 20px;
        }

        /* Fixed Avatar Container to prevent broken image alt text bleed */
        .product-avatar-box {
            width: 50px;
            height: 50px;
            min-width: 50px;
            min-height: 50px;
            border-radius: 50%;
            overflow: hidden;
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .product-avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: 50%;
        }

        /* Quantity controls styling */
        .qty-btn {
            width: 24px;
            height: 24px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border-radius: 50%;
        }

        .qty-input {
            width: 38px;
            text-align: center;
            border: none;
            background: transparent;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="fw-bold">Checkout</h1>
            <p class="text-muted">Please review your items and complete your shipping details.</p>
        </div>

        <form action="{{ Route::has('checkout.store') ? route('checkout.store') : url('/place-order') }}" method="POST">
            @csrf
            <div class="row g-4">

                <!-- Left Column: Billing Details & Payment -->
                <div class="col-lg-7 col-xl-8">

                    <!-- Billing Address Card -->
                    <div class="card border-0 shadow-sm mb-4 rounded-4">
                        <div class="card-body p-4">
                            <h4 class="card-title fw-bold mb-4">
                                <i class="fa-solid fa-location-dot me-2 text-primary"></i> Billing & Shipping Address
                            </h4>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="first_name" value="{{ old('first_name') }}" required>
                                </div>

                                <div class="col-sm-6">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="last_name" value="{{ old('last_name') }}" required>
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required>
                                </div>

                                <div class="col-12">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+1234567890" value="{{ old('phone') }}" required>
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label">Street Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" value="{{ old('address') }}" required>
                                </div>

                                <div class="col-12">
                                    <label for="address2" class="form-label">Address 2 <span class="text-muted">(Optional)</span></label>
                                    <input type="text" class="form-control" id="address2" name="address_2" placeholder="Apartment, suite, or unit" value="{{ old('address_2') }}">
                                </div>

                                <div class="col-md-5">
                                    <label for="country" class="form-label">Country</label>
                                    <select class="form-select" id="country" name="country" required>
                                        <option value="">Choose...</option>
                                        <option value="Pakistan" {{ old('country') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                        <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                                        <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                                </div>

                                <div class="col-md-3">
                                    <label for="zip" class="form-label">Zip / Postal Code</label>
                                    <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip') }}" required>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="same-address" name="same_address" value="1" checked>
                                <label class="form-check-label" for="same-address">Shipping address is the same as my billing address</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="save-info" name="save_info" value="1">
                                <label class="form-check-label" for="save-info">Save this information for next time</label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Options Card -->
                    <div class="card border-0 shadow-sm mb-4 rounded-4">
                        <div class="card-body p-4">
                            <h4 class="card-title fw-bold mb-4">
                                <i class="fa-solid fa-credit-card me-2 text-primary"></i> Payment Method
                            </h4>

                            <div class="my-3">
                                <div class="form-check mb-3">
                                    <input id="credit" name="payment_method" type="radio" class="form-check-input payment-method-radio" value="credit_card" checked required>
                                    <label class="form-check-label fw-semibold" for="credit">Credit / Debit Card</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input id="cod" name="payment_method" type="radio" class="form-check-input payment-method-radio" value="cod" required>
                                    <label class="form-check-label fw-semibold" for="cod">Cash on Delivery (COD)</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input id="paypal" name="payment_method" type="radio" class="form-check-input payment-method-radio" value="paypal" required>
                                    <label class="form-check-label fw-semibold" for="paypal">PayPal</label>
                                </div>
                            </div>

                            <!-- Dynamic Payment Content Container -->
                            <div class="mt-4 pt-3 border-top">

                                <!-- Credit Card Input Fields -->
                                <div id="credit-card-details" class="payment-details-box">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="cc-name" class="form-label">Name on Card</label>
                                            <input type="text" class="form-control" id="cc-name" name="cc_name" placeholder="Full name as displayed on card">
                                        </div>

                                        <div class="col-12">
                                            <label for="cc-number" class="form-label">Credit / Debit Card Number</label>
                                            <input type="text" class="form-control" id="cc-number" name="cc_number" placeholder="xxxx xxxx xxxx xxxx" maxlength="19">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cc-expiration" class="form-label">Expiration (MM/YY)</label>
                                            <input type="text" class="form-control" id="cc-expiration" name="cc_expiration" placeholder="MM/YY" maxlength="5">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cc-cvv" class="form-label">CVV / CVC</label>
                                            <input type="password" class="form-control" id="cc-cvv" name="cc_cvv" placeholder="123" maxlength="4">
                                        </div>
                                    </div>
                                </div>

                                <!-- Cash on Delivery Info Box -->
                                <div id="cod-details" class="payment-details-box d-none">
                                    <div class="alert alert-info border-0 rounded-3 mb-0">
                                        <i class="fa-solid fa-truck-fast me-2"></i>
                                        Pay with cash upon delivery of your items. No advance payment required.
                                    </div>
                                </div>

                                <!-- PayPal Info Box -->
                                <div id="paypal-details" class="payment-details-box d-none">
                                    <div class="alert alert-warning border-0 rounded-3 mb-0">
                                        <i class="fa-brands fa-paypal me-2"></i>
                                        You will be redirected to PayPal to complete your purchase securely after placing the order.
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

                <!-- Right Column: Order Summary -->
                <div class="col-lg-5 col-xl-4">
                    <div class="card border-0 shadow-sm order-summary-card rounded-4">
                        <div class="card-body p-4">
                            @php
                                $items = $cartItems ?? $cart ?? [];
                                $computedSubtotal = 0;
                            @endphp

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-bold mb-0">Order Summary</h4>
                                <span class="badge bg-primary rounded-pill px-3 py-2 fs-6" id="cart-count">
                                    {{ is_array($items) || $items instanceof \Countable ? count($items) : 0 }}
                                </span>
                            </div>

                            <!-- Product List Loop -->
                            <div class="order-items-list mb-3">
                                @forelse($items as $key => $item)
                                    @php
                                        $itemId = is_object($item) ? ($item->id ?? $key) : ($item['id'] ?? $key);
                                        $itemName = is_object($item) ? ($item->name ?? 'Product') : ($item['name'] ?? 'Product');
                                        $itemQty = is_object($item) ? ($item->quantity ?? 1) : ($item['quantity'] ?? 1);

                                        $rawPrice = is_object($item) ? ($item->price ?? 0) : ($item['price'] ?? 0);
                                        $itemPrice = (float) preg_replace('/[^\d.]/', '', $rawPrice);
                                        $lineTotal = $itemPrice * $itemQty;
                                        $computedSubtotal += $lineTotal;

                                        $rawImg = is_object($item) ? ($item->image ?? $item->image_path ?? null) : ($item['image'] ?? $item['image_path'] ?? null);

                                        // SVG Fallback Icon
                                        $placeholder = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' viewBox='0 0 24 24' fill='%236c757d'><path d='M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z'/></svg>";

                                        if (!empty($rawImg)) {
                                            if (filter_var($rawImg, FILTER_VALIDATE_URL) || str_starts_with($rawImg, 'http')) {
                                                $imgSrc = $rawImg;
                                            } elseif (str_starts_with($rawImg, 'storage/')) {
                                                $imgSrc = asset($rawImg);
                                            } elseif (str_starts_with($rawImg, 'images/') || str_starts_with($rawImg, 'uploads/')) {
                                                $imgSrc = asset($rawImg);
                                            } else {
                                                $imgSrc = asset('storage/' . ltrim($rawImg, '/'));
                                            }
                                        } else {
                                            $imgSrc = $placeholder;
                                        }
                                    @endphp

                                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom cart-item-row" data-id="{{ $itemId }}" data-unit-price="{{ $itemPrice }}">
                                        <div class="d-flex align-items-center gap-3">

                                            <!-- Image Wrapper -->
                                            <div class="product-avatar-box shadow-sm flex-shrink-0">
                                                <img
                                                    src="{{ $imgSrc }}"
                                                    alt=""
                                                    onerror="this.onerror=null; this.src='{{ $placeholder }}';"
                                                >
                                            </div>

                                            <!-- Item Title & Interactive Stock Buttons -->
                                            <div>
                                                <h6 class="mb-1 text-dark fw-semibold" style="max-width: 120px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                                    {{ $itemName }}
                                                </h6>

                                                <!-- Dynamic Quantity Controls -->
                                                <div class="d-flex align-items-center bg-light border rounded-pill px-1 py-1">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary qty-btn btn-minus">-</button>
                                                    <input type="text" class="qty-input" value="{{ $itemQty }}" readonly>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary qty-btn btn-plus">+</button>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- Live Price Display -->
                                        <div class="text-end fw-bold text-dark">
                                            $<span class="item-line-total">{{ number_format($lineTotal, 2) }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-3 text-muted text-center">
                                        No items in cart.
                                    </div>
                                @endforelse
                            </div>

                            @php
                                $finalSubtotal = $subtotal ?? $computedSubtotal;
                                $shippingFee = $shipping ?? 0;
                                $grandTotal = $total ?? ($finalSubtotal + $shippingFee);
                            @endphp

                            <!-- Live Totals -->
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold">$<span id="summary-subtotal">{{ number_format($finalSubtotal, 2) }}</span></span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping Fee</span>
                                <span class="fw-semibold text-success">
                                    {{ $shippingFee > 0 ? '$' . number_format($shippingFee, 2) : 'Free' }}
                                </span>
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="h5 mb-0 fw-bold">Total Amount</span>
                                <strong class="h4 mb-0 text-primary">$<span id="summary-total">{{ number_format($grandTotal, 2) }}</span></strong>
                            </div>

                            <!-- Submit Button -->
                            <button class="w-100 btn btn-primary btn-lg fw-bold py-3" type="submit">
                                Place Order Now
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Payment Toggling & Quantity AJAX Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            // --- Payment Option Dynamic Display Logic ---
            const paymentRadios = document.querySelectorAll('.payment-method-radio');
            const creditCardBox = document.getElementById('credit-card-details');
            const codBox = document.getElementById('cod-details');
            const paypalBox = document.getElementById('paypal-details');

            const ccInputs = creditCardBox.querySelectorAll('input');

            function togglePaymentFields() {
                const selectedMethod = document.querySelector('.payment-method-radio:checked')?.value;

                // Hide all boxes initially
                creditCardBox.classList.add('d-none');
                codBox.classList.add('d-none');
                paypalBox.classList.add('d-none');

                // Remove required attribute from CC inputs
                ccInputs.forEach(input => input.removeAttribute('required'));

                if (selectedMethod === 'credit_card') {
                    creditCardBox.classList.remove('d-none');
                    ccInputs.forEach(input => input.setAttribute('required', 'required'));
                } else if (selectedMethod === 'cod') {
                    codBox.classList.remove('d-none');
                } else if (selectedMethod === 'paypal') {
                    paypalBox.classList.remove('d-none');
                }
            }

            paymentRadios.forEach(radio => radio.addEventListener('change', togglePaymentFields));
            togglePaymentFields(); // Initial run on page load

            // --- Quantity Updating Logic ---
            document.querySelectorAll('.cart-item-row').forEach(row => {
                const itemId = row.getAttribute('data-id');
                const qtyInput = row.querySelector('.qty-input');
                const btnPlus = row.querySelector('.btn-plus');
                const btnMinus = row.querySelector('.btn-minus');

                if (btnPlus && btnMinus) {
                    btnPlus.addEventListener('click', function () {
                        let currentQty = parseInt(qtyInput.value) || 1;
                        updateCartQuantity(itemId, currentQty + 1, row);
                    });

                    btnMinus.addEventListener('click', function () {
                        let currentQty = parseInt(qtyInput.value) || 1;
                        if (currentQty > 1) {
                            updateCartQuantity(itemId, currentQty - 1, row);
                        }
                    });
                }
            });

            function updateCartQuantity(itemId, newQuantity, rowElement) {
                const updateUrl = "{{ Route::has('cart.update-quantity') ? route('cart.update-quantity') : url('/cart/update-quantity') }}";

                fetch(updateUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        rowElement.querySelector('.qty-input').value = newQuantity;

                        const unitPrice = parseFloat(rowElement.getAttribute('data-unit-price')) || 0;
                        const formattedLineTotal = data.item_total ?? (unitPrice * newQuantity).toFixed(2);

                        rowElement.querySelector('.item-line-total').innerText = Number(formattedLineTotal).toLocaleString('en-US', {minimumFractionDigits: 2});

                        if (data.subtotal) {
                            document.getElementById('summary-subtotal').innerText = data.subtotal;
                        }
                        if (data.total) {
                            document.getElementById('summary-total').innerText = data.total;
                        }
                        if (data.cart_count !== undefined) {
                            document.getElementById('cart-count').innerText = data.cart_count;
                        }

                        recalculateClientTotals();
                    }
                })
                .catch(error => console.error('Error updating cart:', error));
            }

            function recalculateClientTotals() {
                let grandTotal = 0;
                document.querySelectorAll('.cart-item-row').forEach(row => {
                    const unitPrice = parseFloat(row.getAttribute('data-unit-price')) || 0;
                    const qty = parseInt(row.querySelector('.qty-input').value) || 1;
                    grandTotal += (unitPrice * qty);
                });

                const formattedTotal = Number(grandTotal.toFixed(2)).toLocaleString('en-US', {minimumFractionDigits: 2});
                document.getElementById('summary-subtotal').innerText = formattedTotal;
                document.getElementById('summary-total').innerText = formattedTotal;
            }
        });
    </script>
</body>
</html>
