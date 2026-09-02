<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; color: #333; margin: 0; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 25px; border: 1px solid #e0e0e0; }
        .header { background: #696cff; color: #ffffff; padding: 15px; border-radius: 6px; text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .badge { background: #e7e7ff; color: #696cff; padding: 3px 8px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2>Thank You For Your Order!</h2>
        </div>
        <p>Dear <strong>{{ $order->customer_name }}</strong>,</p>
        <p>Your order <strong>#{{ $order->order_no }}</strong> has been successfully placed.</p>

        <h3>Order Summary:</h3>
        <ul>
            <li><strong>Total Amount:</strong> Rs {{ number_format($order->total_amount, 2) }}</li>
            <li><strong>Payment Status:</strong> <span class="badge">{{ strtoupper($order->payment_status) }}</span></li>
            <li><strong>Fulfillment Status:</strong> <span class="badge">{{ strtoupper($order->fulfillment_status) }}</span></li>
            <li><strong>Delivery Status:</strong> <span class="badge">{{ strtoupper($order->delivery_status) }}</span></li>
            <li><strong>Shipping Address:</strong> {{ $order->shipping_address }}</li>
        </ul>

        <h3>Ordered Items:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rs {{ number_format($item->unit_price, 2) }}</td>
                        <td>Rs {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top:20px;">We are currently processing your order and will keep you updated via email.</p>
    </div>
</body>
</html>
