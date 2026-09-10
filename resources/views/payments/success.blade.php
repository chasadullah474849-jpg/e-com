<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Payment Status</title>

    <style>
        body {
            margin: 0;
            padding: 50px 15px;
            background: #f4f6f8;
            font-family: Arial, sans-serif;
        }

        .status-card {
            width: 100%;
            max-width: 560px;
            margin: auto;
            padding: 35px;
            border-radius: 12px;
            background: #ffffff;
            text-align: center;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
        }

        .success {
            color: #198754;
        }

        .processing {
            color: #d97706;
        }

        .failed {
            color: #dc3545;
        }

        .details {
            margin-top: 25px;
            padding: 18px;
            border-radius: 8px;
            background: #f8f9fa;
            text-align: left;
        }

        .home-button {
            display: inline-block;
            margin-top: 24px;
            padding: 12px 24px;
            border-radius: 6px;
            background: #212529;
            color: #ffffff;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="status-card">

    @if($payment->status === 'succeeded')

        <h1 class="success">
            Payment Successful
        </h1>

        <p>
            Thank you. Your card payment was completed.
        </p>

    @elseif(
        in_array(
            $payment->status,
            [
                'processing',
                'requires_capture',
                'requires_action'
            ]
        )
    )

        <h1 class="processing">
            Payment Processing
        </h1>

        <p>
            Your payment is still being processed.
        </p>

    @else

        <h1 class="failed">
            Payment Not Completed
        </h1>

        <p>
            Current status:
            {{ ucfirst(
                str_replace('_', ' ', $payment->status)
            ) }}
        </p>

    @endif

    <div class="details">

        <p>
            <strong>Payment ID:</strong>
            {{ $payment->stripe_payment_intent_id }}
        </p>

        <p>
            <strong>Customer:</strong>
            {{ $payment->customer_name }}
        </p>

        <p>
            <strong>Email:</strong>
            {{ $payment->customer_email }}
        </p>

        <p>
            <strong>Amount:</strong>
            {{ strtoupper($payment->currency) }}
            {{ number_format($payment->amount / 100, 2) }}
        </p>

        <p>
            <strong>Status:</strong>
            {{ ucfirst(
                str_replace('_', ' ', $payment->status)
            ) }}
        </p>

    </div>

    <a href="{{ route('home') }}" class="home-button">
        Return Home
    </a>

</div>

</body>
</html>
