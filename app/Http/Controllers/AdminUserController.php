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
        $query = User::where('is_admin', false)
            ->where('role', '!=', 'admin')
            ->where('role', '!=', 'super_admin')
            ->with(['verification', 'resources'])
            ->latest();

        // Filter by Role
        if ($request->filled('role')) {
            if ($request->role === 'seller') {
                $query->where(function ($q) {
                    $q->where('is_contributor', true)
                      ->orWhere('role', 'seller');
                });
            } elseif (in_array($request->role, ['buyer', 'user'])) {
                $query->where('is_contributor', false)
                      ->where('role', '!=', 'seller');
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

        // Statistics (Strictly Exclude Admins)
        $totalUsers = User::where('is_admin', false)->where('role', '!=', 'admin')->where('role', '!=', 'super_admin')->count();
        $totalSellers = User::where('is_admin', false)->where('role', '!=', 'admin')->where('role', '!=', 'super_admin')->where('is_contributor', true)->count();
        $totalBuyers = User::where('is_admin', false)->where('role', '!=', 'admin')->where('role', '!=', 'super_admin')->where('is_contributor', false)->count();
        $totalSuspended = User::where('is_admin', false)->where('role', '!=', 'admin')->where('role', '!=', 'super_admin')->where('status', 'suspended')->count();
        $totalPendingKyc = SellerVerification::where('status', 'pending')
            ->whereHas('user', function ($q) {
                $q->where('is_admin', false)->where('role', '!=', 'admin')->where('role', '!=', 'super_admin');
            })->count();
        $pendingKycUsers = User::where('is_admin', false)
            ->where('role', '!=', 'admin')
            ->where('role', '!=', 'super_admin')
            ->whereHas('verification', function ($q) {
                $q->where('status', 'pending');
            })->with(['verification', 'resources'])->latest()->get();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalSellers',
            'totalBuyers',
            'totalSuspended',
            'totalPendingKyc',
            'pendingKycUsers'
        ));
    }

    /**
     * Suspend or activate a user account.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id() || $user->isAdmin()) {
            return redirect()->back()->with('error', 'Administrator accounts cannot be modified here.');
        }

        $newStatus = $user->status === 'suspended' ? 'active' : 'suspended';
        $user->update(['status' => $newStatus]);

        $statusMsg = $newStatus === 'suspended'
            ? "User account \"{$user->name}\" has been SUSPENDED."
            : "User account \"{$user->name}\" has been ACTIVATED.";

        return redirect()->back()->with('success', $statusMsg);
    }

    /**
     * Approve KYC Identity Verification for a user and upgrade to Contributor.
     */
    public function approveKyc(User $user): RedirectResponse
    {
        $verification = $user->verification;
        if ($verification) {
            $verification->update([
                'status' => 'approved',
                'reviewed_at' => now(),
                'rejected_at' => null,
                'rejection_reason' => null,
                'admin_note' => null,
                'admin_notes' => null,
            ]);
        }

        $user->update([
            'is_verified' => true,
            'is_contributor' => true,
            'contributor_status' => 'approved',
            'kyc_rejected_at' => null,
            'kyc_rejection_reason' => null,
            'role' => in_array($user->role, ['admin', 'super_admin']) ? $user->role : 'seller',
        ]);

        Notification::send(
            $user->id,
            '🎉 Contributor KYC Approved!',
            'Congratulations! Your Contributor KYC application has been approved. The Verified Creator badge is now active on your profile and Seller Studio is unlocked.',
            'seller',
            route('dashboard') . '?mode=seller'
        );

        return redirect()->back()
            ->with('success', "✨ Contributor KYC for \"{$user->name}\" has been APPROVED! Verified Creator & Pro Author badges unlocked.");
    }

    /**
     * Reject KYC Identity Verification for a user with 7-day cooldown.
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
                'rejected_at' => now(),
                'rejection_reason' => $validated['admin_note'],
                'admin_note' => $validated['admin_note'],
                'admin_notes' => $validated['admin_note'],
            ]);
        }

        $user->update([
            'is_verified' => false,
            'is_contributor' => false,
            'contributor_status' => 'rejected',
            'kyc_rejected_at' => now(),
            'kyc_rejection_reason' => $validated['admin_note'],
        ]);

        $reapplyDate = now()->addDays(7)->format('M d, Y');
        Notification::send(
            $user->id,
            '⚠️ Contributor KYC Application Declined',
            "Your Contributor KYC application was declined. Reason: {$validated['admin_note']}. You may submit a new application after {$reapplyDate}.",
            'warning',
            route('dashboard') . '?mode=seller'
        );

        return redirect()->back()
            ->with('success', "🚫 KYC verification for \"{$user->name}\" has been REJECTED. 7-day cooldown applied until {$reapplyDate}.");
    }

    /**
     * Securely serve KYC identity documents, selfies, and verification media to authorized administrators.
     */
    public function viewKycDocument(Request $request, string $type, string $filename)
    {
        // 1. Authorize: Only authenticated administrators can view KYC documents
        if (!auth()->check() || (!auth()->user()->is_admin && auth()->user()->role !== 'admin' && auth()->user()->role !== 'super_admin')) {
            abort(403, 'Unauthorized. Administrator privileges required to access KYC records.');
        }

        // 2. Sanitize inputs to prevent directory traversal
        $safeFilename = basename($filename);
        $safeType = strtolower(preg_replace('/[^a-z0-9_-]/', '', $type));

        $allowedTypes = ['ids', 'selfies', 'videos', 'documents'];
        if (!in_array($safeType, $allowedTypes, true)) {
            abort(400, 'Invalid KYC document category.');
        }

        // 3. Search candidate storage paths
        $candidatePaths = [
            storage_path("app/public/verifications/{$safeType}/{$safeFilename}"),
            storage_path("app/public/verifications/{$safeFilename}"),
            storage_path("app/verifications/{$safeType}/{$safeFilename}"),
            storage_path("app/verifications/{$safeFilename}"),
            storage_path("app/private/verifications/{$safeType}/{$safeFilename}"),
            storage_path("app/public/{$safeType}/{$safeFilename}"),
            public_path("storage/verifications/{$safeType}/{$safeFilename}"),
            public_path("storage/verifications/{$safeFilename}"),
        ];

        $filePath = null;
        foreach ($candidatePaths as $path) {
            if (file_exists($path) && is_file($path)) {
                $filePath = $path;
                break;
            }
        }

        // 4. Fallback if file does not physically exist on disk (e.g. seeded demo or missing upload)
        if (!$filePath) {
            // For images (selfies or ID scans), return a fallback placeholder if available
            $extension = strtolower(pathinfo($safeFilename, PATHINFO_EXTENSION));
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                $fallbackImage = public_path('assets/images/placeholder-avatar.png');
                if (file_exists($fallbackImage)) {
                    return response()->file($fallbackImage, [
                        'Content-Type' => 'image/png',
                        'Cache-Control' => 'no-cache, private',
                    ]);
                }
            }

            abort(404, "The requested KYC document ({$safeFilename}) was not found in storage.");
        }

        // 5. Determine Content-Type and return file response
        $mimeType = match (strtolower(pathinfo($filePath, PATHINFO_EXTENSION))) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            default => (function_exists('mime_content_type') ? @mime_content_type($filePath) : null) ?: 'application/octet-stream',
        };

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $safeFilename . '"',
            'Cache-Control' => 'private, no-cache, must-revalidate',
        ]);
    }
}
