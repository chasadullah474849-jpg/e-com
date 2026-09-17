<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | Kaira</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="card border-0 shadow-sm mx-auto text-center" style="max-width:620px">
            <div class="card-body p-5">
                <div class="display-3 text-success mb-3">✓</div>
                <h1 class="h3 fw-bold">Order Confirmed</h1>
                <p class="text-secondary mt-3">
                    {{ session('success', 'Thank you. Your order has been placed successfully.') }}
                </p>

                @if ($orderId)
                    <div class="alert alert-light border mt-4">
                        Order reference: <strong>#{{ $orderId }}</strong>
                    </div>
                @endif

                <a href="{{ url('/') }}" class="btn btn-primary mt-3 px-4">
                    Continue Shopping
                </a>
            </div>
        </div>
    </main>
</body>
</html>
