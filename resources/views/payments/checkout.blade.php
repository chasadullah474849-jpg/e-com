<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        Secure Payment - {{ $product->name }}
    </title>

    <script src="https://js.stripe.com/v3/"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 15px;
            background: #f4f6f8;
            color: #212529;
            font-family: Arial, sans-serif;
        }

        .payment-container {
            width: 100%;
            max-width: 560px;
            margin: auto;
            padding: 32px;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
        }

        .payment-title {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .product-name {
            margin-bottom: 5px;
            color: #555555;
        }

        .product-price {
            margin-top: 0;
            margin-bottom: 25px;
            font-size: 22px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            min-height: 46px;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 16px;
        }

        #payment-element {
            margin: 22px 0;
        }

        .pay-button {
            width: 100%;
            min-height: 50px;
            border: 0;
            border-radius: 6px;
            background: #635bff;
            color: #ffffff;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
        }

        .pay-button:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .message {
            display: none;
            margin-top: 18px;
            padding: 12px;
            border-radius: 6px;
        }

        .message.error {
            display: block;
            background: #f8d7da;
            color: #842029;
        }

        .loading {
            display: none;
            margin: 15px 0;
            color: #555555;
            text-align: center;
        }

        .secure-text {
            margin-top: 16px;
            color: #6c757d;
            font-size: 13px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="payment-container">

    <h1 class="payment-title">
        Secure Card Payment
    </h1>

    <p class="product-name">
        {{ $product->name }}
    </p>

    <p class="product-price">
        {{ strtoupper($currency) }}
        {{ number_format($product->price, 2) }}
    </p>

    <form id="payment-form">

        <div class="form-group">
            <label
                for="customer_name"
                class="form-label"
            >
                Full Name
            </label>

            <input
                type="text"
                id="customer_name"
                class="form-control"
                maxlength="255"
                required
            >
        </div>

        <div class="form-group">
            <label
                for="customer_email"
                class="form-label"
            >
                Email Address
            </label>

            <input
                type="email"
                id="customer_email"
                class="form-control"
                maxlength="255"
                required
            >
        </div>

        <button
            type="button"
            id="continue-button"
            class="pay-button"
        >
            Continue to Card Payment
        </button>

        <div id="loading" class="loading">
            Preparing secure payment...
        </div>

        <div id="payment-section" hidden>
            <div id="payment-element"></div>

            <button
                type="submit"
                id="pay-button"
                class="pay-button"
            >
                Pay
                {{ strtoupper($currency) }}
                {{ number_format($product->price, 2) }}
            </button>
        </div>

        <div id="payment-message" class="message"></div>

        <p class="secure-text">
            Your card details are securely processed by Stripe.
        </p>

    </form>
</div>

<script>
    const stripe = Stripe(
        @json($stripeKey)
    );

    const form = document.getElementById('payment-form');
    const continueButton = document.getElementById(
        'continue-button'
    );
    const payButton = document.getElementById('pay-button');
    const paymentSection = document.getElementById(
        'payment-section'
    );
    const loading = document.getElementById('loading');
    const messageBox = document.getElementById(
        'payment-message'
    );

    let elements = null;
    let paymentReady = false;

    function showMessage(message) {
        messageBox.textContent = message;
        messageBox.className = 'message error';
    }

    function clearMessage() {
        messageBox.textContent = '';
        messageBox.className = 'message';
    }

    continueButton.addEventListener('click', async function () {
        clearMessage();

        const customerName = document
            .getElementById('customer_name')
            .value
            .trim();

        const customerEmail = document
            .getElementById('customer_email')
            .value
            .trim();

        if (!customerName || !customerEmail) {
            showMessage(
                'Please enter your name and email address.'
            );
            return;
        }

        continueButton.disabled = true;
        loading.style.display = 'block';

        try {
            const response = await fetch(
                @json(route('stripe.create-intent', $product->id)),
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        customer_name: customerName,
                        customer_email: customerEmail
                    })
                }
            );

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message ||
                    'Unable to prepare payment.'
                );
            }

            elements = stripe.elements({
                clientSecret: data.clientSecret,
                appearance: {
                    theme: 'stripe',
                    variables: {
                        colorPrimary: '#635bff',
                        borderRadius: '6px'
                    }
                }
            });

            const paymentElement = elements.create(
                'payment'
            );

            paymentElement.mount('#payment-element');

            paymentReady = true;
            paymentSection.hidden = false;
            continueButton.style.display = 'none';
        } catch (error) {
            showMessage(error.message);
            continueButton.disabled = false;
        } finally {
            loading.style.display = 'none';
        }
    });

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        clearMessage();

        if (!paymentReady || !elements) {
            showMessage(
                'Please prepare the payment form first.'
            );
            return;
        }

        payButton.disabled = true;
        payButton.textContent = 'Processing...';

        const result = await stripe.confirmPayment({
            elements: elements,
            confirmParams: {
                return_url: @json(route('stripe.success'))
            }
        });

        if (result.error) {
            showMessage(
                result.error.message ||
                'Your payment could not be completed.'
            );

            payButton.disabled = false;
            payButton.textContent =
                @json(
                    'Pay ' .
                    strtoupper($currency) .
                    ' ' .
                    number_format($product->price, 2)
                );
        }
    });
</script>

</body>
</html>
