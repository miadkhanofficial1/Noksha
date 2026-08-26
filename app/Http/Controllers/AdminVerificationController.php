<?php

namespace App\Http\Controllers;

use App\Models\SellerVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminVerificationController extends Controller
{
    /**
     * Display the Admin Seller Verification Review Queue with search and filters.
     */
    public function index(Request $request): View
    {
        $query = SellerVerification::with('user');

        // Status Filter
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Search Filter (Full Name, Country, or User Name/Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qUser) use ($search) {
                      $qUser->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $verifications = $query->latest()->paginate(10)->withQueryString();

        // Calculate Overview Statistics
        $pendingCount = SellerVerification::where('status', 'pending')->count();
        $approvedCount = SellerVerification::where('status', 'approved')->count();
        $rejectedCount = SellerVerification::where('status', 'rejected')->count();
        $totalCount = SellerVerification::count();

        return view('admin.verifications.index', compact(
            'verifications',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalCount'
        ));
    }

    /**
     * Show single verification details page.
     */
    public function show(SellerVerification $verification): View
    {
        $verification->load('user');

        return view('admin.verifications.show', compact('verification'));
    }

    /**
     * Approve seller identity verification.
     */
    public function approve(SellerVerification $verification): RedirectResponse
    {
        $verification->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'admin_note' => null,
            'admin_notes' => null,
        ]);

        if ($verification->user) {
            $verification->user->update([
                'is_verified' => true,
                'contributor_status' => 'approved',
            ]);

            \App\Models\Notification::send(
                $verification->user_id,
                '🎉 Contributor Verification Approved!',
                'Congratulations! Your identity has been verified. Contributor badge, verified creator shield, and creator upload tools are now unlocked.',
                'success',
                route('dashboard')
            );
        }

        return redirect()->back()
            ->with('success', '✨ Contributor "' . $verification->full_name . '" identity has been APPROVED! Contributor Badge and Creator Tools unlocked.');
    }

    /**
     * Reject seller identity verification with admin note.
     */
    public function reject(Request $request, SellerVerification $verification): RedirectResponse
    {
        $request->validate([
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $note = $request->input('admin_note', 'Documents illegible or identity could not be verified. Please re-upload clear government ID and selfie.');

        $verification->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'admin_note' => $note,
            'admin_notes' => $note,
        ]);

        if ($verification->user) {
            $verification->user->update([
                'is_verified' => false,
                'contributor_status' => 'rejected',
            ]);

            \App\Models\Notification::send(
                $verification->user_id,
                '⚠️ Contributor Verification Declined',
                'Your contributor verification application was declined: ' . $note,
                'warning',
                route('contributor.apply')
            );
        }

        return redirect()->back()
            ->with('success', '🚫 Contributor "' . $verification->full_name . '" verification application has been REJECTED.');
    }
}
