<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .card { background: #ffffff; padding: 25px; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #111; margin-top: 0; }
        .field { margin-bottom: 12px; font-size: 14px; }
        .label { font-weight: bold; color: #555; }
        .message-box { background: #f9f9f9; padding: 15px; border-left: 4px solid #000; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>New Contact Message Received</h2>
        <div class="field"><span class="label">Name:</span> {{ $data['name'] }}</div>
        <div class="field"><span class="label">Email:</span> {{ $data['email'] }}</div>
        <div class="field"><span class="label">Phone:</span> {{ $data['phone'] ?? 'N/A' }}</div>

        <div class="message-box">
            <span class="label">Message:</span>
            <p>{{ $data['message'] }}</p>
        </div>
    </div>
</body>
</html>
