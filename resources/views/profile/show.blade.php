<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <!-- Header Banner -->
                <div class="card-header bg-dark text-white p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold">User Profile</h4>
                        <small class="text-white-50">Manage your account details</small>
                    </div>
                    <span class="badge bg-success rounded-pill px-3 py-2">
                        <i class="fa-solid fa-circle-check me-1"></i> Account Active
                    </span>
                </div>

                <!-- Body Details -->
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-size: 20px;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Full Name</span>
                            <h6 class="fw-bold mb-0 text-dark fs-5">{{ $user->name }}</h6>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-size: 20px;">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Email Address</span>
                            <h6 class="fw-bold mb-0 text-dark fs-6">{{ $user->email }}</h6>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-size: 20px;">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Account Created</span>
                            <h6 class="fw-bold mb-0 text-dark fs-6">
                                {{ $user->created_at ? $user->created_at->format('d M, Y') : '27 Aug, 2026' }}
                            </h6>
                        </div>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="card-footer bg-white border-top-0 p-4 text-end">
                    <a href="{{ url('/') }}" class="btn btn-outline-dark px-4 rounded-pill">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
