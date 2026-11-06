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

        // 1. Anti-Malware Executable Extension Drop Guard (.php, .phtml, .exe, .sh, .bat, .js, .phar)
        $maliciousExtensions = ['php', 'phtml', 'phar', 'exe', 'sh', 'bat', 'js', 'cmd', 'vbs', 'cgi', 'pl', 'asp', 'aspx'];
        foreach ($request->allFiles() as $fieldKey => $fileItem) {
            if (!$fileItem) continue;
            $filesToCheck = is_array($fileItem) ? $fileItem : [$fileItem];
            foreach ($filesToCheck as $f) {
                $ext = strtolower($f->getClientOriginalExtension());
                $origName = strtolower($f->getClientOriginalName());
                foreach ($maliciousExtensions as $badExt) {
                    if ($ext === $badExt || str_ends_with($origName, '.' . $badExt) || str_contains($origName, '.' . $badExt . '.')) {
                        return back()->withErrors([
                            $fieldKey => 'Files with executable or malicious extensions (.php, .phtml, .exe, .sh, .bat, .js, .phar) are strictly prohibited.'
                        ])->withInput();
                    }
                }
            }
        }

        // Safely verify file validity before calling store
        $assetFile = $request->file('asset_file') ?? $request->file('asset_archive');
        if (!$assetFile || !$assetFile->isValid()) {
            return back()->withErrors([
                'asset_file' => 'The asset file could not be uploaded. Please ensure the file is under 100MB and try again.'
            ])->withInput();
        }

        // 2. Strict Multipart MIME-Type & Extension Enforcement
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string',
            'category_id' => 'nullable',
            'price' => 'required|numeric|min:0',
            'asset_description' => 'nullable|string',
            'description' => 'nullable|string',
            'dimensions' => 'nullable|string',
            'tags' => 'nullable|string',
            'cover_image' => 'required|file|mimes:jpeg,png,webp,jpg|max:5120',
            'asset_file' => 'required_without:asset_archive|file|mimetypes:application/zip,application/x-zip-compressed,multipart/x-zip|max:102400',
            'asset_archive' => 'nullable|file|mimetypes:application/zip,application/x-zip-compressed,multipart/x-zip|max:102400',
        ], [
            'cover_image.required' => 'Cover image is required.',
            'cover_image.mimes' => 'Cover image must be a JPEG, PNG, WEBP, or JPG image.',
            'cover_image.max' => 'Cover image may not be greater than 5MB.',
            'asset_file.required' => 'Encapsulated asset archive (.zip) is required.',
            'asset_file.mimetypes' => 'The asset source file must be a valid encapsulated archive (.zip).',
            'asset_file.max' => 'The asset archive may not be greater than 100MB.',
            'asset_archive.mimetypes' => 'The asset source file must be a valid encapsulated archive (.zip).',
            'asset_archive.max' => 'The asset archive may not be greater than 100MB.',
        ]);

        // Cover Image
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('templates/covers', 'public');
        }

        // Asset File
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
