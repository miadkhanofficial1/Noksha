<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Resource;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
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
     * Process payment checkout, save order, clear cart, and redirect to success page.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'string', 'in:bkash,nagad,rocket,card,cash,wallet'],
        ], [
            'payment_method.required' => 'Please select a payment method for checkout.',
        ]);

        $buyer = auth()->user();
        $cartItems = Cart::where('user_id', $buyer->id)
            ->with(['resource.owner'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('warning', 'Your cart is empty.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->resource && $item->resource->is_paid ? $item->resource->price : 0.00;
        });

        // If paying via Central Wallet, verify and deduct funds
        if ($validated['payment_method'] === 'wallet' && $total > 0) {
            $wallet = $buyer->wallet;
            if ($wallet->balance < $total) {
                $shortfall = $total - $wallet->balance;
                return back()->with('error', "Insufficient wallet balance. You need ৳" . number_format($shortfall, 2) . " more. Please recharge your wallet.");
            }

            $wallet->decrement('balance', $total);
            if (Schema::hasColumn('users', 'balance')) {
                $buyer->update(['balance' => $wallet->balance]);
            }

            // Record Buyer Transaction
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $buyer->id,
                'type' => 'template_purchase',
                'amount' => $total,
                'credits_transacted' => 0,
                'balance_after' => $wallet->balance,
                'description' => "Cart checkout payment for " . $cartItems->count() . " item(s)",
                'status' => 'completed',
            ]);
        }

        $orderNumber = 'NOK-' . strtoupper(Str::random(8));
        $transactionId = 'TXN-' . strtoupper(Str::random(10));

        // 1. Create Order
        $order = Order::create([
            'user_id' => $buyer->id,
            'order_number' => $orderNumber,
            'total' => $total,
            'payment_status' => 'completed',
            'order_status' => 'completed',
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $transactionId,
        ]);

        // 2. Create Order Items, Distribute Seller Royalties (85%), & Increment Downloads
        foreach ($cartItems as $item) {
            if ($item->resource) {
                $itemPrice = $item->resource->is_paid ? (float)$item->resource->price : 0.00;
                OrderItem::create([
                    'order_id' => $order->id,
                    'resource_id' => $item->resource_id,
                    'price' => $itemPrice,
                    'quantity' => 1,
                ]);

                $item->resource->increment('downloads');

                // If paid template, credit 85% to creator's wallet (15% platform commission)
                if ($itemPrice > 0 && $item->resource->owner && $item->resource->owner->id !== $buyer->id) {
                    $seller = $item->resource->owner;
                    $commission = round($itemPrice * 0.15, 2);
                    $sellerEarnings = round($itemPrice - $commission, 2);

                    $sellerWallet = $seller->wallet;
                    $sellerWallet->increment('balance', $sellerEarnings);
                    if (Schema::hasColumn('users', 'balance')) {
                        $seller->update(['balance' => $sellerWallet->balance]);
                    }

                    WalletTransaction::create([
                        'wallet_id' => $sellerWallet->id,
                        'user_id' => $seller->id,
                        'type' => 'template_sale',
                        'amount' => $sellerEarnings,
                        'credits_transacted' => 0,
                        'balance_after' => $sellerWallet->balance,
                        'description' => "Template sale: {$item->resource->title} (৳{$sellerEarnings} net after 15% platform commission)",
                        'status' => 'completed',
                    ]);

                    Notification::send(
                        $seller->id,
                        'Template Sold!',
                        "Your template \"{$item->resource->title}\" was purchased! ৳" . number_format($sellerEarnings, 2) . " has been credited to your wallet.",
                        'seller',
                        route('seller.payouts.index')
                    );
                }
            }
        }

        // 3. Clear Cart
        Cart::where('user_id', $buyer->id)->delete();

        // 4. Send Buyer Notification
        Notification::send(
            $buyer->id,
            'Order Placed & Files Ready',
            "Your order #{$orderNumber} was completed. Your digital files are ready for instant download.",
            'buyer',
            route('orders.success', $order->id)
        );

        return redirect()->route('orders.success', $order->id)
            ->with('success', '🎉 Order placed successfully! Thank you for purchasing on Noksha.');
    }

    /**
     * Direct single-template purchase using central wallet balance.
     */
    public function buyWithWallet(Request $request, Resource $resource): RedirectResponse|JsonResponse
    {
        $buyer = auth()->user();
        $buyerWallet = $buyer->wallet ?? \App\Models\Wallet::firstOrCreate(['user_id' => $buyer->id], ['balance' => 0.00]);
        $price = (float) $resource->price;

        // If template is free or buyer is Admin, grant instant download access without charge
        if ($buyer->isAdmin() || !$resource->is_paid || $price <= 0) {
            $orderNumber = ($buyer->isAdmin() ? 'NOK-ADM-' : 'NOK-FR-') . strtoupper(Str::random(8));
            $order = Order::create([
                'user_id' => $buyer->id,
                'order_number' => $orderNumber,
                'total' => 0.00,
                'payment_status' => 'completed',
                'order_status' => 'completed',
                'payment_method' => $buyer->isAdmin() ? 'admin_bypass' : 'free',
                'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
            ]);
            OrderItem::create([
                'order_id' => $order->id,
                'resource_id' => $resource->id,
                'price' => 0.00,
                'quantity' => 1,
            ]);
            $resource->increment('downloads');

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'is_admin' => $buyer->isAdmin(),
                    'message' => $buyer->isAdmin() ? 'Admin bypass: template unlocked with full privileges!' : 'Free template added to your library!',
                    'download_url' => route('resource.download', $resource->id),
                ]);
            }
            return redirect()->route('resource.show', $resource->slug ?? $resource->id)
                ->with('success', '🎉 Template unlocked! You can now download the files.');
        }

        // Check if user already owns this resource
        $alreadyPurchased = Order::where('user_id', $buyer->id)
            ->where('payment_status', 'completed')
            ->whereHas('items', function ($query) use ($resource) {
                $query->where('resource_id', $resource->id);
            })
            ->exists();

        if ($alreadyPurchased) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'already_purchased' => true,
                    'message' => 'You already own a license for this template.',
                    'download_url' => route('resource.download', $resource->id),
                ]);
            }
            return redirect()->route('resource.download', $resource->id);
        }

        // Check wallet balance
        if ($buyerWallet->balance < $price) {
            $shortfall = $price - $buyerWallet->balance;
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'insufficient_balance',
                    'price' => $price,
                    'balance' => $buyerWallet->balance,
                    'shortfall' => $shortfall,
                    'recharge_url' => route('wallet.index'),
                    'message' => "Insufficient wallet balance. You need ৳" . number_format($shortfall, 2) . " more.",
                ], 402);
            }
            return redirect()->route('resource.show', $resource->slug ?? $resource->id)
                ->with('error', "Insufficient wallet balance. You need ৳" . number_format($shortfall, 2) . " more. Please recharge your wallet.");
        }

        // Deduct from buyer's wallet
        $buyerWallet->decrement('balance', $price);
        if (Schema::hasColumn('users', 'balance')) {
            $buyer->update(['balance' => $buyerWallet->balance]);
        }

        // Log buyer's transaction
        WalletTransaction::create([
            'wallet_id' => $buyerWallet->id,
            'user_id' => $buyer->id,
            'type' => 'template_purchase',
            'amount' => $price,
            'credits_transacted' => 0,
            'balance_after' => $buyerWallet->balance,
            'description' => "Purchased template: {$resource->title}",
            'status' => 'completed',
        ]);

        // Credit 85% to seller (15% platform commission)
        $seller = $resource->owner;
        $commission = round($price * 0.15, 2);
        $sellerEarnings = round($price - $commission, 2);

        if ($seller && $seller->id !== $buyer->id) {
            $sellerWallet = $seller->wallet;
            $sellerWallet->increment('balance', $sellerEarnings);
            if (Schema::hasColumn('users', 'balance')) {
                $seller->update(['balance' => $sellerWallet->balance]);
            }

            // Log seller's transaction
            WalletTransaction::create([
                'wallet_id' => $sellerWallet->id,
                'user_id' => $seller->id,
                'type' => 'template_sale',
                'amount' => $sellerEarnings,
                'credits_transacted' => 0,
                'balance_after' => $sellerWallet->balance,
                'description' => "Template sale: {$resource->title} (৳{$sellerEarnings} net after 15% platform commission)",
                'status' => 'completed',
            ]);

            // Notify seller
            Notification::send(
                $seller->id,
                'Template Sold!',
                "Your template \"{$resource->title}\" was purchased by {$buyer->name}! ৳" . number_format($sellerEarnings, 2) . " has been credited to your wallet balance.",
                'seller',
                route('seller.payouts.index')
            );
        }

        // Create completed Order & OrderItem to grant instant download access
        $orderNumber = 'NOK-WL-' . strtoupper(Str::random(8));
        $transactionId = 'TXN-WL-' . strtoupper(Str::random(10));
        $order = Order::create([
            'user_id' => $buyer->id,
            'order_number' => $orderNumber,
            'total' => $price,
            'payment_status' => 'completed',
            'order_status' => 'completed',
            'payment_method' => 'wallet',
            'transaction_id' => $transactionId,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'resource_id' => $resource->id,
            'price' => $price,
            'quantity' => 1,
        ]);

        $resource->increment('downloads');

        // Notify buyer
        Notification::send(
            $buyer->id,
            'Template Purchased Successfully',
            "You purchased \"{$resource->title}\" using your Noksha Wallet balance. Digital files are ready for download.",
            'buyer',
            route('orders.show', $order->id)
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '🎉 Template purchased successfully! Digital files are unlocked.',
                'remaining_balance' => $buyerWallet->fresh()->balance,
                'download_url' => route('resource.download', $resource->id),
            ]);
        }

        return redirect()->route('resource.show', $resource->slug ?? $resource->id)
            ->with('success', "🎉 Successfully purchased \"{$resource->title}\" using your wallet! Digital files are unlocked.");
    }
}
