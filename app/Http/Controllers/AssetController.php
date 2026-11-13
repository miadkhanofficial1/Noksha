<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Template;
use App\Models\User;
use App\Services\NokshaFilePackagerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssetController extends Controller
{
    /**
     * Prohibited executable extensions to prevent remote script execution.
     */
    protected const MALICIOUS_EXTENSIONS = [
        'php', 'phtml', 'phar', 'exe', 'sh', 'bat', 'js', 'cmd', 'vbs', 'cgi', 'pl', 'asp', 'aspx', 'py', 'rb'
    ];

    /**
     * Allowed creative source file extensions.
     */
    protected const ALLOWED_SOURCE_EXTENSIONS = [
        'psd', 'ai', 'fig', 'eps', 'svg', 'zip'
    ];

    /**
     * Store a newly created digital creative asset using structured packaging.
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
                'source_file' => 'The asset file could not be uploaded. Please ensure the file is under 100MB and try again.'
            ])->withInput();
        }

        // 1. Anti-Malware Executable Extension Drop Guard
        foreach ($request->allFiles() as $fieldKey => $fileItem) {
            if (!$fileItem) continue;
            $filesToCheck = is_array($fileItem) ? $fileItem : [$fileItem];
            foreach ($filesToCheck as $f) {
                $ext = strtolower($f->getClientOriginalExtension());
                $origName = strtolower($f->getClientOriginalName());
                foreach (self::MALICIOUS_EXTENSIONS as $badExt) {
                    if ($ext === $badExt || str_ends_with($origName, '.' . $badExt) || str_contains($origName, '.' . $badExt . '.')) {
                        return back()->withErrors([
                            $fieldKey => 'Files with executable or script extensions (.php, .phtml, .exe, .sh, .bat, .js) are strictly prohibited.'
                        ])->withInput();
                    }
                }
            }
        }

        // Locate source file from supported input names
        $sourceFile = $request->file('source_file') ?? $request->file('asset_file') ?? $request->file('asset_archive');
        if (!$sourceFile || !$sourceFile->isValid()) {
            return back()->withErrors([
                'source_file' => 'The main source file is required and must be under 100MB.'
            ])->withInput();
        }

        // Validate source file extension (.psd, .ai, .fig, .eps, .svg, .zip)
        $sourceExt = strtolower($sourceFile->getClientOriginalExtension());
        if (!in_array($sourceExt, self::ALLOWED_SOURCE_EXTENSIONS)) {
            return back()->withErrors([
                'source_file' => 'The main source file must be one of: .PSD, .AI, .FIG, .EPS, .SVG, or .ZIP.'
            ])->withInput();
        }

        // 2. Strict Input Validation
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
            'preview_images' => 'nullable|array|max:4',
            'preview_images.*' => 'nullable|file|mimes:jpeg,png,webp,jpg|max:5120',
            'instruction_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ], [
            'cover_image.required' => 'Primary cover preview image is required.',
            'cover_image.mimes' => 'Cover image must be a JPEG, PNG, WEBP, or JPG image.',
            'cover_image.max' => 'Cover image may not be greater than 5MB.',
            'instruction_pdf.mimes' => 'Instruction guide must be a valid PDF document.',
            'instruction_pdf.max' => 'Instruction PDF may not be greater than 20MB.',
        ]);

        // 3. Assemble Previews Collection (Cover + Additional Slides)
        $previewFiles = [];
        if ($request->hasFile('cover_image')) {
            $previewFiles[] = $request->file('cover_image');
        }
        if ($request->hasFile('preview_images')) {
            foreach ($request->file('preview_images') as $additionalImg) {
                if ($additionalImg && $additionalImg->isValid() && count($previewFiles) < 5) {
                    $previewFiles[] = $additionalImg;
                }
            }
        }

        // 4. Store Structured Files via NokshaFilePackagerService
        $instructionPdf = $request->file('instruction_pdf');
        $packaged = NokshaFilePackagerService::storeStructuredFiles(
            $request->title,
            $previewFiles,
            $sourceFile,
            $instructionPdf,
            'resources'
        );

        $primaryPreviewPath = $packaged['preview_images'][0]['path'] ?? null;
        $sourceFilePath = $packaged['source_file_path'] ?? null;
        $instructionPdfPath = $packaged['instruction_pdf_path'] ?? null;
        $allPreviewPaths = $packaged['preview_image_paths'] ?? [];

        // 5. Create Template / Resource record
        $template = new Template();
        $template->user_id = auth()->id();
        $template->title = $request->title;
        $template->price = $request->price;
        $template->category = $request->category ?? $request->category_id ?? 'Graphics';
        $template->tags = $request->tags;
        $template->description = $request->asset_description ?? $request->description ?? 'High-quality digital design asset.';
        $template->thumbnail_path = $primaryPreviewPath;
        $template->preview_image = $primaryPreviewPath;
        $template->preview_images = $allPreviewPaths;
        $template->source_file_path = $sourceFilePath;
        $template->instruction_pdf_path = $instructionPdfPath;
        $template->file_path = $sourceFilePath ?? $primaryPreviewPath;
        $template->file_type = strtoupper($sourceExt ?: 'ZIP');
        $template->status = 'approved';
        $template->save();

        // 6. Notifications
        Notification::send(
            auth()->id(),
            'Design Upload Published',
            "Your design \"{$template->title}\" was packaged and published successfully!",
            'seller',
            route('seller.dashboard') . '#designs'
        );

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
            ->with('success', '✨ Your design was uploaded, packaged, and published successfully!');
    }

    /**
     * Download structured asset package on-the-fly.
     */
    public function download(Template $template): BinaryFileResponse|RedirectResponse
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Build list of all available component files in exact order: Previews -> Source -> PDF
        $filesToBundle = [];

        if (is_array($template->preview_images) && count($template->preview_images) > 0) {
            foreach ($template->preview_images as $prev) {
                $filesToBundle[] = $prev;
            }
        } elseif ($template->thumbnail_path) {
            $filesToBundle[] = $template->thumbnail_path;
        }

        if ($template->source_file_path) {
            $filesToBundle[] = $template->source_file_path;
        } elseif ($template->file_path) {
            $filesToBundle[] = $template->file_path;
        }

        if ($template->instruction_pdf_path) {
            $filesToBundle[] = $template->instruction_pdf_path;
        }

        $creatorUsername = $template->owner?->username ?? $template->user?->name ?? 'creator';

        return NokshaFilePackagerService::streamZipDownload(
            $template->title,
            $creatorUsername,
            $filesToBundle
        );
    }
}
