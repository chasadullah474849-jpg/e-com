<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>My Profile | Kaira Admin</title>

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
            max-width: 1000px;
            margin: auto;
            padding: 40px 20px;
        }

        .account-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 3px 18px rgba(67, 89, 113, .12);
        }

        .profile-image {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #eef0ff;
        }

        .btn-primary {
            background: #696cff;
            border-color: #696cff;
        }
    </style>
</head>

<body>
    @php
        $profileImage = $user->avatar
            ? asset('storage/' . ltrim($user->avatar, '/'))
            : asset('admins/assets/img/avatars/1.png');
    @endphp

    <main class="page-wrapper">
        <div class="d-flex justify-content-between mb-4">
            <div>
                <h2>My Profile</h2>
                <p class="text-muted mb-0">
                    View and update your account information.
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

        <div class="card account-card">
            <div class="card-body p-4">
                <form
                    method="POST"
                    action="{{ route('admin.profile.update') }}"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @method('PUT')

                    <div class="text-center mb-4">
                        <img
                            src="{{ $profileImage }}"
                            alt="Profile image"
                            class="profile-image mb-3"
                        >

                        <input
                            type="file"
                            name="avatar"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                        </div>

                        <div class="col-md-6 mb-3">
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

                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>

                            <input
                                type="text"
                                name="city"
                                class="form-control"
                                value="{{ old('city', $user->city) }}"
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country</label>

                            <input
                                type="text"
                                name="country"
                                class="form-control"
                                value="{{ old('country', $user->country) }}"
                            >
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Address</label>

                            <textarea
                                name="address"
                                class="form-control"
                                rows="3"
                            >{{ old('address', $user->address) }}</textarea>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label">Bio</label>

                            <textarea
                                name="bio"
                                class="form-control"
                                rows="4"
                            >{{ old('bio', $user->bio) }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Profile
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
