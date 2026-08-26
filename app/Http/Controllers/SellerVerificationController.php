<?php

namespace App\Http\Controllers;

use App\Models\SellerVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerVerificationController extends Controller
{
    /**
     * Show the seller identity verification form and status card.
     */
    public function create(): View
    {
        $verification = SellerVerification::where('user_id', auth()->id())->first();

        if (view()->exists('seller.verification')) {
            return view('seller.verification', compact('verification'));
        }

        return view('seller.verification.create', compact('verification'));
    }

    /**
     * Store or update the seller identity verification submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $existing = SellerVerification::where('user_id', auth()->id())->first();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'country' => ['required', 'string', 'max:100'],
            'document_type' => ['nullable', 'in:nid,passport,driving_license'],
            'id_type' => ['nullable', 'in:nid,passport,driving_license'],
            'document_file' => [$existing && $existing->document_file ? 'nullable' : 'required_without:id_file', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:10240'],
            'id_file' => [$existing && $existing->id_file_path ? 'nullable' : 'required_without:document_file', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:10240'],
            'selfie_file' => [$existing && ($existing->selfie_file || $existing->selfie_file_path) ? 'nullable' : 'required', 'file', 'mimes:jpeg,png,jpg', 'max:5120'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:51200'],
            'agreement' => ['required', 'accepted'],
        ], [
            'full_name.required' => 'Full name is required as stated on your government ID.',
            'date_of_birth.required' => 'Date of birth is required.',
            'document_file.required_without' => 'Government ID document file (NID/Passport/License) is required.',
            'selfie_file.required' => 'Please upload a clear selfie photo of your face.',
            'video_file.mimes' => 'Video file must be in MP4 or WEBM format.',
            'agreement.accepted' => 'You must confirm that all submitted information is accurate.',
        ]);

        $docType = $validated['document_type'] ?? $validated['id_type'] ?? 'nid';

        // File uploads handling via Laravel Storage (public disk)
        $docPath = $existing ? $existing->document_file : null;
        if ($request->hasFile('document_file')) {
            $docPath = $request->file('document_file')->store('verifications/ids', 'public');
        } elseif ($request->hasFile('id_file')) {
            $docPath = $request->file('id_file')->store('verifications/ids', 'public');
        }

        $selfiePath = $existing ? $existing->selfie_file : null;
        if ($request->hasFile('selfie_file')) {
            $selfiePath = $request->file('selfie_file')->store('verifications/selfies', 'public');
        }

        $videoPath = $existing ? $existing->video_file : null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('verifications/videos', 'public');
        }

        // Save or Update Verification Record
        SellerVerification::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'country' => $validated['country'],
                'id_type' => $docType,
                'document_type' => $docType,
                'id_file_path' => $docPath,
                'document_file' => $docPath,
                'selfie_file_path' => $selfiePath,
                'selfie_file' => $selfiePath,
                'video_file_path' => $videoPath,
                'video_file' => $videoPath,
                'status' => 'pending',
                'admin_notes' => null,
                'admin_note' => null,
                'submitted_at' => now(),
            ]
        );

        // Update user contributor_status to pending
        auth()->user()->update([
            'contributor_status' => 'pending',
        ]);

        return redirect()->route('seller.verification.create')
            ->with('success', '✨ Contributor identity verification submitted successfully! Our compliance team will review your application.');
    }
}
