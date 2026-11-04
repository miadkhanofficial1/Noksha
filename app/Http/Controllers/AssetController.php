<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    /**
     * Store a newly created digital creative asset.
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

        // Check if entire POST payload exceeded post_max_size (PHP drops $_POST & $_FILES)
        if ($request->isMethod('post') && empty($_POST) && empty($_FILES) && (int) $request->header('Content-Length') > 0) {
            return back()->withErrors([
                'asset_file' => 'The asset file could not be uploaded. Please ensure the file is under 100MB and try again.'
            ])->withInput();
        }

        // Safely verify file validity before calling store
        if (!$request->hasFile('asset_file') || !$request->file('asset_file')->isValid()) {
            return back()->withErrors([
                'asset_file' => 'The asset file could not be uploaded. Please ensure the file is under 100MB and try again.'
            ])->withInput();
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string',
            'category_id' => 'nullable',
            'price' => 'required|numeric|min:0',
            'asset_description' => 'nullable|string',
            'description' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'tags' => 'nullable|string',
            'cover_image' => 'nullable|image|max:15360',
            'asset_file' => 'required|file|max:102400', // 100MB limit
        ], [
            'asset_file.required' => 'Digital asset source file (e.g., .psd, .ai, .fig, .pdf, .eps, .zip) is required.',
            'asset_file.file' => 'The asset file could not be uploaded. Please ensure the file is under 100MB and try again.',
            'asset_file.uploaded' => 'The asset file could not be uploaded. Please ensure the file is under 100MB and try again.',
            'asset_file.max' => 'The asset file may not be greater than 100MB.',
        ]);

        // Cover Image
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('templates/covers', 'public');
        }

        // Asset File
        $assetFile = $request->file('asset_file');
        $extension = strtolower($assetFile->getClientOriginalExtension() ?: 'zip');
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $filePath = $assetFile->storeAs('templates/packages', $filename, 'public');

        // Save Template
        $template = new Template();
        $template->user_id = auth()->id();
        $template->title = $request->title;
        $template->price = $request->price;
        $template->category = $request->category ?? $request->category_id ?? 'Graphics';
        $template->tags = $request->tags;
        $template->description = $request->asset_description ?? $request->description ?? 'High-quality digital design asset.';
        $template->thumbnail_path = $coverPath;
        $template->file_path = $filePath;
        $template->file_type = strtoupper($extension);
        $template->status = 'approved';
        $template->save();

        // Notification to Contributor
        Notification::send(
            auth()->id(),
            'Design Upload Published',
            "Your design \"{$template->title}\" was published successfully!",
            'seller',
            route('seller.dashboard') . '#designs'
        );

        // Notification to Super Admins
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();
        foreach ($adminUsers as $admin) {
            Notification::send(
                $admin->id,
                'New Resource Published',
                "Contributor " . auth()->user()->name . " published a new design \"{$template->title}\".",
                'admin',
                route('admin.resources.index')
            );
        }

        return redirect()->route('dashboard', ['mode' => 'seller', 'tab' => 'designs'])
            ->with('success', '✨ Your design was uploaded and published successfully!');
    }
}
