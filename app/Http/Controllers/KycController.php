<?php

namespace App\Http\Controllers;

use App\Models\SellerVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KycController extends Controller
{
    /**
     * Show the KYC identity verification form.
     */
    public function create(): View
    {
        return app(SellerVerificationController::class)->create();
    }

    /**
     * Store and enforce strict multipart verification upload.
     * Enforces:
     * - KYC document: required|file|mimes:pdf,jpeg,png,jpg|max:10240
     * - Anti-Malware extension drop guard (.php, .phtml, .exe, .sh, .bat, .js, .phar)
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Anti-Malware Executable Extension Drop Guard
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

        // 2. Strict Multipart MIME-Type Enforcement for KYC Document
        $rules = [
            'kyc_document' => 'required_without_all:document_file,id_document,id_file|nullable|file|mimes:pdf,jpeg,png,jpg|max:10240',
            'document_file' => 'required_without_all:kyc_document,id_document,id_file|nullable|file|mimes:pdf,jpeg,png,jpg|max:10240',
            'id_document' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:10240',
            'id_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:10240',
            'selfie_file' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'selfie' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'portfolio_link' => 'nullable|url|max:255',
            'id_number' => 'nullable|string|max:100',
            'nid_or_passport_number' => 'nullable|string|max:100',
        ];

        $request->validate($rules, [
            'kyc_document.required_without_all' => 'Government ID or Passport KYC document is required.',
            'kyc_document.mimes' => 'KYC document must be a valid PDF, JPEG, PNG, or JPG file.',
            'kyc_document.max' => 'KYC document may not be greater than 10MB.',
            'document_file.required_without_all' => 'Government ID or Passport KYC document is required.',
            'document_file.mimes' => 'Document must be a valid PDF, JPEG, PNG, or JPG file.',
            'document_file.max' => 'Document may not be greater than 10MB.',
        ]);

        return app(SellerVerificationController::class)->store($request);
    }
}

