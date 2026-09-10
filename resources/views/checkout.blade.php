<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>Checkout | Kaira</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <style>

        body {
            background: #f7f8fa;
            color: #222;
            font-family: Arial, sans-serif;
        }

        .checkout-wrapper {
            max-width: 1050px;
            margin: 40px auto;
            padding: 0 15px;
        }

        .checkout-title {
            text-align: center;
            margin-bottom: 35px;
        }

        .checkout-title h1 {
            font-size: 30px;
            font-weight: 700;
        }

        .checkout-title p {
            color: #777;
            margin-bottom: 0;
        }

        .checkout-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .06);
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #0d6efd;
        }

        label {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .form-control,
        .form-select {
            min-height: 40px;
            border-radius: 7px;
            border: 1px solid #dce0e5;
            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 .15rem rgba(13, 110, 253, .10);
        }


        /* =====================================================
           ORDER SUMMARY
        ===================================================== */

        .summary-card {
            position: sticky;
            top: 20px;
        }

        .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .summary-header h4 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .summary-count {
            background: #0d6efd;
            color: #fff;
            width: 27px;
            height: 27px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 700;
        }


        /* =====================================================
           PRODUCT ROW
        ===================================================== */

        .order-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #e5e5e5;
        }

        .product-image-wrapper {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: 50%;
            overflow: hidden;
            background: #eef1f4;
            border: 1px solid #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .product-placeholder {
            font-size: 22px;
            color: #8a929a;
        }

        .order-item-info {
            flex: 1;
            min-width: 0;
        }

        .product-name {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-quantity {
            color: #777;
            font-size: 12px;
            margin-top: 3px;
        }

        .product-price {
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }


        /* =====================================================
           TOTAL
        ===================================================== */

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
        }

        .total-row.shipping span:last-child {
            color: #198754;
            font-weight: 600;
        }

        .grand-total {
            border-top: 1px solid #ddd;
            margin-top: 8px;
            padding-top: 15px;
            display: flex;
            justify-content: space-between;
            font-size: 17px;
            font-weight: 700;
        }

        .grand-total span:last-child {
            color: #0d6efd;
        }


        /* =====================================================
           PAYMENT
        ===================================================== */

        .payment-box {
            background: #dff6ff;
            border-radius: 7px;
            padding: 14px;
            border: 1px solid #b8e8f8;
            margin-bottom: 12px;
            transition: border-color .2s, background .2s, box-shadow .2s;
        }

        .payment-box.selected {
            border-color: #0d6efd;
            background: #eef6ff;
            box-shadow: 0 0 0 3px rgba(13,110,253,.08);
        }

        .card-payment-panel {
            display: none;
            margin-top: 14px;
            padding: 18px;
            border: 1px solid #e0e5eb;
            border-radius: 10px;
            background: linear-gradient(145deg,#fff,#f7f9fc);
        }

        .card-payment-panel.show { display: block; animation: cardOpen .22s ease; }
        @keyframes cardOpen { from {opacity:0;transform:translateY(-6px)} to {opacity:1;transform:none} }
        .card-number-wrap { position: relative; }
        .card-number-wrap .form-control { padding-right: 72px; }
        .card-brand { position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:12px;font-weight:700;color:#0d6efd;letter-spacing:.5px; }
        .accepted-cards { display:flex;gap:7px;align-items:center;color:#667085;font-size:12px; }
        .accepted-cards i { font-size:25px;color:#344054; }
        .secure-note { display:flex;gap:7px;align-items:flex-start;margin-top:12px;color:#667085;font-size:11px; }
        .secure-note i { color:#198754;font-size:14px; }

        .payment-box label {
            cursor: pointer;
            width: 100%;
        }

        .payment-title {
            font-weight: 700;
            font-size: 13px;
        }

        .payment-description {
            color: #666;
            font-size: 11px;
        }


        /* =====================================================
           PLACE ORDER
        ===================================================== */

        .place-order-btn {
            width: 100%;
            border: 0;
            background: #0d6efd;
            color: white;
            padding: 13px;
            border-radius: 7px;
            font-weight: 700;
            font-size: 14px;
            margin-top: 15px;
            transition: .2s;
        }

        .place-order-btn:hover {
            background: #0b5ed7;
        }

        .place-order-btn:disabled {
            opacity: .7;
            cursor: not-allowed;
        }


        /* =====================================================
           ALERTS
        ===================================================== */

        .alert {
            border-radius: 8px;
            font-size: 13px;
        }

        @media(max-width: 991px) {

            .summary-card {
                position: static;
            }

        }

    </style>

</head>


<body>


<div class="checkout-wrapper">


    {{-- =====================================================
         TITLE
    ====================================================== --}}

    <div class="checkout-title">

        <h1>Checkout</h1>

        <p>
            Please review your items and complete your shipping details.
        </p>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- =====================================================
         ERROR MESSAGE
    ====================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix these errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        id="checkoutForm"
        method="POST"
        action="{{ route('checkout.submit') }}"
    >

        @csrf


        <div class="row g-3">


            {{-- =================================================
                 LEFT SIDE
            ================================================== --}}

            <div class="col-lg-8">


                {{-- =================================================
                     BILLING / SHIPPING
                ================================================== --}}

                <div class="checkout-card">

                    <div class="section-title">

                        <i class="bi bi-geo-alt-fill"></i>

                        Billing & Shipping Address

                    </div>


                    <div class="row g-3">


                        {{-- FIRST NAME --}}

                        <div class="col-md-6">

                            <label for="first_name">
                                First Name
                            </label>

                            <input
                                type="text"
                                id="first_name"
                                name="first_name"
                                class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name') }}"
                                required
                            >

                            @error('first_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- LAST NAME --}}

                        <div class="col-md-6">

                            <label for="last_name">
                                Last Name
                            </label>

                            <input
                                type="text"
                                id="last_name"
                                name="last_name"
                                class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name') }}"
                                required
                            >

                            @error('last_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- EMAIL --}}

                        <div class="col-12">

                            <label for="email">
                                Email Address
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                required
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- PHONE --}}

                        <div class="col-12">

                            <label for="phone">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}"
                                placeholder="+92XXXXXXXXXX"
                                required
                            >

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- ADDRESS --}}

                        <div class="col-12">

                            <label for="address">
                                Street Address
                            </label>

                            <input
                                type="text"
                                id="address"
                                name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                value="{{ old('address') }}"
                                required
                            >

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- ADDRESS 2 --}}

                        <div class="col-12">

                            <label for="address2">
                                Address 2 (Optional)
                            </label>

                            <input
                                type="text"
                                id="address2"
                                name="address2"
                                class="form-control"
                                value="{{ old('address2') }}"
                                placeholder="Apartment, suite, or unit"
                            >

                        </div>


                        {{-- COUNTRY --}}

                        <div class="col-md-4">

                            <label for="country">
                                Country
                            </label>

                            <select
                                id="country"
                                name="country"
                                class="form-select @error('country') is-invalid @enderror"
                                required
                            >

                                <option value="Pakistan"
                                    {{ old('country', 'Pakistan') == 'Pakistan' ? 'selected' : '' }}>
                                    Pakistan
                                </option>

                            </select>

                            @error('country')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- CITY --}}

                        <div class="col-md-4">

                            <label for="city">
                                City
                            </label>

                            <input
                                type="text"
                                id="city"
                                name="city"
                                class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city') }}"
                                required
                            >

                            @error('city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- ZIP --}}

                        <div class="col-md-4">

                            <label for="zip">
                                Zip / Postal Code
                            </label>

                            <input
                                type="text"
                                id="zip"
                                name="zip"
                                class="form-control @error('zip') is-invalid @enderror"
                                value="{{ old('zip') }}"
                                required
                            >

                            @error('zip')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     PAYMENT
                ================================================== --}}

                <div class="checkout-card">

                    <div class="section-title">

                        <i class="bi bi-credit-card-fill"></i>

                        Payment Method

                    </div>


                    <div class="payment-box">

                        <label
                            for="cod"
                            class="d-flex align-items-start gap-2"
                        >

                            <input
                                type="radio"
                                id="cod"
                                name="payment_method"
                                value="cash_on_delivery"
                                {{ old('payment_method', 'cash_on_delivery') === 'cash_on_delivery' ? 'checked' : '' }}
                            >

                            <div>

                                <div class="payment-title">
                                    Cash on Delivery (COD)
                                </div>

                                <div class="payment-description">
                                    Pay with cash upon delivery of your items.
                                    No advance payment required.
                                </div>

                            </div>

                        </label>

                    </div>

                    <div class="payment-box" id="cardPaymentBox">
                        <label for="credit_card" class="d-flex align-items-start gap-2">
                            <input type="radio" id="credit_card" name="payment_method" value="credit_debit_card" {{ old('payment_method') === 'credit_debit_card' ? 'checked' : '' }}>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <div class="payment-title">Credit or Debit Card</div>
                                    <div class="accepted-cards" aria-label="Accepted cards">
                                        <i class="bi bi-credit-card-2-front-fill"></i>
                                        <span>VISA · Mastercard</span>
                                    </div>
                                </div>
                                <div class="payment-description">Enter your card details securely to pay online.</div>
                            </div>
                        </label>

                        <div class="card-payment-panel" id="cardPaymentPanel" aria-hidden="true">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="card_holder">Name on Card</label>
                                    <input type="text" id="card_holder" name="card_holder" class="form-control" value="{{ old('card_holder') }}" placeholder="Asad Arif" autocomplete="cc-name" maxlength="80">
                                    <div class="invalid-feedback">Enter the cardholder name.</div>
                                </div>
                                <div class="col-12">
                                    <label for="card_number">Card Number</label>
                                    <div class="card-number-wrap">
                                        <input type="text" id="card_number" name="card_number" class="form-control" inputmode="numeric" autocomplete="cc-number" placeholder="1234 5678 9012 3456" maxlength="19">
                                        <span class="card-brand" id="cardBrand">CARD</span>
                                        <div class="invalid-feedback">Enter a valid card number.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="card_expiry">Expiry Date</label>
                                    <input type="text" id="card_expiry" name="card_expiry" class="form-control" inputmode="numeric" autocomplete="cc-exp" placeholder="MM/YY" maxlength="5">
                                    <div class="invalid-feedback">Enter a valid future expiry date.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="card_cvv">CVV</label>
                                    <input type="password" id="card_cvv" name="card_cvv" class="form-control" inputmode="numeric" autocomplete="cc-csc" placeholder="123" maxlength="4">
                                    <div class="invalid-feedback">Enter a valid 3 or 4 digit CVV.</div>
                                </div>
                            </div>
                            <div class="secure-note"><i class="bi bi-shield-lock-fill"></i><span>Demo card form. Connect a PCI-compliant payment gateway before accepting real card details.</span></div>
                        </div>
                    </div>

                </div>

            </div>



            {{-- =================================================
                 RIGHT SIDE
            ================================================== --}}

            <div class="col-lg-4">


                <div class="checkout-card summary-card">


                    <div class="summary-header">

                        <h4>
                            Order Summary
                        </h4>

                        <span class="summary-count">
                            {{ collect($cartItems)->sum('quantity') }}
                        </span>

                    </div>


                    {{-- =================================================
                         PRODUCTS
                    ================================================== --}}

                    @foreach($cartItems as $item)

                        @php

                            $image = $item['image'] ?? null;

                            if ($image) {

                                if (
                                    \Illuminate\Support\Str::startsWith(
                                        $image,
                                        ['http://', 'https://']
                                    )
                                ) {

                                    $imageUrl = $image;

                                } else {

                                    $imageUrl = asset(
                                        'uploads/products/' .
                                        ltrim($image, '/')
                                    );

                                }

                            } else {

                                $imageUrl = null;

                            }

                        @endphp


                        <div class="order-item">


                            {{-- PRODUCT IMAGE --}}

                            <div class="product-image-wrapper">

                                @if($imageUrl)

                                    <img
                                        src="{{ $imageUrl }}"
                                        alt="{{ $item['name'] }}"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                    >

                                    <div
                                        class="product-placeholder"
                                        style="display:none;"
                                    >
                                        <i class="bi bi-image"></i>
                                    </div>

                                @else

                                    <div class="product-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>

                                @endif

                            </div>


                            {{-- PRODUCT INFORMATION --}}

                            <div class="order-item-info">

                                <div class="product-name">
                                    {{ $item['name'] }}
                                </div>

                                <div class="product-quantity">

                                    Qty:
                                    {{ $item['quantity'] }}

                                </div>

                            </div>


                            {{-- ITEM TOTAL --}}

                            <div class="product-price">

                                Rs
                                {{ number_format($item['item_total'], 2) }}

                            </div>

                        </div>

                    @endforeach



                    {{-- =================================================
                         SUBTOTAL
                    ================================================== --}}

                    <div class="total-row mt-3">

                        <span>
                            Subtotal
                        </span>

                        <span>
                            Rs {{ number_format($subtotal, 2) }}
                        </span>

                    </div>


                    {{-- SHIPPING --}}

                    <div class="total-row shipping">

                        <span>
                            Shipping Fee
                        </span>

                        <span>
                            Free
                        </span>

                    </div>


                    {{-- TOTAL --}}

                    <div class="grand-total">

                        <span>
                            Total Amount
                        </span>

                        <span>
                            Rs {{ number_format($total, 2) }}
                        </span>

                    </div>


                    {{-- =================================================
                         PLACE ORDER
                    ================================================== --}}

                    <button
                        type="submit"
                        id="placeOrderButton"
                        class="place-order-btn"
                    >

                        <span id="buttonText">
                            Place Order Now
                        </span>

                        <span
                            id="buttonSpinner"
                            class="spinner-border spinner-border-sm ms-2"
                            style="display:none;"
                        ></span>

                    </button>


                </div>

            </div>

        </div>

    </form>

