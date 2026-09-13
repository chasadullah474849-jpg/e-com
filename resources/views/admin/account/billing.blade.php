<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Billing | Kaira Admin</title>

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
            max-width: 1150px;
            margin: auto;
            padding: 40px 20px;
        }

        .billing-card {
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
                <h2>Billing</h2>
                <p class="text-muted mb-0">
                    Billing information and checkout history.
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

        <div class="card billing-card mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Billing Information</h5>
            </div>

            <div class="card-body p-4">
                <form
                    method="POST"
                    action="{{ route('admin.billing.update') }}"
                >
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Company Name
                            </label>

                            <input
                                type="text"
                                name="company_name"
                                class="form-control"
                                value="{{ old('company_name', $user->company_name) }}"
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Billing Email
                            </label>

                            <input
                                type="email"
                                name="billing_email"
                                class="form-control"
                                value="{{ old('billing_email', $user->billing_email ?: $user->email) }}"
                                required
                            >
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Billing Address
                            </label>

                            <textarea
                                name="address"
                                class="form-control"
                                rows="3"
                            >{{ old('address', $user->address) }}</textarea>
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

                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                VAT/Tax Number
                            </label>

                            <input
                                type="text"
                                name="vat_number"
                                class="form-control"
                                value="{{ old('vat_number', $user->vat_number) }}"
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Billing Information
                    </button>
                </form>
            </div>
        </div>

        <div class="card billing-card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    Checkout and Order History
                </h5>
            </div>

            <div class="card-body p-4">
                @if($orders->isEmpty())
                    <div class="alert alert-info mb-0">
                        No completed checkout orders were found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            {{ $order->order_no ?: 'ORDER-' . $order->id }}
                                        </td>

                                        <td>
                                            {{ optional($order->order_date ?? $order->created_at)->format('d M Y') }}
                                        </td>

                                        <td>
                                            {{ $order->items->sum('quantity') }}
                                        </td>

                                        <td>
                                            {{ ucfirst($order->payment_status ?? $order->payment_method ?? 'Pending') }}
                                        </td>

                                        <td>
                                            {{ ucfirst($order->fulfillment_status ?? $order->delivery_status ?? $order->status ?? 'Pending') }}
                                        </td>

                                        <td class="text-end fw-semibold">
                                            PKR
                                            {{ number_format($order->total_amount ?? $order->total ?? 0, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $orders->links() }}
                @endif
            </div>
        </div>
    </main>
</body>
</html>
