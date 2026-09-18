<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Resource;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResourceController extends Controller
{
    /**
     * Show the resource upload form.
     */
    public function create(): View
    {
        $user = auth()->user();
        if ($user && $user->status === 'suspended') {
            abort(403, 'Your account has been suspended. Please contact support.');
        }

        $categories = Category::all();

        return view('resource.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage and database.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        if ($user && $user->status === 'suspended') {
            abort(403, 'Your account has been suspended. Please contact support.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'preview_image' => ['required', 'file', 'mimes:jpeg,png,jpg,webp,gif,svg', 'max:10240'], // Max 10MB
            'resource_file' => [
                'required',
                'file',
                'extensions:zip,rar,7z,tar,gz,png,jpg,jpeg,psd,fig,figma,ai,svg,pdf,eps,xd,sketch',
                'max:102400', // Max 100MB
            ],
            'is_paid' => ['nullable'],
            'price' => [$request->boolean('is_paid') ? 'required' : 'nullable', 'numeric', 'min:0'],
            'tags' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'demo_link' => ['nullable', 'url', 'max:255'],
        ], [
            'title.required' => 'Resource title is required.',
            'description.required' => 'Product description is required.',
            'preview_image.required' => 'Please upload a cover preview image for your asset.',
            'preview_image.mimes' => 'Preview image must be a valid image file (JPG, PNG, WEBP, GIF, SVG).',
            'preview_image.max' => 'Preview image size cannot exceed 10 MB.',
            'resource_file.required' => 'Please upload the main resource package file.',
            'resource_file.extensions' => 'The resource file must be a valid design asset format (ZIP, RAR, 7Z, PNG, JPG, PSD, FIGMA, AI, SVG, PDF, EPS, or XD).',
            'resource_file.max' => 'The resource file size cannot exceed 100 MB.',
            'price.required' => 'Price is required when resource is marked as Paid.',
        ]);

        // Process File Storage
        $previewPath = $request->file('preview_image')->store('previews', 'public');
        $filePath = $request->file('resource_file')->store('resources', 'public');
        $fileExtension = strtolower($request->file('resource_file')->getClientOriginalExtension());

        // Process Manual & AI Auto-generated Tags
        $manualTags = [];
        if (!empty($request->tags)) {
            $manualTags = array_map('trim', explode(',', $request->tags));
        }

        $categoryName = !empty($validated['category_id']) ? Category::find($validated['category_id'])?->name : null;
        $tagsArray = \App\Services\TagService::generate(
            $validated['title'],
            $validated['description'],
            $categoryName,
            $manualTags
        );

        // Generate unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug . '-' . Str::random(6);

        $isPaid = $request->boolean('is_paid');
        $price = $isPaid ? (float) ($validated['price'] ?? 0.00) : 0.00;

        // Create Resource record
        $resource = Resource::create([
            'user_id' => auth()->id(),
            'category_id' => !empty($validated['category_id']) ? $validated['category_id'] : null,
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'preview_image' => $previewPath,
            'file_path' => $filePath,
            'file_type' => $fileExtension,
            'tags' => $tagsArray,
            'is_paid' => $isPaid,
            'price' => $price,
            'requirements' => $request->requirements,
            'demo_link' => $request->demo_link,
            'status' => 'pending',
            'downloads' => 0,
            'views' => 0,
        ]);

        // Send Notifications
        \App\Models\Notification::send(
            auth()->id(),
            'Resource Uploaded',
            "Your design asset \"{$resource->title}\" was uploaded and is pending approval.",
            'seller',
            route('seller.dashboard')
        );

        $adminUsers = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->get();
        foreach ($adminUsers as $admin) {
            \App\Models\Notification::send(
                $admin->id,
                'New Resource Pending Approval',
                "Resource \"{$resource->title}\" by " . auth()->user()->name . " requires review.",
                'admin',
                route('admin.resources.index')
            );
        }

        return redirect()->route('seller.dashboard')
            ->with('success', '✨ Asset uploaded successfully! Your template is pending moderation approval.');
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
                $hasPurchased = !$resource->is_paid || Order::where('user_id', auth()->id())
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
            $totalReviews = $reviews->count();
            $avgRating = $totalReviews > 0 ? number_format($reviews->avg('rating'), 1) : '4.9';

            $ratingBreakdown = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
            if ($totalReviews > 0) {
                foreach ($reviews as $rev) {
                    $star = (int) $rev->rating;
                    if (isset($ratingBreakdown[$star])) {
                        $ratingBreakdown[$star]++;
                    }
                }
            }
        } else {
            $relatedResources = collect();
            $reviews = collect();
            $totalReviews = 0;
            $avgRating = '4.9';
            $ratingBreakdown = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        }

        return view('resource.show', compact(
            'resource',
            'relatedResources',
            'hasPurchased',
            'hasReviewed',
            'reviews',
            'totalReviews',
            'avgRating',
            'ratingBreakdown'
        ));
    }

    /**
     * Display demo resource page.
     */
    public function showDemo(): View
    {
        return $this->show('demo');
    }
}
