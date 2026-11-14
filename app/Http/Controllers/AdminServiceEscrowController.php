<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Notification;
use App\Models\PlatformReview;
use App\Models\Resource;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminServiceEscrowController extends Controller
{
    /**
     * Ensure the authenticated user has administrative privileges.
     */
    protected function checkAdminAuthorization(): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized. Please sign in to access the administrative panel.');
        }

        $isAdmin = false;
        if (method_exists($user, 'isAdmin')) {
            $isAdmin = $user->isAdmin();
        } elseif (isset($user->is_admin) && $user->is_admin) {
            $isAdmin = true;
        } elseif (in_array($user->role ?? '', ['admin', 'super_admin'])) {
            $isAdmin = true;
        }

        abort_unless($isAdmin, 403, 'Forbidden. You do not have permission to access the Services & Escrow Hub.');
    }

    /**
     * Display the 3-Tab Services & Escrow Hub command center.
     */
    public function index(Request $request): View
    {
        $this->checkAdminAuthorization();

        $activeTab = $request->query('tab', 'creators');

        // ========================================================
        // 1. KPI SUMMARY METRICS
        // ========================================================
        $escrowHeldStatuses = ['in_progress', 'funded_in_progress', 'delivered_preview', 'delivered', 'disputed'];
        $escrowHeld = (float) ServiceOrder::whereIn('status', $escrowHeldStatuses)->sum('amount');
        $completedVolume = (float) ServiceOrder::where('status', 'completed')->sum('amount');
        $totalCommission = (float) ServiceOrder::where('status', 'completed')->sum('platform_fee');
        $activeOrdersCount = ServiceOrder::whereIn('status', $escrowHeldStatuses)->count();
        $disputedOrdersCount = ServiceOrder::where('status', 'disputed')->count();
        $unresolvedComplaintsCount = PlatformReview::where('is_complaint', true)->where('admin_resolved', false)->count();
        $totalDisputesCount = $disputedOrdersCount + $unresolvedComplaintsCount;
        $totalServicesCount = Service::count();

        // ========================================================
        // 2. TAB 1: CREATOR SALES & SERVICE CATALOGUE
        // ========================================================
        $creatorSearch = trim($request->query('creator_q', ''));
        $creatorsQuery = User::where(function ($q) {
            $q->where('is_contributor', true)
              ->orWhereHas('services')
              ->orWhereHas('sellerOrders');
        })
        ->with([
            'services' => fn($q) => $q->latest(),
            'sellerOrders' => fn($q) => $q->with('buyer')->latest()->take(10),
            'receivedReviews' => fn($q) => $q->with('reviewer')->latest()->take(5),
            'resources' => fn($q) => $q->select('id', 'user_id', 'title', 'downloads', 'views', 'price', 'is_paid'),
        ]);

        if (!empty($creatorSearch)) {
            $creatorsQuery->where(function ($q) use ($creatorSearch) {
                $q->where('name', 'like', "%{$creatorSearch}%")
                  ->orWhere('username', 'like', "%{$creatorSearch}%")
                  ->orWhere('email', 'like', "%{$creatorSearch}%");
            });
        }

        $creators = $creatorsQuery->latest()->get()->map(function ($creator) {
            $services = $creator->services;
            $servicesCount = $services->count();
            $activeServicesCount = $services->where('status', 'active')->count();

            $completedOrders = $creator->sellerOrders->where('status', 'completed');
            $completedOrdersCount = $completedOrders->count();
            $serviceVolume = (float) $completedOrders->sum('amount');

            // Template stats
            $resources = $creator->resources;
            $downloadsCount = (int) $resources->sum('downloads');
            $templateSalesVolume = (float) $resources->where('is_paid', true)->sum(function ($r) {
                return ((float) $r->price) * ((int) $r->downloads);
            });

            // Commission generated (15% from services + 15% from template sales)
            $serviceCommission = (float) $completedOrders->sum('platform_fee');
            if ($serviceCommission <= 0 && $serviceVolume > 0) {
                $serviceCommission = round($serviceVolume * 0.15, 2);
            }
            $templateCommission = round($templateSalesVolume * 0.15, 2);
            $totalCommission = $serviceCommission + $templateCommission;

            // Rating & Demerits
            $avgRating = $creator->average_rating;
            $reviewsCount = $creator->total_reviews_count;
            $demerits = (int) ($creator->demerit_points ?? 0);
            $hasWarning = $creator->hasReliabilityWarning();

            return [
                'user' => $creator,
                'services_count' => $servicesCount,
                'active_services_count' => $activeServicesCount,
                'completed_orders_count' => $completedOrdersCount,
                'service_volume' => $serviceVolume,
                'downloads_count' => $downloadsCount,
                'template_sales_volume' => $templateSalesVolume,
                'total_commission' => $totalCommission,
                'avg_rating' => $avgRating,
                'reviews_count' => $reviewsCount,
                'demerit_points' => $demerits,
                'has_warning' => $hasWarning,
                'services' => $services,
                'recent_orders' => $creator->sellerOrders->take(5),
            ];
        });

        // ========================================================
        // 3. TAB 2: LIVE ESCROW, ORDERS & COMMISSION LEDGER
        // ========================================================
        $orderFilter = $request->query('order_status', 'all');
        $orderSearch = trim($request->query('order_q', ''));

        $ordersQuery = ServiceOrder::with(['buyer', 'seller', 'service', 'reviews'])
            ->latest();

        if ($orderFilter === 'in_progress') {
            $ordersQuery->whereIn('status', ['in_progress', 'funded_in_progress']);
        } elseif ($orderFilter === 'delivered') {
            $ordersQuery->whereIn('status', ['delivered_preview', 'delivered']);
        } elseif ($orderFilter === 'completed') {
            $ordersQuery->where('status', 'completed');
        } elseif ($orderFilter === 'disputed') {
            $ordersQuery->where('status', 'disputed');
        } elseif ($orderFilter === 'refunded') {
            $ordersQuery->whereIn('status', ['refunded', 'cancelled']);
        }

        if (!empty($orderSearch)) {
            $ordersQuery->where(function ($q) use ($orderSearch) {
                $q->where('order_code', 'like', "%{$orderSearch}%")
                  ->orWhere('order_number', 'like', "%{$orderSearch}%")
                  ->orWhere('custom_title', 'like', "%{$orderSearch}%")
                  ->orWhereHas('buyer', fn($bq) => $bq->where('name', 'like', "%{$orderSearch}%")->orWhere('email', 'like', "%{$orderSearch}%"))
                  ->orWhereHas('seller', fn($sq) => $sq->where('name', 'like', "%{$orderSearch}%")->orWhere('email', 'like', "%{$orderSearch}%"));
            });
        }

        $orders = $ordersQuery->paginate(15, ['*'], 'orders_page');

        // ========================================================
        // 4. TAB 3: DISPUTES & EVIDENCE INSPECTOR
        // ========================================================
        $disputedOrders = ServiceOrder::where('status', 'disputed')
            ->with(['buyer', 'seller', 'service', 'conversation'])
            ->latest()
            ->get();

        $complaintReviews = PlatformReview::where('is_complaint', true)
            ->with(['reviewer', 'creator', 'service', 'serviceOrder', 'asset'])
            ->latest()
            ->get();

        return view('admin.services-hub.index', compact(
            'activeTab',
            'escrowHeld',
            'completedVolume',
            'totalCommission',
            'activeOrdersCount',
            'disputedOrdersCount',
            'unresolvedComplaintsCount',
            'totalDisputesCount',
            'totalServicesCount',
            'creators',
            'creatorSearch',
            'orders',
            'orderFilter',
            'orderSearch',
            'disputedOrders',
            'complaintReviews'
        ));
    }

    /**
     * Resolve a disputed service order (100% Buyer Refund or 85% Creator Release).
     */
    public function resolveDispute(ServiceOrder $order, Request $request): RedirectResponse
    {
        $this->checkAdminAuthorization();

        $request->validate([
            'resolution' => 'required|in:refund_buyer,release_to_creator',
            'admin_notes' => 'nullable|string|max:1000',
            'demerit_action' => 'nullable|in:no_change,add_25,clear_demerits',
        ]);

        $resolution = $request->input('resolution');
        $adminNotes = trim($request->input('admin_notes', ''));
        $demeritAction = $request->input('demerit_action', 'no_change');

        $totalAmount = (float) ($order->amount ?? $order->total_amount);
        $commissionRate = (float) ($order->platform_commission_rate ?: 15.00);
        $platformFee = round($totalAmount * ($commissionRate / 100), 2);
        $sellerPayout = round($totalAmount - $platformFee, 2);

        DB::beginTransaction();
        try {
            if ($resolution === 'refund_buyer') {
                // 1. Credit 100% of order total back to buyer wallet
                $buyerWallet = $order->buyer->wallet ?? Wallet::firstOrCreate(['user_id' => $order->buyer_id], ['balance' => 0.00]);
                $buyerWallet->deposit(
                    $totalAmount,
                    "Full Escrow Refund for Disputed Order #{$order->order_code} (Noksha Admin Arbitration)",
                    'refund'
                );

                // 2. Update order status
                $order->update([
                    'status' => 'refunded',
                    'completed_at' => null,
                ]);

                // 3. Demerit action
                if ($demeritAction === 'add_25') {
                    $order->seller?->recordDemerit(25);
                }

                // 4. Notify buyer and seller
                Notification::create([
                    'user_id' => $order->buyer_id,
                    'title' => '🛡️ Escrow Dispute Resolved: 100% Refunded',
                    'message' => "Arbitration completed for order #{$order->order_code}. ৳" . number_format($totalAmount, 2) . " has been refunded to your wallet.",
                    'type' => 'wallet',
                    'action_url' => route('inbox.show', $order->conversation_id),
                ]);

                Notification::create([
                    'user_id' => $order->seller_id,
                    'title' => '⚠️ Dispute Decided: Order Refunded',
                    'message' => "Noksha arbitration closed order #{$order->order_code} with a refund to the client." . ($adminNotes ? " Note: {$adminNotes}" : ''),
                    'type' => 'system',
                    'action_url' => route('inbox.show', $order->conversation_id),
                ]);

                // 5. Post system message in chat
                Message::create([
                    'conversation_id' => $order->conversation_id,
                    'sender_id' => auth()->id(),
                    'body' => "🛡️ Noksha Arbitration Decision: Dispute resolved in favor of the buyer. 100% escrow funds (৳" . number_format($totalAmount, 2) . ") refunded to buyer wallet." . ($adminNotes ? " Admin Notes: {$adminNotes}" : ''),
                    'message_type' => 'order_status',
                    'is_read' => false,
                    'metadata' => [
                        'type' => 'arbitration_resolved',
                        'decision' => 'refunded_to_buyer',
                        'order_id' => $order->id,
                        'notes' => $adminNotes,
                    ],
                ]);

                $flashMsg = "Dispute resolved successfully. 100% escrow (৳" . number_format($totalAmount, 2) . ") refunded to buyer wallet.";
            } else {
                // Release 85% to creator earnings
                $sellerWallet = $order->seller->wallet ?? Wallet::firstOrCreate(['user_id' => $order->seller_id], ['balance' => 0.00]);
                $sellerWallet->creditEarnings(
                    $sellerPayout,
                    "Admin arbitration release for Order #{$order->order_code} (15% platform commission retained)",
                    'service_order',
                    $order->id
                );

                $order->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'platform_fee' => $platformFee,
                    'seller_payout' => $sellerPayout,
                    'seller_payout_amount' => $sellerPayout,
                ]);

                // Demerit action: clear disputed penalty if creator delivery was upheld
                if ($demeritAction === 'clear_demerits') {
                    $currentPoints = max(0, ((int) $order->seller->demerit_points) - 25);
                    $order->seller->update([
                        'demerit_points' => $currentPoints,
                        'reliability_warning' => $currentPoints >= 20,
                    ]);
                }

                Notification::create([
                    'user_id' => $order->seller_id,
                    'title' => '🎉 Dispute Resolved: Payout Released',
                    'message' => "Arbitration upheld delivery on order #{$order->order_code}. ৳" . number_format($sellerPayout, 2) . " credited to your earnings.",
                    'type' => 'wallet',
                    'action_url' => route('inbox.show', $order->conversation_id),
                ]);

                Notification::create([
                    'user_id' => $order->buyer_id,
                    'title' => '🛡️ Arbitration Completed: Delivery Upheld',
                    'message' => "Arbitration for order #{$order->order_code} has concluded and verified files have been unlocked in chat.",
                    'type' => 'system',
                    'action_url' => route('inbox.show', $order->conversation_id),
                ]);

                Message::create([
                    'conversation_id' => $order->conversation_id,
                    'sender_id' => auth()->id(),
                    'body' => "🛡️ Noksha Arbitration Decision: Delivery verified and approved. Escrow payment released to creator (৳" . number_format($sellerPayout, 2) . ")." . ($adminNotes ? " Admin Notes: {$adminNotes}" : ''),
                    'message_type' => 'order_status',
                    'is_read' => false,
                    'metadata' => [
                        'type' => 'arbitration_resolved',
                        'decision' => 'released_to_creator',
                        'order_id' => $order->id,
                        'notes' => $adminNotes,
                    ],
                ]);

                $flashMsg = "Dispute resolved successfully. Escrow released to creator (৳" . number_format($sellerPayout, 2) . ") with 15% platform fee retained.";
            }

            // Mark any attached complaint reviews as admin resolved
            PlatformReview::where('service_order_id', $order->id)->update(['admin_resolved' => true]);

            DB::commit();

            return redirect()->route('admin.services-hub.index', ['tab' => 'disputes'])
                ->with('success', $flashMsg);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Dispute resolution failed: ' . $e->getMessage());
        }
    }

    /**
     * Adjust creator demerits and reliability warning flags.
     */
    public function adjustDemerits(User $creator, Request $request): RedirectResponse
    {
        $this->checkAdminAuthorization();

        $request->validate([
            'demerit_points' => 'required|integer|min:0|max:500',
            'reliability_warning' => 'nullable|boolean',
        ]);

        $points = (int) $request->input('demerit_points');
        $warning = $request->boolean('reliability_warning');

        $creator->update([
            'demerit_points' => $points,
            'reliability_warning' => $warning,
        ]);

        Notification::create([
            'user_id' => $creator->id,
            'title' => '🛡️ Account Standing Updated',
            'message' => "Noksha moderation team updated your creator status: {$points} demerit points recorded." . ($warning ? " Reliability warning active." : ""),
            'type' => 'system',
            'action_url' => route('creator.profile', $creator->username),
        ]);

        return redirect()->back()->with('success', "Demerits updated for creator {$creator->name}. Current points: {$points}.");
    }

    /**
     * Toggle a creator's service between active and paused (or suspended).
     */
    public function toggleServiceStatus(Service $service, Request $request): RedirectResponse
    {
        $this->checkAdminAuthorization();

        $currentStatus = $service->status;
        $newStatus = $currentStatus === 'active' ? 'paused' : 'active';

        $service->update(['status' => $newStatus]);

        Notification::create([
            'user_id' => $service->user_id,
            'title' => 'Service Status Changed',
            'message' => "Your service \"{$service->title}\" is now {$newStatus} by Noksha administration.",
            'type' => 'system',
            'action_url' => route('creator.profile', $service->user?->username),
        ]);

        return redirect()->back()->with('success', "Service \"{$service->title}\" status toggled to {$newStatus}.");
    }

    /**
     * Resolve or dismiss a complaint platform review.
     */
    public function resolveComplaintReview(PlatformReview $review, Request $request): RedirectResponse
    {
        $this->checkAdminAuthorization();

        $dismissComplaint = $request->boolean('dismiss_complaint');

        $review->update([
            'admin_resolved' => true,
            'is_complaint' => $dismissComplaint ? false : $review->is_complaint,
        ]);

        return redirect()->back()->with('success', "Complaint review #{$review->id} marked as resolved.");
    }
}
