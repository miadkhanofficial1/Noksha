<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    /**
     * Display customer's order history list.
     */
    public function index(Request $request): View
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.resource.category', 'items.resource.owner'])
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Display specific order details.
     */
    public function show(Order $order): View
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order.');
        }

        $order->load(['items.resource.category', 'items.resource.owner']);

        return view('orders.show', compact('order'));
    }

    /**
     * Display order placement success confirmation page.
     */
    public function success(Order $order): View
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order success page.');
        }

        $order->load(['items.resource.category', 'items.resource.owner']);

        return view('orders.success', compact('order'));
    }

    /**
     * Handle direct digital resource file download.
     */
    public function download(Resource $resource): StreamedResponse|RedirectResponse
    {
        // Free resources are instantly downloadable; Paid resources require completed purchase
        $hasPurchased = !$resource->is_paid || Order::where('user_id', auth()->id())
            ->where('payment_status', 'completed')
            ->whereHas('items', function ($query) use ($resource) {
                $query->where('resource_id', $resource->id);
            })
            ->exists();

        if (!$hasPurchased) {
            return redirect()->route('resource.show', $resource->slug ?? $resource->id)
                ->with('warning', 'Please complete payment purchase before downloading this paid asset.');
        }

        $resource->increment('downloads');

        if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
            return Storage::disk('public')->download($resource->file_path, $resource->title . '.' . ($resource->file_type ?? 'zip'));
        }

        return redirect()->back()->with('success', "✨ Digital file download started for \"{$resource->title}\"!");
    }
}
