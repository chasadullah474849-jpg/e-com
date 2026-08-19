<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - Checkout</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f7f7f7;
            color: #222;
        }

        .login-wrapper {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px;
        }

        .login-box {
            width: 100%;
            max-width: 460px;

            background: #fff;

            padding: 45px;

            border-radius: 14px;

            box-shadow:
                0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .logo {
            text-align: center;

            margin-bottom: 30px;
        }

        .logo h1 {
            margin: 0;

            font-size: 34px;

            letter-spacing: 2px;
        }

        .logo p {
            margin-top: 5px;

            color: #777;

            font-size: 14px;
        }

        h2 {
            text-align: center;

            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;

            margin-bottom: 8px;

            font-weight: 600;
        }

        input {
            width: 100%;

            padding: 14px 15px;

            border: 1px solid #ccc;

            border-radius: 7px;

            font-size: 15px;

            outline: none;
        }

        input:focus {
            border-color: #222;
        }

        .login-btn {
            width: 100%;

            padding: 15px;

            border: none;

            border-radius: 7px;

            background: #222;

            color: #fff;

            font-size: 16px;

            cursor: pointer;
        }

        .login-btn:hover {
            background: #000;
        }

        .error {
            background: #ffe5e5;

            color: #b00020;

            padding: 13px;

            border-radius: 7px;

            margin-bottom: 20px;
        }

        .back-cart {
            text-align: center;

            margin-top: 22px;
        }

        .back-cart a {
            color: #222;

            text-decoration: none;
        }

        .demo-account {
            margin-top: 25px;

            padding: 15px;

            background: #f5f5f5;

            border-radius: 8px;

            font-size: 13px;

            color: #555;
        }

        .demo-account strong {
            color: #222;
        }

    </style>

</head>

<body>

<div class="login-wrapper">

    <div class="login-box">

        <div class="logo">

            <h1>KAIRA</h1>

            <p>Secure Checkout</p>

        </div>


        <h2>
            Sign in to continue
        </h2>


        {{-- Error Messages --}}

        @if($errors->any())

            <div class="error">

                @foreach($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        @endif


        {{-- Login Form --}}

        <form
            action="{{ route('checkout.authenticate') }}"
            method="POST"
        >

            @csrf


            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    autocomplete="email"
                    required
                >

            </div>


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >

            </div>


            <button
                type="submit"
                class="login-btn"
            >

                Login & Continue

            </button>

        </form>


        {{-- Test Account --}}

        <div class="demo-account">

            <strong>Test Customer Account</strong>

            <br><br>

            Email:
            <strong>
                customer@example.com
            </strong>

            <br>

            Password:
            <strong>
                Customer@123
            </strong>

        </div>


        <div class="back-cart">

            <a href="{{ route('cart') }}">
                ← Back to Cart
            </a>

        </div>

    </div>

</div>

</body>

</html>
