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
     * Store a newly created design resource with flexible digital asset file support.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        if ($user && $user->status === 'suspended') {
            abort(403, 'Your account has been suspended. Please contact support for assistance.');
        }

        if (!$user || !$user->isApprovedContributor()) {
            return redirect()->route('contributor.apply')
                ->with('error', 'Contributor KYC verification required. Please apply to unlock Creator Studio and upload privileges.');
        }

        // Normalize aliases for category
        $categoryId = $request->input('category_id');
        if (!$categoryId && $request->filled('category')) {
            $cat = Category::where('id', $request->input('category'))
                ->orWhere('name', $request->input('category'))
                ->orWhere('slug', $request->input('category'))
                ->first();
            $categoryId = $cat?->id;
        }
        if (!$categoryId) {
            $categoryId = Category::first()?->id ?? 1;
        }
        $request->merge(['category_id' => $categoryId]);

        // Normalize aliases for dimensions
        $dimensions = $request->input('dimensions') ?? $request->input('size_dimensions');
        if (!empty($dimensions)) {
            $request->merge([
                'dimensions' => $dimensions,
                'size_dimensions' => $dimensions,
            ]);
        }

        // Resolve cover preview image file (supports 'cover_image' or 'preview_image')
        $coverFile = $request->file('cover_image') ?? $request->file('preview_image');

        // Resolve digital asset file (supports 'asset_file', 'zip_file', 'resource_file', or 'file')
        $assetFile = $request->file('asset_file') ?? $request->file('zip_file') ?? $request->file('resource_file') ?? $request->file('file');

        // Validate text & scalar inputs
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'category' => ['nullable'],
            'description' => ['nullable', 'string', 'max:5000'],
            'dimensions' => ['nullable', 'string', 'max:150'],
            'size_dimensions' => ['nullable', 'string', 'max:150'],
            'color' => ['nullable', 'string', 'max:100'],
            'extensions' => ['nullable'],
            'tags' => ['nullable', 'string'],
            'is_paid' => ['nullable'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'status_toggle' => ['nullable', 'string'],
            'demo_link' => ['nullable', 'url', 'max:255'],
            'requirements' => ['nullable', 'string'],
        ], [
            'title.required' => 'Asset title is required.',
            'category_id.exists' => 'Selected category is invalid.',
            'price.numeric' => 'Price must be a valid number.',
        ]);

        $description = $request->input('description') ?: 'High quality digital design asset.';
        $dimensions = $request->input('dimensions') ?? $request->input('size_dimensions') ?: 'Vector / High Resolution';

        // Validate Cover Preview Image
        if (!$coverFile || !$coverFile->isValid()) {
            return back()->withErrors([
                'cover_image' => 'Cover preview image is required.',
                'preview_image' => 'Cover preview image is required.',
            ])->withInput();
        }
        $allowedImageMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array($coverFile->getMimeType(), $allowedImageMimes)) {
            return back()->withErrors([
                'cover_image' => 'Cover preview image must be JPG, PNG, or WEBP format.',
                'preview_image' => 'Cover preview image must be JPG, PNG, or WEBP format.',
            ])->withInput();
        }
        if ($coverFile->getSize() > 10 * 1024 * 1024) {
            return back()->withErrors([
                'cover_image' => 'Cover preview image size cannot exceed 10MB.',
                'preview_image' => 'Cover preview image size cannot exceed 10MB.',
            ])->withInput();
        }

        // Validate Digital Asset File
        if (!$assetFile || !$assetFile->isValid()) {
            return back()->withErrors([
                'asset_file' => 'Digital asset source file (e.g., .psd, .ai, .fig, .pdf, .eps, .zip) is required.',
                'zip_file' => 'Digital asset source file is required.',
                'resource_file' => 'Digital asset source file is required.',
                'file' => 'Digital asset source file is required.',
            ])->withInput();
        }

        $extension = strtolower($assetFile->getClientOriginalExtension());
        $allowedAssetExts = [
            'psd', 'ai', 'fig', 'pdf', 'eps', 'svg', 'png', 'jpg', 'jpeg', 'webp',
            'zip', 'rar', '7z', 'tar', 'gz', 'xd', 'sketch', 'indd', 'cdr', 'tif',
            'tiff', 'blend', 'fbx', 'obj'
        ];
        if (!empty($extension) && !in_array($extension, $allowedAssetExts)) {
            return back()->withErrors([
                'asset_file' => 'Uploaded file format is not supported. Please upload a valid digital asset file (.psd, .ai, .fig, .pdf, .eps, .svg, .zip, .rar, etc.).',
                'zip_file' => 'Uploaded file format is not supported.',
                'resource_file' => 'Uploaded file format is not supported.',
                'file' => 'Uploaded file format is not supported.',
            ])->withInput();
        }

        if ($assetFile->getSize() > 100 * 1024 * 1024) {
            return back()->withErrors([
                'asset_file' => 'Digital asset file size cannot exceed 100MB.',
                'zip_file' => 'Asset package size cannot exceed 100MB.',
                'resource_file' => 'Asset package size cannot exceed 100MB.',
                'file' => 'Asset package size cannot exceed 100MB.',
            ])->withInput();
        }

        // Process File Storage
        $previewPath = $coverFile->store('templates/covers', 'public');
        $rawExt = $assetFile->getClientOriginalExtension() ?: 'bin';
        $filename = time() . '_' . uniqid() . '.' . $rawExt;
        $filePath = $assetFile->storeAs('templates/packages', $filename, 'public');

        // Process File Extensions / Type
        $extensionsSelected = $request->input('extensions');
        if (is_array($extensionsSelected)) {
            $extensionsStr = implode(', ', array_filter($extensionsSelected));
        } else {
            $extensionsStr = (string) $extensionsSelected;
        }
        $fileType = !empty($extensionsStr) ? strtoupper($extensionsStr) : strtoupper($rawExt ?: 'ASSET');

        // Process Formatted Requirements & Specs
        $color = $request->input('color', '#6C4CF1');
        $size = $dimensions;
        $extraReq = $request->input('requirements', '');
        $combinedRequirements = "Size: {$size} | Color: {$color} | Extensions: {$fileType}" . ($extraReq ? " | {$extraReq}" : "");

        // Process Manual & AI Auto-generated Tags (Max 10 tags limit)
        $manualTags = [];
        if (!empty($request->tags)) {
            $exploded = array_map('trim', explode(',', $request->tags));
            $manualTags = array_slice(array_filter($exploded), 0, 10);
        }

        $categoryName = Category::find($categoryId)?->name;
        $tagsArray = TagService::generate(
            $validated['title'],
            $description,
            $categoryName,
            $manualTags
        );
        $tagsArray = array_slice($tagsArray, 0, 10);

        // Generate unique SEO slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug . '-' . Str::random(6);

        // Pricing logic
        $rawPrice = (float) ($request->input('price', 0));
        $isPaid = $request->boolean('is_paid') || $rawPrice > 0;
        $price = $isPaid ? $rawPrice : 0.00;

        // Status logic: active ('approved') or 'inactive'
        $status = ($request->input('status_toggle') === 'inactive') ? 'inactive' : 'approved';

        // Create Resource record
        $resource = Resource::create([
            'user_id' => auth()->id(),
            'category_id' => $categoryId,
            'category' => $categoryId,
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $description,
            'preview_image' => $previewPath,
            'thumbnail_path' => $previewPath,
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
            'Design Upload Published',
            "Your design \"{$resource->title}\" was published successfully!",
            'seller',
            route('seller.dashboard') . '#designs'
        );

        // Send Notification to Super Admins
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();
        foreach ($adminUsers as $admin) {
            Notification::send(
                $admin->id,
                'New Resource Published',
                "Contributor " . auth()->user()->name . " published a new design \"{$resource->title}\".",
                'admin',
                route('admin.resources.index')
            );
        }

        return redirect()->route('dashboard', ['mode' => 'seller', 'tab' => 'designs'])
            ->with('success', '✨ Your design was uploaded and published successfully!');
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

    /**
     * Download or stream the official Noksha Creator Upload Guidelines (PDF / Printable HTML).
     */
    public function downloadGuidelines()
    {
        $data = [
            'appName' => 'Noksha Digital Marketplace',
            'title' => 'Noksha Creator Upload Guidelines & Submission Standards',
            'version' => '2026.1',
            'date' => now()->format('F d, Y'),
        ];

        // If DomPDF package is installed in environment
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.guidelines', $data);
            return $pdf->download('Noksha-Creator-Upload-Guidelines.pdf');
        }

        // Clean, printable, self-contained HTML/PDF response
        return response()->view('pdf.guidelines', $data, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="Noksha-Creator-Upload-Guidelines.html"',
        ]);
    }
}
