<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Resource;
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
        $categories = Category::all();

        return view('resource.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage and database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'preview_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // Max 5MB
            'resource_file' => ['required', 'file', 'mimes:zip,rar,psd,ai,svg,pdf', 'max:51200'], // Max 50MB
            'is_paid' => ['required', 'boolean'],
            'price' => ['required_if:is_paid,1,true', 'nullable', 'numeric', 'min:0'],
            'tags' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'demo_link' => ['nullable', 'url', 'max:255'],
        ], [
            'title.required' => 'Resource title is required.',
            'description.required' => 'Product description is required.',
            'preview_image.required' => 'Please upload a preview image for your asset.',
            'preview_image.image' => 'Preview image must be a valid image file (JPG, PNG, WEBP).',
            'resource_file.required' => 'Please upload the main resource file (ZIP, PSD, AI, SVG).',
            'price.required_if' => 'Price is required when resource is marked as Paid.',
        ]);

        // Process File Storage
        $previewPath = $request->file('preview_image')->store('previews', 'public');
        $filePath = $request->file('resource_file')->store('resources', 'public');
        $fileExtension = strtolower($request->file('resource_file')->getClientOriginalExtension());

        // Process Tags array
        $tagsArray = [];
        if (!empty($request->tags)) {
            $tagsArray = array_map('trim', explode(',', $request->tags));
        }

        // Generate unique slug
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug . '-' . Str::random(6);

        // Create Resource record
        Resource::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id ?: null,
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'preview_image' => $previewPath,
            'file_path' => $filePath,
            'file_type' => $fileExtension,
            'tags' => $tagsArray,
            'is_paid' => $request->boolean('is_paid'),
            'price' => $request->boolean('is_paid') ? ($validated['price'] ?? 0.00) : 0.00,
            'requirements' => $request->requirements,
            'demo_link' => $request->demo_link,
            'status' => 'pending',
            'downloads' => 0,
            'views' => 0,
        ]);

        return redirect()->route('resource.create')
            ->with('success', '✨ Asset uploaded successfully! Your template is pending moderation approval.');
    }
}
