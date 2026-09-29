<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Resource;
use App\Models\Review;
use App\Models\User;
use App\Services\TagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResourceController extends Controller
{
    /**
     * Show the contributor design upload form.
     * Redirects to the Unified Dashboard Top-Tabs embedded upload form.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('dashboard', ['tab' => 'upload']);
    }

    /**
     * Store a newly created design resource with strict ZIP validation and metadata.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        if ($user && $user->status === 'suspended') {
            abort(403, 'Your account has been suspended. Please contact support for assistance.');
        }

        // Strict Server-Side Validation
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'color' => ['nullable', 'string', 'max:100'],
            'extensions' => ['nullable'], // Array or string of selected extensions
            'tags' => ['nullable', 'string'],
            'size_dimensions' => ['required', 'string', 'max:150'],
            'preview_image' => ['required', 'file', 'mimes:jpeg,png,jpg,webp', 'max:10240'], // Max 10MB
            'resource_file' => [
                'required',
                'file',
                'extensions:zip',
                'max:102400', // Max 100MB
            ],
            'is_paid' => ['nullable'],
            'price' => [$request->boolean('is_paid') ? 'required' : 'nullable', 'numeric', 'min:0'],
            'status_toggle' => ['nullable', 'string'],
            'demo_link' => ['nullable', 'url', 'max:255'],
            'requirements' => ['nullable', 'string'],
        ], [
            'title.required' => 'Resource title is required.',
            'category_id.required' => 'Please select a category.',
            'description.required' => 'Design description is required.',
            'size_dimensions.required' => 'Design format / dimensions are required.',
            'preview_image.required' => 'Cover preview image is required.',
            'preview_image.mimes' => 'Preview image must be JPG, PNG, or WEBP format.',
            'preview_image.max' => 'Preview image size cannot exceed 10MB.',
            'resource_file.required' => 'Original design package .ZIP file is required.',
            'resource_file.extensions' => 'Uploaded package must strictly be a valid .ZIP archive.',
            'resource_file.max' => 'ZIP package size cannot exceed 100MB.',
            'price.required' => 'Price is required for premium assets.',
        ]);

        // Process File Storage
        $previewPath = $request->file('preview_image')->store('previews', 'public');
        $filePath = $request->file('resource_file')->store('resources', 'public');

        // Process File Extensions
        $extensionsSelected = $request->input('extensions');
        if (is_array($extensionsSelected)) {
            $extensionsStr = implode(', ', array_filter($extensionsSelected));
        } else {
            $extensionsStr = (string) $extensionsSelected;
        }
        $fileType = !empty($extensionsStr) ? strtoupper($extensionsStr) : 'ZIP';

        // Process Formatted Requirements & Specs
        $color = $request->input('color', '#6C4CF1');
        $size = $validated['size_dimensions'];
        $extraReq = $request->input('requirements', '');
        $combinedRequirements = "Size: {$size} | Color: {$color} | Extensions: {$fileType}" . ($extraReq ? " | {$extraReq}" : "");

        // Process Manual & AI Auto-generated Tags (Max 10 tags limit)
        $manualTags = [];
        if (!empty($request->tags)) {
            $exploded = array_map('trim', explode(',', $request->tags));
            $manualTags = array_slice(array_filter($exploded), 0, 10);
        }

        $categoryName = Category::find($validated['category_id'])?->name;
        $tagsArray = TagService::generate(
            $validated['title'],
            $validated['description'],
            $categoryName,
            $manualTags
        );
        $tagsArray = array_slice($tagsArray, 0, 10);

        // Generate unique SEO slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug . '-' . Str::random(6);

        // Pricing logic
        $isPaid = $request->boolean('is_paid');
        $price = $isPaid ? (float) ($validated['price'] ?? 0.00) : 0.00;

        // Status logic: active ('pending' moderation) or 'inactive'
        $status = ($request->input('status_toggle') === 'inactive') ? 'inactive' : 'pending';

        // Create Resource record
        $resource = Resource::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'preview_image' => $previewPath,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'tags' => $tagsArray,
            'is_paid' => $isPaid,
            'price' => $price,
            'requirements' => $combinedRequirements,
            'demo_link' => $request->demo_link,
            'status' => $status,
            'downloads' => 0,
            'views' => 0,
        ]);

        // Send Notification to Contributor
        Notification::send(
            auth()->id(),
            'Design Upload Submitted',
            "Your design \"{$resource->title}\" was uploaded successfully and is pending admin moderation.",
            'seller',
            route('seller.dashboard') . '#designs'
        );

        // Send Notification to Super Admins
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();
        foreach ($adminUsers as $admin) {
            Notification::send(
                $admin->id,
                'New Resource Awaiting Moderation',
                "Contributor " . auth()->user()->name . " uploaded a new design \"{$resource->title}\".",
                'admin',
                route('admin.resources.index')
            );
        }

        return redirect()->route('dashboard', ['mode' => 'seller', 'tab' => 'designs'])
            ->with('success', '✨ Your design was uploaded successfully! It is currently awaiting admin moderation.');
    }

    /**
     * Display details of a specific resource along with purchase verification & ratings.
     */
    public function show(string $slugOrId): View
    {
        $resource = Resource::where('slug', $slugOrId)
            ->orWhere('id', $slugOrId)
            ->with(['owner', 'category', 'reviews.user'])
            ->first();

        if (!$resource) {
            $resource = Resource::with(['owner', 'category', 'reviews.user'])->latest()->first();
        }

        $hasPurchased = false;
        $hasReviewed = false;

        if ($resource) {
            $resource->increment('views');

            $relatedResources = Resource::where('status', 'approved')
                ->where('id', '!=', $resource->id)
                ->where('category_id', $resource->category_id)
                ->take(3)
                ->get();

            if (auth()->check()) {
                $hasPurchased = auth()->user()->isAdmin() || !$resource->is_paid || Order::where('user_id', auth()->id())
                    ->where('payment_status', 'completed')
                    ->whereHas('items', function ($query) use ($resource) {
                        $query->where('resource_id', $resource->id);
                    })
                    ->exists();

                $hasReviewed = Review::where('user_id', auth()->id())
                    ->where('resource_id', $resource->id)
                    ->exists();
            }

            $reviews = $resource->reviews()->with('user')->latest()->get();
            $totalReviews = $resource->reviews()->count();
            $avgRating = $totalReviews > 0 ? round($resource->reviews()->avg('rating'), 1) : 0.0;

            // Star distribution percentages
            $starBreakdown = [];
            for ($i = 5; $i >= 1; $i--) {
                $count = $resource->reviews()->where('rating', $i)->count();
                $pct = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                $starBreakdown[$i] = [
                    'count' => $count,
                    'percentage' => $pct,
                ];
            }

            return view('resource.show', compact(
                'resource',
                'relatedResources',
                'hasPurchased',
                'hasReviewed',
                'reviews',
                'totalReviews',
                'avgRating',
                'starBreakdown'
            ));
        }

        abort(404, 'Design resource not found.');
    }
}
