<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Kaira</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
            color: #1a1a1a;
        }

        /* Toast Container */
        .toast-container-top-right {
            position: fixed;
            top: 25px;
            right: 25px;
            z-index: 9999;
        }

        /* Navbar Enhancements */
        .navbar-brand {
            letter-spacing: 2.5px;
        }

        .nav-link {
            font-size: 0.9rem;
            color: #4a4a4a;
            position: relative;
            padding-bottom: 6px !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #000000 !important;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 2px;
            background-color: #000000;
            border-radius: 2px;
        }

        .nav-icon-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #1a1a1a;
            transition: all 0.2s ease;
        }

        .nav-icon-btn:hover {
            background-color: #f1f3f5;
            color: #000;
        }

        /* Card Styling */
        .contact-info-card {
            background: #111111;
            color: #ffffff;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .contact-info-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .icon-box {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #ffffff;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }

        .info-item:hover .icon-box {
            transform: scale(1.08);
            background: rgba(255, 255, 255, 0.18);
        }

        .form-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Form Controls */
        .custom-input {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            background-color: #fbfbfb;
        }

        .custom-input:focus {
            background-color: #ffffff;
            border-color: #000000;
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.06);
            outline: none;
        }

        .btn-submit {
            background: #000000;
            color: #ffffff;
            border-radius: 12px;
            padding: 14px 28px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-submit:hover {
            background: #222222;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .btn-submit:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold text-uppercase fs-3" href="{{ route('home') }}">
            KAIRA<span class="text-secondary">.</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#kairaNavbar" aria-controls="kairaNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="kairaNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4 text-center">
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link fw-semibold dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Shop
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm rounded-3 mt-2">
                        <li><a class="dropdown-item py-2" href="{{ route('productss') }}">All Products</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('collections') }}">Collections</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('collections*') ? 'active' : '' }}" href="{{ Route::has('collections') ? route('collections') : url('/collections') }}">Collection</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('products*') ? 'active' : '' }}" href="{{ route('productss') }}">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ request()->routeIs('home.contact') ? 'active' : '' }}" href="{{ route('home.contact') }}">Contact</a>
                </li>
            </ul>

            <!-- Right Utilities -->
            <div class="d-flex align-items-center justify-content-center gap-2 mt-3 mt-lg-0">
                <a href="{{ route('search') }}" class="nav-icon-btn text-decoration-none" title="Search">
                    <i class="bi bi-search fs-5"></i>
                </a>
                <a href="{{ route('cart') }}" class="nav-icon-btn text-decoration-none" title="Shopping Cart">
                    <i class="bi bi-bag fs-5"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Top Right Floating Success Alert -->
<div class="toast-container-top-right">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg border-0 d-flex align-items-center gap-3 px-4 py-3 rounded-4" role="alert">
            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
            <div>
                <strong class="d-block text-dark">Success</strong>
                <span class="small text-muted">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<!-- Main Section -->
<div class="container my-5 py-3">
    <!-- Header Banner -->
    <div class="text-center mb-5">
        <h1 class="fw-bold display-6 mb-2">Get In Touch</h1>
        <p class="text-muted fs-6">Have questions or feedback? We’d love to hear from you.</p>
    </div>

    <div class="row g-4 justify-content-center align-items-stretch">
        <!-- Contact Information (Dark Modern Accent Card) -->
        <div class="col-lg-4 col-md-5">
            <div class="contact-info-card p-4 p-xl-5 shadow-sm h-100 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="fw-bold text-white mb-2">Contact Info</h4>
                    <p class="text-white-50 small mb-4 pb-2">Reach out directly or visit our headquarters.</p>

                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex align-items-center info-item">
                            <div class="icon-box me-3">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <span class="text-white-50 small text-uppercase d-block fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Support</span>
                                <span class="fs-6 fw-semibold text-white">Kaira Support Team</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center info-item">
                            <div class="icon-box me-3">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div>
                                <span class="text-white-50 small text-uppercase d-block fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Phone</span>
                                <span class="fs-6 fw-semibold text-white">+92 300 1234567</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center info-item">
                            <div class="icon-box me-3">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div>
                                <span class="text-white-50 small text-uppercase d-block fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Email</span>
                                <span class="fs-6 fw-semibold text-white">support@kaira.com</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center info-item">
                            <div class="icon-box me-3">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <span class="text-white-50 small text-uppercase d-block fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Location</span>
                                <span class="fs-6 fw-semibold text-white">Main Boulevard, City Center</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Icons inside Contact Card -->
                <div class="pt-4 mt-4 border-top border-secondary border-opacity-25">
                    <span class="text-white-50 small d-block mb-3">Follow Us</span>
                    <div class="d-flex gap-3 fs-5">
                        <a href="#" class="text-white-50 text-decoration-none hover-white"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white-50 text-decoration-none hover-white"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white-50 text-decoration-none hover-white"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-8 col-md-7">
            <div class="form-card p-4 p-xl-5 shadow-sm h-100">
                <h4 class="fw-bold mb-1">Send Us A Message</h4>
                <p class="text-muted small mb-4">Fill out the form below and our team will get back to you within 24 hours.</p>

                <form action="{{ route('home.contact.send') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-semibold small text-dark">Your Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control custom-input" placeholder="e.g. John Doe" required>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-semibold small text-dark">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control custom-input" placeholder="name@example.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Phone Number</label>
                        <input type="text" name="phone" class="form-control custom-input" placeholder="+92 300 0000000">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-dark">Message <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control custom-input" rows="5" placeholder="Write your query or detailed message here..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-submit text-uppercase w-100 fs-6">
                        Send Message <i class="bi bi-arrow-right ms-2"></i>
                    </button>
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
