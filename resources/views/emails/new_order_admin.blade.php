<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; color: #333; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 25px; border-radius: 8px; border-left: 5px solid #28a745; }
    </style>
</head>
<body>
    <div class="card">
        <h2>🎉 New Order Received!</h2>
        <p>A new order has been placed on your store:</p>

        <ul>
            <li><strong>Order No:</strong> #{{ $order->order_no }}</li>
            <li><strong>Customer Name:</strong> {{ $order->customer_name }}</li>
            <li><strong>Customer Email:</strong> {{ $order->customer_email }}</li>
            <li><strong>Phone:</strong> {{ $order->customer_phone ?? 'N/A' }}</li>
            <li><strong>Total Amount:</strong> Rs {{ number_format($order->total_amount, 2) }}</li>
            <li><strong>Payment Method:</strong> {{ strtoupper($order->delivery_method) }}</li>
            <li><strong>Shipping Address:</strong> {{ $order->shipping_address }}</li>
        </ul>

        <p><a href="{{ route('admin.orders.show', $order->id) }}" style="background:#696cff; color:#ffffff; padding:10px 15px; text-decoration:none; border-radius:5px; display:inline-block;">View Order in Admin Panel</a></p>
    </div>
</body>
</html>
