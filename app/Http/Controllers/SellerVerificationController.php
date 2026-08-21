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

        return view('seller.verification.create', compact('verification'));
    }

    /**
     * Store or update the seller identity verification submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'country' => ['required', 'string', 'max:100'],
            'id_type' => ['required', 'in:nid,passport,driving_license'],
            'id_file' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:10240'],
            'selfie_file' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:51200'],
            'agreement' => ['required', 'accepted'],
        ], [
            'full_name.required' => 'Full name is required as stated on your government ID.',
            'date_of_birth.required' => 'Date of birth is required.',
            'id_file.required' => 'Government ID document file (NID/Passport/License) is required.',
            'selfie_file.required' => 'Please upload a clear selfie photo of your face.',
            'agreement.accepted' => 'You must confirm that all submitted information is accurate.',
        ]);

        // Process File Storage locally via Laravel Storage
        $idPath = $request->file('id_file')->store('verifications/ids', 'public');
        $selfiePath = $request->file('selfie_file')->store('verifications/selfies', 'public');
        $videoPath = $request->hasFile('video_file') ? $request->file('video_file')->store('verifications/videos', 'public') : null;

        // Create or Update Verification Record
        SellerVerification::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'country' => $validated['country'],
                'id_type' => $validated['id_type'],
                'id_file_path' => $idPath,
                'selfie_file_path' => $selfiePath,
                'video_file_path' => $videoPath,
                'status' => 'pending',
                'admin_notes' => null,
            ]
        );

        return redirect()->route('seller.verification.create')
            ->with('success', '✨ Verification documents submitted successfully! Our compliance team will review your application.');
    }
}
