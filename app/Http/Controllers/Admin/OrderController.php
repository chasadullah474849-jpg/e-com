<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderStatusUpdatedMail;
use App\Mail\OrderPlacedCustomerMail;
use App\Mail\NewOrderAdminMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('search')) {
            $query->where('order_no', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('fulfillment_status')) {
            $query->where('fulfillment_status', $request->fulfillment_status);
        }

        $orders = $query->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_no'           => 'required|unique:orders,order_no',
            'order_date'         => 'required|date',
            'customer_name'      => 'required|string|max:255',
            'customer_email'     => 'required|email|max:255',
            'delivery_method'    => 'required|string|max:255',
            'payment_status'     => 'required',
            'fulfillment_status' => 'required',
            'delivery_status'    => 'required',
            'items'              => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            $order = Order::create([
                'order_no'           => $request->order_no,
                'order_date'         => $request->order_date,
                'customer_name'      => $request->customer_name,
                'customer_email'     => $request->customer_email,
                'customer_phone'     => $request->customer_phone,
                'total_amount'       => $totalAmount,
                'payment_status'     => $request->payment_status,
                'fulfillment_status' => $request->fulfillment_status,
                'delivery_status'    => $request->delivery_status,
                'delivery_method'    => $request->delivery_method,
                'shipping_address'   => $request->shipping_address,
                'notes'              => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $order->items()->create([
                    'product_name' => $item['product_name'],
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'subtotal'     => $item['quantity'] * $item['unit_price'],
                ]);
            }
        });

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load('items');
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'order_no'           => 'required|unique:orders,order_no,' . $order->id,
            'order_date'         => 'required|date',
            'customer_name'      => 'required|string|max:255',
            'customer_email'     => 'required|email|max:255',
            'delivery_method'    => 'required|string|max:255',
            'payment_status'     => 'required',
            'fulfillment_status' => 'required',
            'delivery_status'    => 'required',
            'items'              => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);

        $changes = [];

        if ($request->payment_status !== $order->payment_status) {
            $changes['Payment Status'] = $request->payment_status;
        }
        if ($request->fulfillment_status !== $order->fulfillment_status) {
            $changes['Fulfillment Status'] = $request->fulfillment_status;
        }
        if ($request->delivery_status !== $order->delivery_status) {
            $changes['Delivery Status'] = $request->delivery_status;
        }

        DB::transaction(function () use ($request, $order) {
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            $order->update([
                'order_no'           => $request->order_no,
                'order_date'         => $request->order_date,
                'customer_name'      => $request->customer_name,
                'customer_email'     => $request->customer_email,
                'customer_phone'     => $request->customer_phone,
                'total_amount'       => $totalAmount,
                'payment_status'     => $request->payment_status,
                'fulfillment_status' => $request->fulfillment_status,
                'delivery_status'    => $request->delivery_status,
                'delivery_method'    => $request->delivery_method,
                'shipping_address'   => $request->shipping_address,
                'notes'              => $request->notes,
            ]);

            $order->items()->delete();
            foreach ($request->items as $item) {
                $order->items()->create([
                    'product_name' => $item['product_name'],
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'subtotal'     => $item['quantity'] * $item['unit_price'],
                ]);
            }
        });

        // Send status update email to Customer
        if (!empty($changes) && !empty($order->customer_email)) {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusUpdatedMail($order, $changes));
            } catch (\Exception $e) {
                Log::error('Status update mail failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully and status email sent.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:128',
            'last_name'      => 'required|string|max:128',
            'email'          => 'required|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'nullable|string|max:20',
            'payment_method' => 'required|string',
        ]);

        $customerName = trim($request->first_name . ' ' . $request->last_name);
        $fullAddress  = implode(', ', array_filter([
            $request->address,
            $request->city,
            $request->postal_code
        ]));

        $createdOrder = null;

        DB::transaction(function () use ($request, $customerName, $fullAddress, &$createdOrder) {

            $cartItems = session()->get('cart', []);

            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            // Create Order using customer's submitted email
            $createdOrder = Order::create([
                'order_no'           => 'ORD-' . strtoupper(Str::random(8)),
                'order_date'         => now(),
                'customer_name'      => $customerName,
                'customer_email'     => $request->email,
                'customer_phone'     => $request->phone,
                'total_amount'       => $totalAmount,
                'payment_status'     => $request->payment_method === 'cash_on_delivery' ? 'pending' : 'paid',
                'fulfillment_status' => 'unfulfilled',
                'delivery_status'    => 'pending',
                'delivery_method'    => $request->payment_method,
                'shipping_address'   => $fullAddress,
                'notes'              => $request->notes ?? null,
            ]);

            foreach ($cartItems as $item) {
                $createdOrder->items()->create([
                    'product_name' => $item['name'],
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['price'],
                    'subtotal'     => $item['price'] * $item['quantity'],
                ]);
            }

            session()->forget('cart');
        });

        // 1. Send confirmation email to Customer
        if ($createdOrder && !empty($createdOrder->customer_email)) {
            try {
                Mail::to($createdOrder->customer_email)->send(new OrderPlacedCustomerMail($createdOrder));
            } catch (\Exception $e) {
                Log::error('Customer order placement mail failed: ' . $e->getMessage());
            }
        }

        // 2. Send new order notification to Admin Email
        $adminEmail = config('mail.from.address'); // Set your admin email address here or in .env
        if ($createdOrder && !empty($adminEmail)) {
            try {
                Mail::to($adminEmail)->send(new NewOrderAdminMail($createdOrder));
            } catch (\Exception $e) {
                Log::error('Admin new order notification mail failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
    }
}
