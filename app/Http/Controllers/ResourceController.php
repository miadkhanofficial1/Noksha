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

        // Resolve archive package file (supports 'zip_file', 'resource_file', or 'file')
        $zipFile = $request->file('zip_file') ?? $request->file('resource_file') ?? $request->file('file');

        // Validate text & scalar inputs
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:2000'],
            'dimensions' => ['required', 'string', 'max:150'],
            'size_dimensions' => ['required', 'string', 'max:150'],
            'color' => ['nullable', 'string', 'max:100'],
            'extensions' => ['nullable'],
            'tags' => ['nullable', 'string'],
            'is_paid' => ['nullable'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'status_toggle' => ['nullable', 'string'],
            'demo_link' => ['nullable', 'url', 'max:255'],
            'requirements' => ['nullable', 'string'],
        ], [
            'title.required' => 'Resource title is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category is invalid.',
            'description.required' => 'Design description is required.',
            'description.max' => 'Design description cannot exceed 2000 characters.',
            'dimensions.required' => 'Design format / dimensions are required.',
            'size_dimensions.required' => 'Design format / dimensions are required.',
            'price.numeric' => 'Price must be a valid number.',
        ]);

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

        // Validate Original Design Package Archive (.ZIP / .RAR)
        if (!$zipFile || !$zipFile->isValid()) {
            return back()->withErrors([
                'zip_file' => 'Original design package .ZIP file is required.',
                'resource_file' => 'Original design package .ZIP file is required.',
                'file' => 'Original design package .ZIP file is required.',
            ])->withInput();
        }
        $zipExt = strtolower($zipFile->getClientOriginalExtension());
        if (!in_array($zipExt, ['zip', 'rar', '7z', 'tar', 'gz'])) {
            return back()->withErrors([
                'zip_file' => 'Uploaded package must strictly be a valid .ZIP archive.',
                'resource_file' => 'Uploaded package must strictly be a valid .ZIP archive.',
                'file' => 'Uploaded package must strictly be a valid .ZIP archive.',
            ])->withInput();
        }
        if ($zipFile->getSize() > 100 * 1024 * 1024) {
            return back()->withErrors([
                'zip_file' => 'ZIP package size cannot exceed 100MB.',
                'resource_file' => 'ZIP package size cannot exceed 100MB.',
            ])->withInput();
        }

        // Process File Storage
        $previewPath = $coverFile->store('templates/covers', 'public');
        $filePath = $zipFile->store('templates/packages', 'public');

        // Process File Extensions
        $extensionsSelected = $request->input('extensions');
        if (is_array($extensionsSelected)) {
            $extensionsStr = implode(', ', array_filter($extensionsSelected));
        } else {
            $extensionsStr = (string) $extensionsSelected;
        }
        $fileType = !empty($extensionsStr) ? strtoupper($extensionsStr) : strtoupper($zipExt ?: 'ZIP');

        // Process Formatted Requirements & Specs
        $color = $request->input('color', '#6C4CF1');
        $size = $validated['dimensions'];
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
        $rawPrice = (float) ($request->input('price', 0));
        $isPaid = $request->boolean('is_paid') || $rawPrice > 0;
        $price = $isPaid ? $rawPrice : 0.00;

        // Status logic: active ('approved') or 'inactive'
        $status = ($request->input('status_toggle') === 'inactive') ? 'inactive' : 'approved';

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

