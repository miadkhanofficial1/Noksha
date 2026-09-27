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
        // 1. Enforce 7-Day Rejection Cooldown Lock
        if (auth()->user()->isKycRejectedInCooldown()) {
            $remainingDate = auth()->user()->getKycCooldownRemainingDate();
            $reason = auth()->user()->getKycRejectionReason();
            $msg = "Your application was rejected. Reason: {$reason}. You can reapply after {$remainingDate}.";

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->to(route('dashboard') . '?mode=seller')->with('error', $msg);
        }

        $existing = SellerVerification::where('user_id', auth()->id())->first();

        // 2. Strict Anti-Fraud Validation Rules
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'id_number' => ['required', 'string', 'max:100'],
            'portfolio_link' => ['required', 'url', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'country' => ['nullable', 'string', 'max:100'],
            'document_type' => ['nullable', 'in:nid,passport,driving_license'],
            'document_file' => [
                $existing && $existing->document_file ? 'nullable' : 'required',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:10240'
            ],
            'selfie_file' => [
                $existing && $existing->selfie_file ? 'nullable' : 'required',
                'file',
                'mimes:jpeg,png,jpg',
                'max:5120'
            ],
            'agreement' => ['required', 'accepted'],
        ];

        $messages = [
            'full_name.required' => 'Full legal name is required as stated on your government ID.',
            'phone.required' => 'Phone or WhatsApp number is required.',
            'id_number.required' => 'National ID or Passport number is required.',
            'portfolio_link.required' => 'Portfolio link (Behance, Dribbble, or personal site) is required.',
            'portfolio_link.url' => 'Please enter a valid portfolio URL.',
            'document_file.required' => 'Government ID or Passport scan is required.',
            'selfie_file.required' => 'Face selfie holding the NID card is required for identity verification.',
            'agreement.accepted' => 'You must accept the contributor guidelines and copyright terms.',
        ];

        $validated = $request->validate($rules, $messages);

        $docType = $validated['document_type'] ?? 'nid';
        $country = $validated['country'] ?? 'Bangladesh';
        $dob = $validated['date_of_birth'] ?? now()->subYears(22)->format('Y-m-d');

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

        // Formatted admin notes combining portfolio and ID number
        $adminNotes = "ID/Passport: {$validated['id_number']} | Portfolio: {$validated['portfolio_link']}";

        // Save or Update Verification Record
        SellerVerification::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'full_name' => $validated['full_name'],
                'date_of_birth' => $dob,
                'country' => $country,
                'id_type' => $docType,
                'document_type' => $docType,
                'id_number' => $validated['id_number'],
                'portfolio_link' => $validated['portfolio_link'],
                'id_file_path' => $docPath,
                'document_file' => $docPath,
                'selfie_file_path' => $selfiePath,
                'selfie_file' => $selfiePath,
                'status' => 'pending',
                'admin_notes' => $adminNotes,
                'admin_note' => $adminNotes,
                'rejection_reason' => null,
                'rejected_at' => null,
                'submitted_at' => now(),
            ]
        );

        // Update user: contributor_status to pending and clear previous rejection cooldown
        $userUpdates = [
            'contributor_status' => 'pending',
            'kyc_rejected_at' => null,
            'kyc_rejection_reason' => null,
            'phone' => $validated['phone'],
        ];
        auth()->user()->update($userUpdates);

        // Notify Admins about new KYC application
        $adminUsers = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->get();
        foreach ($adminUsers as $admin) {
            \App\Models\Notification::send(
                $admin->id,
                'New Contributor KYC Application',
                "User " . auth()->user()->name . " submitted a Contributor KYC application for review.",
                'admin',
                route('admin.users.index') . '?status=pending_kyc'
            );
        }

        $msg = 'Your Contributor KYC application has been submitted and is under review.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return redirect()->to(route('dashboard') . '?mode=seller')->with('success', $msg);
    }
}
