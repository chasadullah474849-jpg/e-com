<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Settings | Kaira Admin</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f5f5f9;
            color: #566a7f;
        }

        .page-wrapper {
            max-width: 900px;
            margin: auto;
            padding: 40px 20px;
        }

        .settings-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 3px 18px rgba(67, 89, 113, .12);
        }

        .btn-primary {
            background: #696cff;
            border-color: #696cff;
        }
    </style>
</head>

<body>
    <main class="page-wrapper">
        <div class="d-flex justify-content-between mb-4">
            <div>
                <h2>Account Settings</h2>
                <p class="text-muted mb-0">
                    Update account details and password.
                </p>
            </div>

            <a
                href="{{ route('admin.dashboard') }}"
                class="btn btn-outline-secondary align-self-center"
            >
                Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card settings-card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Account Information</h5>
            </div>

            <div class="card-body p-4">
                <form
                    method="POST"
                    action="{{ route('admin.settings.update') }}"
                >
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Name</label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            Contact Number
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="{{ old('phone', $user->phone) }}"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Account Settings
                    </button>
                </form>
            </div>
        </div>

        <div class="card settings-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Change Password</h5>
            </div>

            <div class="card-body p-4">
                <form
                    method="POST"
                    action="{{ route('admin.settings.password') }}"
                >
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">
                            Current Password
                        </label>

                        <input
                            type="password"
                            name="current_password"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            New Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Change Password
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
