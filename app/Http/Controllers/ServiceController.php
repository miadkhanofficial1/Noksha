<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\Notification;
use App\Models\Resource;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Maximum allowed active/total creative services per creator.
     */
    public const MAX_SERVICES_PER_CREATOR = 5;

    /**
     * Minimum price allowed for any creative service in BDT.
     */
    public const MINIMUM_SERVICE_PRICE = 600.00;

    /**
     * Public discovery catalogue for creative services (/services).
     */
    public function index(Request $request): View
    {
        $queryStr = trim($request->input('q', ''));
        $category = $request->input('category');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $deliveryDays = $request->input('delivery_days');
        $availableOnly = $request->boolean('available_only');
        $sort = $request->input('sort', 'latest');

        $servicesQuery = Service::with(['user.receivedReviews'])
            ->where(function ($q) {
                $q->where('status', 'active')->orWhere('is_active', true);
            });

        // Keyword Search
        if (!empty($queryStr)) {
            $servicesQuery->where(function ($q) use ($queryStr) {
                $q->where('title', 'like', "%{$queryStr}%")
                  ->orWhere('description', 'like', "%{$queryStr}%")
                  ->orWhere('category', 'like', "%{$queryStr}%")
                  ->orWhereHas('user', function ($uq) use ($queryStr) {
                      $uq->where('name', 'like', "%{$queryStr}%")
                         ->orWhere('username', 'like', "%{$queryStr}%");
                  });
            });
        }

        // Category Filter
        if (!empty($category)) {
            $servicesQuery->where('category', $category);
        }

        // Price Filters
        if ($minPrice !== null && is_numeric($minPrice)) {
            $servicesQuery->where('starting_price', '>=', (float) $minPrice);
        }
        if ($maxPrice !== null && is_numeric($maxPrice)) {
            $servicesQuery->where('starting_price', '<=', (float) $maxPrice);
        }

        // Delivery Days Filter
        if (!empty($deliveryDays) && is_numeric($deliveryDays)) {
            $servicesQuery->where('delivery_days', '<=', (int) $deliveryDays);
        }

        // Available Slots Only (max_slots > active_orders_count)
        if ($availableOnly) {
            $servicesQuery->whereRaw('max_slots > active_orders_count');
        }

        // Sorting
        switch ($sort) {
            case 'price_low':
                $servicesQuery->orderBy('starting_price', 'asc');
                break;
            case 'price_high':
                $servicesQuery->orderBy('starting_price', 'desc');
                break;
            case 'rating':
                $servicesQuery->orderByDesc('rating_avg')->orderByDesc('reviews_count');
                break;
            case 'fastest':
                $servicesQuery->orderBy('delivery_days', 'asc');
                break;
            case 'popular':
                $servicesQuery->orderByDesc('active_orders_count')->latest();
                break;
            case 'latest':
            default:
                $servicesQuery->latest();
                break;
        }

        $services = $servicesQuery->paginate(12)->withQueryString();

        // Categories with active services count
        $categories = Category::all();

        return view('services.index', compact(
            'services',
            'categories',
            'queryStr',
            'category',
            'minPrice',
            'maxPrice',
            'deliveryDays',
            'availableOnly',
            'sort'
        ));
    }

    /**
     * Store a newly created creator service.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user || !$user->isApprovedContributor()) {
            return back()->with('error', 'Only verified contributors can publish creative services.');
        }

        // Strict limit: Maximum 5 total services per creator
        $currentCount = $user->services()->count();
        if ($currentCount >= self::MAX_SERVICES_PER_CREATOR) {
            return back()->with('error', "Service limit reached! You have {$currentCount}/5 active services. Please edit or delete an existing service to add a new one.");
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string|min:20',
            'starting_price' => 'required|numeric|min:' . self::MINIMUM_SERVICE_PRICE,
            'delivery_days' => 'required|integer|min:1|max:60',
            'concepts_count' => 'required|integer|min:1|max:10',
            'revisions_count' => 'required|integer|min:0|max:20',
            'max_slots' => 'required|integer|min:1|max:5',
            'status' => 'required|in:active,paused',
            'sample_images' => 'nullable|array|max:3',
            'sample_images.*' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
            'selected_asset_images' => 'nullable|string',
        ], [
            'starting_price.min' => 'Starting price must be at least ৳' . number_format(self::MINIMUM_SERVICE_PRICE, 2) . ' BDT.',
            'max_slots.max' => 'Maximum active weekly slots cannot exceed 5.',
        ]);

        // Process preview images (Max 3)
        $previewImages = [];

        // 1. Check direct file uploads
        if ($request->hasFile('sample_images')) {
            foreach ($request->file('sample_images') as $file) {
                if (count($previewImages) >= 3) break;
                if ($file && $file->isValid()) {
                    $path = $file->store('services/samples', 'public');
                    $previewImages[] = $path;
                }
            }
        }

        // 2. Check picked images from existing Noksha templates/assets
        if (count($previewImages) < 3 && $request->filled('selected_asset_images')) {
            $selectedPaths = explode(',', $request->input('selected_asset_images'));
            foreach ($selectedPaths as $pickedPath) {
                if (count($previewImages) >= 3) break;
                $cleaned = trim($pickedPath);
                if (!empty($cleaned) && !in_array($cleaned, $previewImages)) {
                    $previewImages[] = $cleaned;
                }
            }
        }

        $service = new Service();
        $service->user_id = $user->id;
        $service->title = $request->title;
        $service->slug = Str::slug($request->title) . '-' . Str::lower(Str::random(5));
        $service->category = $request->category;
        $service->description = $request->description;
        $service->starting_price = (float) $request->starting_price;
        $service->price = (float) $request->starting_price;
        $service->delivery_days = (int) $request->delivery_days;
        $service->duration_days = (int) $request->delivery_days;
        $service->concepts_count = (int) $request->concepts_count;
        $service->revisions_count = (int) $request->revisions_count;
        $service->max_slots = (int) $request->max_slots;
        $service->active_orders_count = 0;
        $service->status = $request->status;
        $service->is_active = ($request->status === 'active');
        $service->preview_images = $previewImages;
        $service->rating_avg = 5.00;
        $service->reviews_count = 0;
        $service->save();

        return back()->with('success', "🎉 Your service '{$service->title}' was published successfully! ({$user->services()->count()}/5 slots used)");
    }

    /**
     * Update an existing creator service.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $user = Auth::user();

        if (!$user || ($service->user_id !== $user->id && !$user->isAdmin())) {
            abort(403, 'Unauthorized access to modify this service.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string|min:20',
            'starting_price' => 'required|numeric|min:' . self::MINIMUM_SERVICE_PRICE,
            'delivery_days' => 'required|integer|min:1|max:60',
            'concepts_count' => 'required|integer|min:1|max:10',
            'revisions_count' => 'required|integer|min:0|max:20',
            'max_slots' => 'required|integer|min:1|max:5',
            'status' => 'required|in:active,paused',
            'sample_images' => 'nullable|array|max:3',
            'sample_images.*' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:5120',
            'selected_asset_images' => 'nullable|string',
            'keep_existing_images' => 'nullable|boolean',
        ]);

        $previewImages = $request->boolean('keep_existing_images', true) ? ($service->preview_images ?? []) : [];

        // Upload new images
        if ($request->hasFile('sample_images')) {
            foreach ($request->file('sample_images') as $file) {
                if (count($previewImages) >= 3) break;
                if ($file && $file->isValid()) {
                    $path = $file->store('services/samples', 'public');
                    $previewImages[] = $path;
                }
            }
        }

        // Add gallery picked images
        if (count($previewImages) < 3 && $request->filled('selected_asset_images')) {
            $selectedPaths = explode(',', $request->input('selected_asset_images'));
            foreach ($selectedPaths as $pickedPath) {
                if (count($previewImages) >= 3) break;
                $cleaned = trim($pickedPath);
                if (!empty($cleaned) && !in_array($cleaned, $previewImages)) {
                    $previewImages[] = $cleaned;
                }
            }
        }

        // Ensure array has max 3 items
        $previewImages = array_slice($previewImages, 0, 3);

        $service->title = $request->title;
        $service->category = $request->category;
        $service->description = $request->description;
        $service->starting_price = (float) $request->starting_price;
        $service->price = (float) $request->starting_price;
        $service->delivery_days = (int) $request->delivery_days;
        $service->duration_days = (int) $request->delivery_days;
        $service->concepts_count = (int) $request->concepts_count;
        $service->revisions_count = (int) $request->revisions_count;
        $service->max_slots = (int) $request->max_slots;
        $service->status = $request->status;
        $service->is_active = ($request->status === 'active');
        if (!empty($previewImages)) {
            $service->preview_images = $previewImages;
        }
        $service->save();

        return back()->with('success', "✨ Service '{$service->title}' was updated successfully!");
    }

    /**
     * Delete an existing creator service.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $user = Auth::user();

        if (!$user || ($service->user_id !== $user->id && !$user->isAdmin())) {
            abort(403, 'Unauthorized access to delete this service.');
        }

        // Check for active orders
        $hasActiveOrders = $service->orders()
            ->whereIn('status', ['in_progress', 'funded_in_progress', 'delivered_preview', 'pending_acceptance', 'pending_approval'])
            ->exists();

        if ($hasActiveOrders) {
            return back()->with('error', 'Cannot delete service while active orders are in progress. You can pause the service instead.');
        }

        $serviceTitle = $service->title;
        $service->delete();

        return back()->with('success', "🗑️ Service '{$serviceTitle}' has been removed.");
    }

    /**
     * Quick toggle service status between Active and Paused.
     */
    public function toggleStatus(Service $service): RedirectResponse
    {
        $user = Auth::user();

        if (!$user || ($service->user_id !== $user->id && !$user->isAdmin())) {
            abort(403, 'Unauthorized access to toggle this service.');
        }

        if ($service->status === 'active' || $service->is_active) {
            $service->status = 'paused';
            $service->is_active = false;
            $msg = "Service '{$service->title}' has been paused.";
        } else {
            $service->status = 'active';
            $service->is_active = true;
            $msg = "Service '{$service->title}' is now active and taking client orders.";
        }

        $service->save();

        return back()->with('success', $msg);
    }

    /**
     * Smart Brief Inquiry Flow:
     * Validates project brief, budget, timeline, tags, and reference attachment.
     * Creates or opens conversation thread, injects structured Service Inquiry Card,
     * and redirects buyer directly to chat.
     */
    public function inquire(Request $request, Service $service): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('warning', 'Please sign in to inquire and message this creator.');
        }

        if ($user->id === $service->user_id) {
            return back()->with('warning', 'You cannot send an inquiry for your own service.');
        }

        $request->validate([
            'brief' => 'required|string|min:15|max:3000',
            'budget' => 'nullable|numeric|min:' . self::MINIMUM_SERVICE_PRICE,
            'desired_days' => 'nullable|integer|min:1|max:90',
            'chips' => 'nullable|array',
            'chips.*' => 'string|max:50',
            'reference_file' => 'nullable|file|mimes:jpeg,png,webp,jpg,pdf,zip|max:15360',
        ], [
            'brief.required' => 'Please provide a project brief describing what you need.',
            'brief.min' => 'Project brief should be at least 15 characters long.',
            'budget.min' => 'Estimated budget should be at least ৳' . number_format(self::MINIMUM_SERVICE_PRICE, 2) . ' BDT.',
        ]);

        $creator = $service->user;
        if (!$creator) {
            return back()->with('error', 'Creator profile could not be found.');
        }

        // 1. Locate or create Direct Conversation thread
        $conversation = Conversation::where(function ($q) use ($user, $creator) {
            $q->where('buyer_id', $user->id)->where('seller_id', $creator->id);
        })->orWhere(function ($q) use ($user, $creator) {
            $q->where('buyer_id', $creator->id)->where('seller_id', $user->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'buyer_id' => $user->id,
                'seller_id' => $creator->id,
                'conversation_type' => 'direct',
                'is_frozen' => false,
                'last_message_at' => now(),
            ]);
        }

        // 2. Handle optional reference file
        $referenceFilePath = null;
        $referenceFileName = null;
        if ($request->hasFile('reference_file')) {
            $file = $request->file('reference_file');
            $referenceFileName = $file->getClientOriginalName();
            $referenceFilePath = $file->store('inbox/inquiries', 'public');
        }

        $budget = $request->filled('budget') ? (float) $request->budget : (float) $service->starting_price;
        $deliveryDays = $request->filled('desired_days') ? (int) $request->desired_days : (int) $service->delivery_days;
        $selectedChips = $request->input('chips', []);

        // 3. Build Structured Service Inquiry Brief Card
        $metadata = [
            'type' => 'service_inquiry',
            'service_id' => $service->id,
            'service_title' => $service->title,
            'service_slug' => $service->slug,
            'category' => $service->category,
            'starting_price' => $service->starting_price,
            'preview_images' => $service->preview_images ?? [],
            'budget' => $budget,
            'desired_days' => $deliveryDays,
            'chips' => $selectedChips,
            'brief_text' => $request->brief,
            'reference_file_path' => $referenceFilePath,
            'reference_file_name' => $referenceFileName,
            'buyer_name' => $user->name,
            'buyer_avatar' => $user->avatar,
        ];

        $messageBody = "📋 [Project Inquiry: {$service->title}]\n\n" . $request->brief;

        // 4. Create Message in Conversation
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => $messageBody,
            'message_type' => 'service_proposal', // Rendered natively with custom card in chat
            'is_read' => false,
            'metadata' => $metadata,
        ]);

        // Attach file if present
        if ($referenceFilePath) {
            MessageAttachment::create([
                'message_id' => $message->id,
                'file_name' => $referenceFileName,
                'file_path' => $referenceFilePath,
                'file_type' => pathinfo($referenceFileName, PATHINFO_EXTENSION) ?: 'file',
                'file_size' => $request->file('reference_file')->getSize(),
            ]);
        }

        // Update conversation timestamp
        $conversation->update(['last_message_at' => now()]);

        // 5. Send Notification to Creator
        Notification::send(
            $creator->id,
            'New Service Inquiry',
            "{$user->name} sent a project brief inquiry for '{$service->title}'.",
            'seller',
            route('inbox.show', $conversation->id)
        );

        return redirect()->route('inbox.show', $conversation->id)
            ->with('success', "🚀 Your inquiry for '{$service->title}' has been submitted! Discuss details directly with the creator below.");
    }
}
