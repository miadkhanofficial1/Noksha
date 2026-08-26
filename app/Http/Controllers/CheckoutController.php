<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout billing summary and demo payment page.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with(['resource.category', 'resource.owner'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('warning', 'Your shopping cart is currently empty. Please add design assets before checking out.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->resource && $item->resource->is_paid ? $item->resource->price : 0.00;
        });

        $total = $subtotal;

        return view('checkout.index', compact('cartItems', 'subtotal', 'total'));
    }

    /**
     * Process demo payment checkout, save order, clear cart, and redirect to success page.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'string', 'in:bkash,nagad,rocket,card,cash'],
        ], [
            'payment_method.required' => 'Please select a payment method for checkout.',
        ]);

        $cartItems = Cart::where('user_id', auth()->id())
            ->with('resource')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('warning', 'Your cart is empty.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->resource && $item->resource->is_paid ? $item->resource->price : 0.00;
        });

        $orderNumber = 'NOK-' . strtoupper(Str::random(8));
        $transactionId = 'TXN-' . strtoupper(Str::random(10));

        // 1. Create Order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => $orderNumber,
            'total' => $total,
            'payment_status' => 'completed',
            'order_status' => 'completed',
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $transactionId,
        ]);

        // 2. Create Order Items & Increment Download Counts
        foreach ($cartItems as $item) {
            if ($item->resource) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'resource_id' => $item->resource_id,
                    'price' => $item->resource->is_paid ? $item->resource->price : 0.00,
                    'quantity' => 1,
                ]);

                $item->resource->increment('downloads');
            }
        }

        // 3. Clear Cart
        Cart::where('user_id', auth()->id())->delete();

        // 4. Send Buyer Notification
        \App\Models\Notification::send(
            auth()->id(),
            'Order Placed & Files Ready',
            "Your order #{$orderNumber} was completed. Your digital files are ready for instant download.",
            'buyer',
            route('orders.success', $order->id)
        );

        return redirect()->route('orders.success', $order->id)
            ->with('success', '🎉 Order placed successfully! Thank you for purchasing on Noksha.');
    }
}