</div>


<script>

    const checkoutForm = document.getElementById('checkoutForm');
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const cardPanel = document.getElementById('cardPaymentPanel');
    const cardHolder = document.getElementById('card_holder');
    const cardNumber = document.getElementById('card_number');
    const cardExpiry = document.getElementById('card_expiry');
    const cardCvv = document.getElementById('card_cvv');
    const cardBrand = document.getElementById('cardBrand');

    function cardSelected() { return document.getElementById('credit_card').checked; }
    function updatePaymentUI() {
        document.querySelectorAll('.payment-box').forEach(box => box.classList.remove('selected'));
        const checked = document.querySelector('input[name="payment_method"]:checked');
        if (checked) checked.closest('.payment-box').classList.add('selected');
        cardPanel.classList.toggle('show', cardSelected());
        cardPanel.setAttribute('aria-hidden', cardSelected() ? 'false' : 'true');
        [cardHolder, cardNumber, cardExpiry, cardCvv].forEach(field => field.required = cardSelected());
    }
    paymentRadios.forEach(radio => radio.addEventListener('change', updatePaymentUI));
    updatePaymentUI();

    cardNumber.addEventListener('input', function () {
        const digits = this.value.replace(/\D/g, '').slice(0, 16);
        this.value = digits.replace(/(.{4})/g, '$1 ').trim();
        cardBrand.textContent = digits.startsWith('4') ? 'VISA' : /^5[1-5]/.test(digits) ? 'MC' : 'CARD';
        this.classList.remove('is-invalid');
    });
    cardExpiry.addEventListener('input', function () {
        const digits = this.value.replace(/\D/g, '').slice(0, 4);
        this.value = digits.length > 2 ? digits.slice(0,2) + '/' + digits.slice(2) : digits;
        this.classList.remove('is-invalid');
    });
    cardCvv.addEventListener('input', function () { this.value = this.value.replace(/\D/g, '').slice(0,4);this.classList.remove('is-invalid'); });

    function luhn(number) {
        let sum=0,doubleDigit=false;
        for(let i=number.length-1;i>=0;i--){let digit=Number(number[i]);if(doubleDigit){digit*=2;if(digit>9)digit-=9}sum+=digit;doubleDigit=!doubleDigit}
        return number.length>=13 && number.length<=16 && sum%10===0;
    }
    function validExpiry(value) {
        const match=value.match(/^(0[1-9]|1[0-2])\/(\d{2})$/);if(!match)return false;
        const expiry=new Date(2000+Number(match[2]),Number(match[1]),0,23,59,59);return expiry>=new Date();
    }
    function validateCard() {
        if(!cardSelected())return true;
        const tests=[[cardHolder,cardHolder.value.trim().length>=2],[cardNumber,luhn(cardNumber.value.replace(/\D/g,''))],[cardExpiry,validExpiry(cardExpiry.value)],[cardCvv,/^\d{3,4}$/.test(cardCvv.value)]];
        tests.forEach(([field,valid])=>field.classList.toggle('is-invalid',!valid));
        return tests.every(([,valid])=>valid);
    }

    checkoutForm.addEventListener('submit', function (event) {
            if (!validateCard()) { event.preventDefault();cardPanel.scrollIntoView({behavior:'smooth',block:'center'});return; }

            const button =
                document.getElementById('placeOrderButton');

            const text =
                document.getElementById('buttonText');

            const spinner =
                document.getElementById('buttonSpinner');


            button.disabled = true;

            text.innerText =
                'Processing Order...';

            spinner.style.display =
                'inline-block';

        });

</script>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>
