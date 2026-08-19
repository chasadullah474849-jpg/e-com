<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Placed Successfully</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card border-0 shadow-sm p-4">
                    <div class="card-body">
                        <div class="mb-3 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l4.992-5.5a.75.75 0 0 0-.018-1.042z"/>
                            </svg>
                        </div>
                        <h2 class="fw-bold mb-3">Order Confirmed!</h2>
                        <p class="text-muted mb-4">
                            {{ session('success') ?? 'Thank you! Your order has been recorded successfully.' }}
                        </p>
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Return to Store</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
