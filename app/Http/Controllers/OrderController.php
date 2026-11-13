<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
     * Download purchased resource from specific order with verified delivery and fallback packaging.
     */
    public function downloadResource(Order $order, $assetId): BinaryFileResponse|StreamedResponse|RedirectResponse
    {
        // Verify user ownership or admin privileges
        if ($order->user_id !== auth()->id() && auth()->user()?->role !== 'admin' && !auth()->user()?->isAdmin()) {
            abort(403, 'Unauthorized access to purchased resource.');
        }

        // Fetch asset model (Template or Digital Asset or OrderItem)
        $asset = \App\Models\Template::find($assetId)
            ?? \App\Models\Resource::find($assetId)
            ?? $order->items()->where('id', $assetId)->first()?->resource
            ?? $order->items()->first()?->resource;

        if (!$asset) {
            abort(404, 'Purchased digital resource could not be found.');
        }

        $asset->increment('downloads');

        // Dynamic structured ZIP packaging using NokshaFilePackagerService
        $filesToBundle = [];
        if (is_array($asset->preview_images) && count($asset->preview_images) > 0) {
            foreach ($asset->preview_images as $prev) {
                if (!empty($prev)) {
                    $filesToBundle[] = $prev;
                }
            }
        } elseif ($asset->thumbnail_path || $asset->preview_image) {
            $filesToBundle[] = $asset->thumbnail_path ?? $asset->preview_image;
        }

        if ($asset->source_file_path) {
            $filesToBundle[] = $asset->source_file_path;
        }
        if ($asset->file_path && !in_array($asset->file_path, $filesToBundle)) {
            $filesToBundle[] = $asset->file_path;
        }
        if ($asset->instruction_pdf_path) {
            $filesToBundle[] = $asset->instruction_pdf_path;
        }

        $creatorUsername = $asset->user?->username ?? $asset->owner?->username ?? \Illuminate\Support\Str::slug($asset->user?->name ?? 'creator', '_');

        return \App\Services\NokshaFilePackagerService::streamZipDownload(
            $asset->title,
            $creatorUsername,
            $filesToBundle
        );
    }

    /**
     * Handle direct digital resource file download.
     */
    public function download(Resource $resource): BinaryFileResponse|StreamedResponse|RedirectResponse
    {
        // Free resources are instantly downloadable; Paid resources require completed purchase or Admin role
        $hasPurchased = auth()->user()?->isAdmin() || !$resource->is_paid || Order::where('user_id', auth()->id())
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

        // Dynamic structured ZIP packaging using NokshaFilePackagerService
        $filesToBundle = [];
        if (is_array($resource->preview_images) && count($resource->preview_images) > 0) {
            foreach ($resource->preview_images as $prev) {
                if (!empty($prev)) {
                    $filesToBundle[] = $prev;
                }
            }
        } elseif ($resource->thumbnail_path || $resource->preview_image) {
            $filesToBundle[] = $resource->thumbnail_path ?? $resource->preview_image;
        }

        if ($resource->source_file_path) {
            $filesToBundle[] = $resource->source_file_path;
        }
        if ($resource->file_path && !in_array($resource->file_path, $filesToBundle)) {
            $filesToBundle[] = $resource->file_path;
        }
        if ($resource->instruction_pdf_path) {
            $filesToBundle[] = $resource->instruction_pdf_path;
        }

        $creatorUsername = $resource->user?->username ?? $resource->owner?->username ?? \Illuminate\Support\Str::slug($resource->user?->name ?? 'creator', '_');

        return \App\Services\NokshaFilePackagerService::streamZipDownload(
            $resource->title,
            $creatorUsername,
            $filesToBundle
        );
    }
}
