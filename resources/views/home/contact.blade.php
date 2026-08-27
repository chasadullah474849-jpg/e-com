<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Kaira</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        .toast-container-top-right {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>
<body class="bg-light">

<!-- Top Right Floating Success Alert -->
<div class="toast-container-top-right">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg border-0 d-flex align-items-center gap-2 px-4 py-3" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>
                <strong>Success!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<div class="container my-5 py-4">
    <div class="row g-4 justify-content-center">
        <!-- Contact Information -->
        <div class="col-md-5">
            <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm h-100">
                <h3 class="fw-bold mb-4">Contact Information</h3>

                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-person fs-3 text-dark me-3"></i>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold">Name</small>
                        <span class="fs-6 fw-semibold">Kaira Support Team</span>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-telephone fs-3 text-dark me-3"></i>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold">Phone Number</small>
                        <span class="fs-6 fw-semibold">+92 300 1234567</span>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-envelope fs-3 text-dark me-3"></i>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold">Email Address</small>
                        <span class="fs-6 fw-semibold">support@kaira.com</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <i class="bi bi-geo-alt fs-3 text-dark me-3"></i>
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold">Location</small>
                        <span class="fs-6 fw-semibold">Main Boulevard, City Center</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-md-7">
            <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm">
                <h3 class="fw-bold mb-4">Send Us A Message</h3>

                <form action="{{ route('home.contact.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Your Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="+92 300 0000000">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="How can we help you?" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 py-2 rounded-pill fw-semibold">SEND MESSAGE</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Auto-dismiss toast after 4 seconds -->
<script>
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 4000);
</script>

</body>
</html>
