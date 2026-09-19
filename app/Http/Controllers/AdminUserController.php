<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\SellerVerification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display the User & KYC Verification Hub with filters, search, and KYC review modal.
     */
    public function index(Request $request): View
    {
        $query = User::with(['verification', 'resources'])->latest();

        // Filter by Role
        if ($request->filled('role') && in_array($request->role, ['seller', 'buyer', 'user', 'admin'])) {
            if ($request->role === 'buyer') {
                $query->whereIn('role', ['buyer', 'user']);
            } else {
                $query->where('role', $request->role);
            }
        }

        // Filter by Status
        if ($request->filled('status')) {
            if ($request->status === 'pending_kyc') {
                $query->whereHas('verification', function ($q) {
                    $q->where('status', 'pending');
                });
            } elseif (in_array($request->status, ['active', 'suspended'])) {
                $query->where('status', $request->status);
            }
        }

        // Search Filter (Name, Email, Username, Phone)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        // Statistics
        $totalUsers = User::count();
        $totalSellers = User::where('role', 'seller')->count();
        $totalBuyers = User::whereIn('role', ['user', 'buyer'])->count();
        $totalSuspended = User::where('status', 'suspended')->count();
        $totalPendingKyc = SellerVerification::where('status', 'pending')->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalSellers',
            'totalBuyers',
            'totalSuspended',
            'totalPendingKyc'
        ));
    }

    /**
     * Suspend or activate a user account.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot suspend your own Super Admin account.');
        }

        $newStatus = $user->status === 'suspended' ? 'active' : 'suspended';
        $user->update(['status' => $newStatus]);

        $statusMsg = $newStatus === 'suspended'
            ? "User account \"{$user->name}\" has been SUSPENDED."
            : "User account \"{$user->name}\" has been ACTIVATED.";

        return redirect()->back()->with('success', $statusMsg);
    }

    /**
     * Approve KYC Identity Verification for a user.
     */
    public function approveKyc(User $user): RedirectResponse
    {
        $verification = $user->verification;
        if ($verification) {
            $verification->update([
                'status' => 'approved',
                'reviewed_at' => now(),
                'admin_note' => null,
                'admin_notes' => null,
            ]);
        }

        $user->update([
            'is_verified' => true,
            'contributor_status' => 'approved',
        ]);

        Notification::send(
            $user->id,
            '🎉 KYC Identity Verification Approved!',
            'Your identity verification documents have been approved by Super Admin compliance. Your creator badge and marketplace seller tools are now fully active.',
            'seller',
            route('seller.dashboard')
        );

        return redirect()->back()
            ->with('success', "✨ KYC identity for \"{$user->name}\" has been APPROVED! Verified creator privileges unlocked.");
    }

    /**
     * Reject KYC Identity Verification for a user.
     */
    public function rejectKyc(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'admin_note' => ['required', 'string', 'max:1000'],
        ]);

        $verification = $user->verification;
        if ($verification) {
            $verification->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
                'admin_note' => $validated['admin_note'],
                'admin_notes' => $validated['admin_note'],
            ]);
        }

        $user->update([
            'is_verified' => false,
            'contributor_status' => 'rejected',
        ]);

        Notification::send(
            $user->id,
            '⚠️ KYC Verification Declined',
            'Your KYC verification was declined: ' . $validated['admin_note'],
            'warning',
            route('seller.verification.create')
        );

        return redirect()->back()
            ->with('success', "🚫 KYC verification for \"{$user->name}\" was REJECTED with note recorded.");
    }
}
