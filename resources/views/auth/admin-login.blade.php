<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login | Kaira</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #696cff;
            --primary-dark: #5558e8;
            --primary-soft: #eef0ff;
            --dark: #263238;
            --text: #566a7f;
            --muted: #8b97a6;
            --border: #d9dee3;
            --white: #ffffff;
            --danger: #e53935;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background: #f5f5f9;
        }

        button,
        input {
            font: inherit;
        }

        .login-page {
            display: grid;
            grid-template-columns: minmax(380px, 47%) 1fr;
            min-height: 100vh;
        }

        .visual-panel {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 64px;
            color: var(--white);
            background: linear-gradient(145deg, #7477ff 0%, #5f62ef 52%, #4547bb 100%);
        }

        .visual-panel::before,
        .visual-panel::after {
            position: absolute;
            border-radius: 50%;
            content: "";
        }

        .visual-panel::before {
            top: -135px;
            left: -110px;
            width: 380px;
            height: 380px;
            background: rgba(255, 255, 255, 0.08);
        }

        .visual-panel::after {
            right: -170px;
            bottom: -160px;
            width: 440px;
            height: 440px;
            background: rgba(255, 255, 255, 0.07);
        }

        .visual-content {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 76px;
            color: var(--white);
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-mark {
            display: grid;
            width: 52px;
            height: 52px;
            place-items: center;
            border-radius: 16px;
            color: var(--primary);
            background: var(--white);
            font-size: 25px;
            box-shadow: 0 14px 35px rgba(22, 24, 91, 0.25);
        }

        .visual-content h1 {
            max-width: 470px;
            margin-bottom: 22px;
            font-size: clamp(38px, 4.2vw, 62px);
            line-height: 1.08;
            letter-spacing: -2px;
        }

        .visual-content > p {
            max-width: 440px;
            color: rgba(255, 255, 255, 0.82);
            font-size: 18px;
            line-height: 1.75;
        }

        .feature-list {
            display: grid;
            gap: 16px;
            margin-top: 42px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 13px;
            color: rgba(255, 255, 255, 0.92);
        }

        .check {
            display: grid;
            flex: 0 0 28px;
            width: 28px;
            height: 28px;
            place-items: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.16);
            font-size: 14px;
            font-weight: 900;
        }

        .form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 28px;
            background:
                radial-gradient(circle at 95% 5%, rgba(105, 108, 255, 0.09), transparent 28%),
                var(--white);
        }

        .login-box {
            width: 100%;
            max-width: 460px;
        }

        .mobile-brand {
            display: none;
            align-items: center;
            gap: 10px;
            margin-bottom: 36px;
            color: var(--primary);
            font-size: 26px;
            font-weight: 800;
        }

        .mobile-brand .brand-mark {
            width: 44px;
            height: 44px;
            border: 1px solid #e5e7ff;
            background: var(--primary-soft);
            box-shadow: none;
        }

        .login-box h2 {
            margin-bottom: 10px;
            color: var(--dark);
            font-size: 32px;
            line-height: 1.2;
            letter-spacing: -0.8px;
        }

        .intro {
            margin-bottom: 34px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.65;
        }

        .alert {
            margin-bottom: 22px;
            padding: 13px 15px;
            border: 1px solid #ffc9c6;
            border-radius: 9px;
            color: #b42318;
            background: #fff4f3;
            font-size: 14px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 21px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #435466;
            font-size: 14px;
            font-weight: 600;
        }

        .input-wrap {
            position: relative;
        }

        .form-control {
            width: 100%;
            height: 50px;
            border: 1px solid var(--border);
            border-radius: 9px;
            outline: none;
            padding: 0 15px;
            color: var(--dark);
            background: var(--white);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control::placeholder {
            color: #b1bac4;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(105, 108, 255, 0.12);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(229, 57, 53, 0.1);
        }

        .password-input {
            padding-right: 52px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 7px;
            display: grid;
            width: 39px;
            height: 39px;
            place-items: center;
            border: 0;
            border-radius: 7px;
            color: #7d8996;
            background: transparent;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .password-toggle:hover,
        .password-toggle:focus-visible {
            color: var(--primary);
            background: var(--primary-soft);
            outline: none;
        }

        .invalid-feedback {
            margin-top: 7px;
            color: var(--danger);
            font-size: 13px;
        }

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin: 4px 0 26px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .form-check-input {
            width: 17px;
            height: 17px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .form-check-label {
            color: var(--text);
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        .secure-note {
            color: var(--muted);
            font-size: 13px;
        }

        .btn {
            display: inline-flex;
            width: 100%;
            min-height: 50px;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 9px;
            color: var(--white);
            background: linear-gradient(135deg, var(--primary), #5d5fe7);
            font-weight: 700;
            letter-spacing: 0.1px;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(105, 108, 255, 0.24);
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .btn:hover {
            box-shadow: 0 13px 26px rgba(105, 108, 255, 0.32);
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn:disabled {
            cursor: wait;
            opacity: 0.72;
        }

        .footer-text {
            margin-top: 28px;
            color: #a0a9b4;
            font-size: 13px;
            text-align: center;
        }

        @media (max-width: 900px) {
            .login-page {
                grid-template-columns: 1fr;
            }

            .visual-panel {
                display: none;
            }

            .mobile-brand {
                display: flex;
            }

            .form-panel {
                min-height: 100vh;
            }
        }

        @media (max-width: 480px) {
            .form-panel {
                align-items: flex-start;
                padding: 40px 20px;
            }

            .login-box h2 {
                font-size: 28px;
            }

            .options-row {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <main class="login-page">
        <section class="visual-panel" aria-hidden="true">
            <div class="visual-content">
                <div class="brand">
                    <span class="brand-mark">K</span>
                    <span>Kaira</span>
                </div>

                <h1>Manage your store with confidence.</h1>
                <p>
                    Access your admin dashboard to manage products, orders,
                    customers and store performance from one secure place.
                </p>

                <div class="feature-list">
                    <div class="feature"><span class="check">✓</span><span>Manage products and inventory</span></div>
                    <div class="feature"><span class="check">✓</span><span>Track orders and customers</span></div>
                    <div class="feature"><span class="check">✓</span><span>Review store analytics</span></div>
                </div>
            </div>
        </section>

        <section class="form-panel">
            <div class="login-box">
                <div class="mobile-brand">
                    <span class="brand-mark">K</span>
                    <span>Kaira</span>
                </div>

                <h2>Welcome back</h2>
                <p class="intro">Sign in with your administrator account to continue.</p>

                @if (session('error'))
                    <div class="alert" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->has('login'))
                    <div class="alert" role="alert">
                        {{ $errors->first('login') }}
                    </div>
                @endif

                <form
                    id="adminLoginForm"
                    method="POST"
                    action="{{ route('admin.login.submit') }}"
                >
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="admin@example.com"
                            autocomplete="username"
                            required
                            autofocus
                        >

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>

                        <div class="input-wrap">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control password-input @error('password') is-invalid @enderror"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                id="passwordToggle"
                                class="password-toggle"
                                aria-label="Show password"
                                aria-pressed="false"
                            >
                                <span id="eyeIcon" aria-hidden="true">◉</span>
                            </button>
                        </div>

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="options-row">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                value="1"
                                class="form-check-input"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <label for="remember" class="form-check-label">Remember Me</label>
                        </div>

                        <span class="secure-note">Secure admin access</span>
                    </div>

                    <button type="submit" id="submitButton" class="btn">
                        <span id="buttonText">Sign In</span>
                    </button>
                </form>

                <p class="footer-text">© {{ date('Y') }} Kaira. All rights reserved.</p>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('adminLoginForm');
            const password = document.getElementById('password');
            const passwordToggle = document.getElementById('passwordToggle');
            const eyeIcon = document.getElementById('eyeIcon');
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');

            passwordToggle.addEventListener('click', function () {
                const isHidden = password.type === 'password';

                password.type = isHidden ? 'text' : 'password';
                passwordToggle.setAttribute('aria-pressed', String(isHidden));
                passwordToggle.setAttribute(
                    'aria-label',
                    isHidden ? 'Hide password' : 'Show password'
                );
                eyeIcon.textContent = isHidden ? '⊘' : '◉';
                password.focus();
            });

            form.addEventListener('submit', function () {
                submitButton.disabled = true;
                buttonText.textContent = 'Signing In...';
            });
        });
    </script>
</body>
</html>
