<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header { background-color: #696cff; color: #ffffff; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .content { padding: 25px; }
        .status-box { background-color: #f8f9fa; border-left: 4px solid #696cff; padding: 15px; margin: 15px 0; border-radius: 4px; }
        .status-item { margin-bottom: 8px; font-size: 15px; }
        .status-item strong { text-transform: capitalize; }
        .footer { text-align: center; padding: 15px; font-size: 12px; color: #888; background: #f8f9fa; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; text-transform: uppercase; background: #e7e7ff; color: #696cff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Status Update</h1>
        </div>
        <div class="content">
            <p>Dear {{ $order->customer_name ?? 'Customer' }},</p>
            <p>We are writing to let you know that your order <strong>#{{ $order->id }}</strong> has been updated.</p>

            <div class="status-box">
                @foreach($changes as $field => $status)
                    <div class="status-item">
                        <strong>{{ str_replace('_', ' ', $field) }}:</strong>
                        <span class="badge">{{ $status }}</span>
                    </div>
                @endforeach
            </div>

            <p><strong>Order Summary:</strong></p>
            <ul>
                <li><strong>Total Amount:</strong> ${{ number_format($order->total, 2) }}</li>
                <li><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</li>
                <li><strong>Fulfillment:</strong> {{ ucfirst($order->fulfillment_status) }}</li>
                <li><strong>Delivery Status:</strong> {{ ucfirst($order->delivery_status) }}</li>
            </ul>

            <p>Thank you for shopping with us!</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Your Store. All rights reserved.
        </div>
    </div>
</body>
</html>
