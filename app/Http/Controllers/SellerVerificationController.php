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
            'full_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'id_number' => ['nullable', 'string', 'max:100'],
            'nid_or_passport_number' => ['nullable', 'string', 'max:100'],
            'portfolio_link' => ['required', 'url', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'country' => ['nullable', 'string', 'max:100'],
            'document_type' => ['nullable', 'in:nid,passport,driving_license'],
            'document_file' => [
                $existing && ($existing->document_file || auth()->user()->kyc_document_path) ? 'nullable' : 'required_without:kyc_document',
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:10240'
            ],
            'kyc_document' => [
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:10240'
            ],
            'selfie_file' => [
                'nullable',
                'file',
                'mimes:jpeg,png,jpg',
                'max:5120'
            ],
            'contributor_bio' => ['nullable', 'string', 'max:1000'],
            'agreement' => ['nullable'],
        ];

        $messages = [
            'portfolio_link.required' => 'Portfolio link (Behance, Dribbble, or personal site) is required.',
            'portfolio_link.url' => 'Please enter a valid portfolio URL.',
            'document_file.required_without' => 'Government ID or Passport document is required.',
        ];

        $validated = $request->validate($rules, $messages);

        $idNumber = $validated['nid_or_passport_number'] ?? $validated['id_number'] ?? $request->input('nid_or_passport_number') ?? $request->input('id_number');
        if (empty($idNumber)) {
            return back()->withErrors(['nid_or_passport_number' => 'National ID or Passport number is required.'])->withInput();
        }

        $fullName = $validated['full_name'] ?? auth()->user()->name;
        $phone = $validated['phone'] ?? auth()->user()->phone ?? 'N/A';
        $docType = $validated['document_type'] ?? 'nid';
        $country = $validated['country'] ?? 'Bangladesh';
        $dob = $validated['date_of_birth'] ?? now()->subYears(22)->format('Y-m-d');

        // File uploads handling via Laravel Storage (public disk)
        $docPath = $existing ? ($existing->document_file ?? auth()->user()->kyc_document_path) : auth()->user()->kyc_document_path;
        if ($request->hasFile('document_file')) {
            $docPath = $request->file('document_file')->store('verifications/ids', 'public');
        } elseif ($request->hasFile('kyc_document')) {
            $docPath = $request->file('kyc_document')->store('verifications/ids', 'public');
        } elseif ($request->hasFile('id_file')) {
            $docPath = $request->file('id_file')->store('verifications/ids', 'public');
        }

        $selfiePath = $existing ? $existing->selfie_file : null;
        if ($request->hasFile('selfie_file')) {
            $selfiePath = $request->file('selfie_file')->store('verifications/selfies', 'public');
        }

        // Formatted admin notes combining portfolio and ID number
        $adminNotes = "ID/Passport: {$idNumber} | Portfolio: {$validated['portfolio_link']}";

        // Save or Update Verification Record
        SellerVerification::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'full_name' => $fullName,
                'date_of_birth' => $dob,
                'country' => $country,
                'id_type' => $docType,
                'document_type' => $docType,
                'id_number' => $idNumber,
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
            'nid_or_passport_number' => $idNumber,
            'portfolio_link' => $validated['portfolio_link'],
            'kyc_document_path' => $docPath,
            'contributor_bio' => $request->input('contributor_bio') ?? $request->input('bio'),
            'kyc_rejected_at' => null,
            'kyc_rejection_reason' => null,
            'phone' => $phone,
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
