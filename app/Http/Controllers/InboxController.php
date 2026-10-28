<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Policies\ConversationPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InboxController extends Controller
{
    /**
     * Display the main Inbox hub.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        $conversations = $this->getUserConversations($user, $request->input('search'));

        // If user already has threads, open the most recent active thread
        if ($conversations->isNotEmpty()) {
            return redirect()->route('inbox.show', $conversations->first()->id);
        }

        return view('inbox.index', [
            'conversations' => $conversations,
            'activeConversation' => null,
            'counterpart' => null,
            'services' => collect(),
            'messages' => collect(),
            'search' => $request->input('search'),
        ]);
    }

    /**
     * Display a specific conversation or initiate a thread with a creator.
     */
    public function show(string|int $identifier, Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        // 1. Check if identifier corresponds to an existing Conversation ID
        $conversation = Conversation::with(['buyer', 'seller', 'activeOrder.service', 'latestMessage'])->find($identifier);

        // 2. If not found by conversation ID, check if identifier is a target User ID (from Creator Profile "Send Message")
        if (!$conversation) {
            $targetUser = User::find($identifier);

            if ($targetUser) {
                if ((int) $targetUser->id === (int) $user->id) {
                    return redirect()->route('inbox.index')
                        ->with('error', 'You cannot start a messaging thread with yourself.');
                }

                // Check if an existing direct thread already exists between these users
                $existing = Conversation::where(function ($q) use ($user, $targetUser) {
                    $q->where('buyer_id', $user->id)->where('seller_id', $targetUser->id);
                })->orWhere(function ($q) use ($user, $targetUser) {
                    $q->where('buyer_id', $targetUser->id)->where('seller_id', $user->id);
                })->first();

                if ($existing) {
                    return redirect()->route('inbox.show', $existing->id);
                }

                // Check authorization policy before initiating thread
                $policy = new ConversationPolicy();
                if (!$policy->initiate($user, $targetUser, 'direct')) {
                    return redirect()->route('inbox.index')->with(
                        'error',
                        'Unable to initiate conversation. Buyers can only start threads with verified creators/sellers.'
                    );
                }

                // Determine buyer/seller roles
                $buyerId = $user->id;
                $sellerId = $targetUser->id;

                if ($user->isSellerOrCreator() && !$targetUser->isSellerOrCreator()) {
                    $buyerId = $targetUser->id;
                    $sellerId = $user->id;
                }

                // Create initial thread
                $conversation = Conversation::create([
                    'buyer_id' => $buyerId,
                    'seller_id' => $sellerId,
                    'conversation_type' => 'direct',
                    'is_frozen' => false,
                    'last_message_at' => now(),
                ]);

                // Welcome system notice
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $user->id,
                    'body' => "Direct inquiry initiated with {$targetUser->name}. Welcome to Noksha secure escrow chat.",
                    'message_type' => 'system_notice',
                    'is_read' => true,
                ]);

                return redirect()->route('inbox.show', $conversation->id);
            }

            abort(404, 'Conversation or creator not found.');
        }

        // 3. Security check: user must be participant or admin
        if (!$conversation->isParticipant($user) && !$user->isAdmin()) {
            abort(403, 'Unauthorized access to this conversation.');
        }

        // 4. Mark unread messages received from counterpart as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // 5. Load full conversation relationships
        $conversation->load([
            'buyer',
            'seller',
            'activeOrder.service',
            'messages.sender',
            'messages.attachments',
        ]);

        // Auto-complete delivered order if 3 days have elapsed without dispute
        $this->checkAndAutoComplete($conversation->activeOrder);

        $counterpart = $conversation->getOtherParticipant($user);

        // 6. Fetch all conversations for sidebar
        $conversations = $this->getUserConversations($user, $request->input('search'));

        // 7. Load available services offered by the seller for the custom proposal widget
        $services = Service::where('user_id', $conversation->seller_id)
            ->where('is_active', true)
            ->get();

        return view('inbox.index', [
            'conversations' => $conversations,
            'activeConversation' => $conversation,
            'counterpart' => $counterpart,
            'services' => $services,
            'messages' => $conversation->messages,
            'search' => $request->input('search'),
        ]);
    }

    /**
     * Freeze conversation when a participant leaves.
     */
    public function freeze(Conversation $conversation): RedirectResponse
    {
        $user = Auth::user();

        if (!$conversation->isParticipant($user) && !$user->isAdmin()) {
            abort(403);
        }

        $conversation->freeze($user);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => "{$user->name} has left the conversation. The thread is now frozen.",
            'message_type' => 'system_notice',
            'is_read' => true,
        ]);

        return back()->with('info', 'You have left the conversation. The thread is now frozen.');
    }

    /**
     * Request reopening of a frozen conversation.
     */
    public function requestReopen(Conversation $conversation): RedirectResponse
    {
        $user = Auth::user();

        if (!$conversation->isParticipant($user) && !$user->isAdmin()) {
            abort(403);
        }

        $conversation->requestReopen($user);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => "{$user->name} requested to re-open the conversation.",
            'message_type' => 'system_notice',
            'is_read' => true,
        ]);

        return back()->with('success', 'Re-open request submitted. Awaiting counterpart confirmation.');
    }

    /**
     * Approve or decline a conversation reopen request.
     */
    public function respondReopen(Conversation $conversation, Request $request): RedirectResponse
    {
        $user = Auth::user();
        $action = $request->input('action', 'approve');

        if (!$conversation->isParticipant($user) && !$user->isAdmin()) {
            abort(403);
        }

        if ($action === 'approve') {
            $conversation->approveReopen();

            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'body' => "{$user->name} approved the re-open request. Thread is now active.",
                'message_type' => 'system_notice',
                'is_read' => true,
            ]);

            return back()->with('success', 'Conversation re-opened successfully.');
        }

        $conversation->declineReopen();

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => "{$user->name} declined the re-open request. Thread remains frozen.",
            'message_type' => 'system_notice',
            'is_read' => true,
        ]);

        return back()->with('info', 'Re-open request declined.');
    }

    /**
     * Propose a custom service or submit an order request from chat.
     */
    public function storeServiceProposal(Conversation $conversation, Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$conversation->isParticipant($user) && !$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:600',
            'duration_days' => 'required|integer|min:1|max:90',
            'service_id' => 'nullable|exists:services,id',
        ]);

        $serviceId = $request->input('service_id');

        // If custom service without existing template, create a service entry for the seller
        if (!$serviceId) {
            $service = Service::create([
                'user_id' => $conversation->seller_id,
                'title' => $request->input('title'),
                'slug' => Str::slug($request->input('title')) . '-' . Str::random(6),
                'description' => $request->input('description'),
                'price' => (float) $request->input('price'),
                'duration_days' => (int) $request->input('duration_days', 3),
                'is_active' => true,
            ]);
            $serviceId = $service->id;
        }

        $totalAmount = (float) $request->input('price');
        $financials = ServiceOrder::calculateFinancials($totalAmount, 15.00);

        // Initial status: if seller sends proposal, buyer can pay immediately;
        // if buyer requests, seller approves first.
        $initialStatus = ($user->id === $conversation->seller_id) ? 'awaiting_payment' : 'pending_approval';

        $order = ServiceOrder::create([
            'order_number' => ServiceOrder::generateOrderNumber(),
            'conversation_id' => $conversation->id,
            'service_id' => $serviceId,
            'buyer_id' => $conversation->buyer_id,
            'seller_id' => $conversation->seller_id,
            'total_amount' => $totalAmount,
            'platform_commission_rate' => 15.00,
            'platform_fee' => $financials['platform_fee'],
            'seller_payout_amount' => $financials['seller_payout_amount'],
            'status' => $initialStatus,
            'deadline_at' => now()->addDays((int) $request->input('duration_days', 3)),
        ]);

        $conversation->update([
            'active_order_id' => $order->id,
            'last_message_at' => now(),
        ]);

        // Post in-chat proposal card message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => "Service Proposal: {$order->service->title} (৳" . number_format($order->total_amount, 2) . ")",
            'message_type' => 'service_proposal',
            'is_read' => false,
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'title' => $order->service->title,
                'description' => $request->input('description'),
                'price' => $order->total_amount,
                'duration_days' => $request->input('duration_days'),
                'status' => $order->status,
            ],
        ]);

        return back()->with('success', 'Custom service proposal submitted successfully.');
    }

    /**
     * Seller approves or declines a service order request.
     */
    public function respondOrderProposal(ServiceOrder $order, Request $request): RedirectResponse
    {
        $user = Auth::user();
        $action = $request->input('action', 'approve');

        if ($order->seller_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the designated creator can respond to this proposal.');
        }

        if ($action === 'approve') {
            $order->update(['status' => 'awaiting_payment']);

            Message::create([
                'conversation_id' => $order->conversation_id,
                'sender_id' => $user->id,
                'body' => "✅ Proposal Accepted: Creator agreed to terms. Buyer may now proceed to secure escrow payment.",
                'message_type' => 'order_status',
                'is_read' => false,
            ]);

            return back()->with('success', 'Proposal accepted! Client can now fund the escrow order.');
        }

        $order->update(['status' => 'declined']);

        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "❌ Proposal Declined: Creator was unable to accept this request.",
            'message_type' => 'order_status',
            'is_read' => false,
        ]);

        return back()->with('info', 'Proposal declined.');
    }

    /**
     * Fund order escrow simulation & activation.
     */
    public function fundOrder(ServiceOrder $order, Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        if ($order->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the client buyer can fund this service order.');
        }

        $buyer = $order->buyer;
        $totalAmount = (float) $order->total_amount;

        // Ensure wallet funds exist, or deposit required amount to simulate instant escrow payment
        if ($buyer->wallet->hasBalance($totalAmount)) {
            $buyer->wallet->deduct($totalAmount, "Escrow funding for service order #{$order->order_number}", 'service_order');
        } else {
            $buyer->wallet->deposit($totalAmount, "Simulated instant deposit for escrow order #{$order->order_number}");
            $buyer->wallet->deduct($totalAmount, "Escrow funding for service order #{$order->order_number}", 'service_order');
        }

        // Calculate delivery deadline based on service duration (default 3 days)
        $durationDays = $order->service->duration_days ?? 3;
        $deadline = now()->addDays($durationDays);

        $order->update([
            'status' => 'funded_in_progress',
            'deadline_at' => $deadline,
        ]);

        // Link active order to conversation
        $order->conversation->update([
            'active_order_id' => $order->id,
            'last_message_at' => now(),
        ]);

        // Post system event bubble in chat
        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $buyer->id,
            'body' => "🔒 Escrow Funded: ৳" . number_format($order->total_amount, 2) . " held safely by Noksha. Delivery timer started. File attachments are now unlocked!",
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'order_id' => $order->id,
                'status' => 'funded_in_progress',
                'amount' => $order->total_amount,
                'deadline_at' => $deadline->toIso8601String(),
            ],
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Escrow funded successfully! Delivery timer has begun.',
                'order' => $order->fresh(),
            ]);
        }

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('success', 'Escrow funded successfully! Delivery timer has begun.');
    }

    /**
     * Seller delivers completed source package (.ZIP only).
     */
    public function deliverWork(ServiceOrder $order, Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($order->seller_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the designated creator can submit work for this order.');
        }

        $request->validate([
            'delivery_file' => 'required|file|mimes:zip|max:204800', // Max 200MB .ZIP
            'delivery_notes' => 'nullable|string|max:2000',
        ]);

        $file = $request->file('delivery_file');
        $disk = config('filesystems.default', 's3');
        $path = $file->store('service_deliveries/' . $order->id, $disk);
        $notes = trim((string) $request->input('delivery_notes', ''));

        $order->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'delivery_archive_path' => $path,
        ]);

        $order->conversation->update(['last_message_at' => now()]);

        // Post delivery card in chat for buyer
        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "📦 Final Work Delivered! Source package (" . $file->getClientOriginalName() . ") uploaded. " . ($notes ? "Notes: {$notes}" : ""),
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'order_id' => $order->id,
                'status' => 'delivered',
                'file_name' => $file->getClientOriginalName(),
                'delivery_notes' => $notes,
                'delivered_at' => now()->toIso8601String(),
            ],
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('success', 'Final work package delivered successfully! Client notified for review.');
    }

    /**
     * Download secure delivery source package.
     */
    public function downloadDelivery(ServiceOrder $order)
    {
        $user = Auth::user();

        if ($order->buyer_id !== $user->id && $order->seller_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized to download this delivery archive.');
        }

        if (empty($order->delivery_archive_path)) {
            abort(404, 'Delivery archive not found for this order.');
        }

        $disk = config('filesystems.default', 's3');
        $filename = 'Noksha-Delivery-' . $order->order_number . '.zip';

        if (Storage::disk($disk)->exists($order->delivery_archive_path)) {
            return Storage::disk($disk)->download($order->delivery_archive_path, $filename);
        }

        return redirect(Storage::disk($disk)->url($order->delivery_archive_path));
    }

    /**
     * Buyer accepts work and releases escrow funds with automatic 15% platform split.
     */
    public function releaseEscrow(ServiceOrder $order, Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($order->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the client buyer can accept work and release escrow.');
        }

        if ($order->status === 'completed') {
            return back()->with('info', 'Escrow funds for this order have already been released.');
        }

        $commissionRate = (float) ($order->platform_commission_rate ?: 15.00);
        $platformFee = round((float) $order->total_amount * ($commissionRate / 100), 2);
        $sellerPayout = round((float) $order->total_amount - $platformFee, 2);

        $order->update([
            'status' => 'completed',
            'platform_fee' => $platformFee,
            'seller_payout_amount' => $sellerPayout,
        ]);

        $order->conversation->update(['last_message_at' => now()]);

        // Credit seller wallet earnings
        $order->seller->wallet->creditEarnings(
            $sellerPayout,
            "Payout for completed order #{$order->order_number} (after {$commissionRate}% Noksha commission)",
            'service_order',
            $order->id
        );

        // Post completion system bubble in chat
        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "🎉 Order Completed! Funds released to seller after {$commissionRate}% Noksha platform commission deduction (৳" . number_format($sellerPayout, 2) . " credited to seller earnings).",
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'order_id' => $order->id,
                'status' => 'completed',
                'seller_payout' => $sellerPayout,
                'platform_fee' => $platformFee,
            ],
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('success', 'Order marked as completed and funds successfully released to the creator.');
    }

    /**
     * Buyer requests revisions on delivered work.
     */
    public function requestRevision(ServiceOrder $order, Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($order->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the client buyer can request revisions.');
        }

        $request->validate([
            'revision_notes' => 'required|string|max:2000',
        ]);

        $notes = $request->input('revision_notes');

        $order->update(['status' => 'funded_in_progress']);
        $order->conversation->update(['last_message_at' => now()]);

        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "🔄 Revision Requested by Client: {$notes}",
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'order_id' => $order->id,
                'status' => 'revision_requested',
                'notes' => $notes,
            ],
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('info', 'Revision request dispatched to creator.');
    }

    /**
     * Cancel order and process refund to buyer wallet.
     */
    public function cancelOrder(ServiceOrder $order, Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$order->conversation->isParticipant($user) && !$user->isAdmin()) {
            abort(403);
        }

        if ($order->isFunded()) {
            $order->buyer->wallet->deposit((float) $order->total_amount, "Refund for cancelled service order #{$order->order_number}");
        }

        $order->update(['status' => 'cancelled']);
        $order->conversation->update(['last_message_at' => now()]);

        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "⚠️ Order Cancelled & Escrow Refunded: ৳" . number_format($order->total_amount, 2) . " returned to client wallet.",
            'message_type' => 'order_status',
            'is_read' => false,
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('info', 'Order cancelled and funds returned to buyer balance.');
    }

    /**
     * Lightweight AJAX polling endpoint for live inbox updates.
     */
    public function pollMessages(Conversation $conversation, Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$conversation->isParticipant($user) && !$user->isAdmin()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        // Auto-complete check: if order delivered for >= 3 days without dispute
        $this->checkAndAutoComplete($conversation->activeOrder);

        $afterId = (int) $request->input('after_id', 0);

        // Fetch new messages
        $messages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $afterId)
            ->with(['sender', 'attachments'])
            ->oldest()
            ->get();

        // Mark incoming messages as read
        if ($messages->isNotEmpty()) {
            Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        $conversation->refresh();
        $activeOrder = $conversation->activeOrder;

        return response()->json([
            'status' => 'success',
            'latest_id' => $messages->last()?->id ?? $afterId,
            'messages' => $messages->map(function ($msg) use ($user) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'is_me' => (int) $msg->sender_id === (int) $user->id,
                    'sender_name' => $msg->sender->name ?? 'User',
                    'body' => $msg->body,
                    'message_type' => $msg->message_type,
                    'metadata' => $msg->metadata,
                    'created_at_human' => $msg->created_at ? $msg->created_at->format('H:i') : '',
                    'attachments' => $msg->attachments->map(fn($a) => [
                        'file_name' => $a->file_name,
                        'url' => $a->url,
                        'size' => $a->formatted_size,
                    ]),
                ];
            }),
            'active_order' => $activeOrder ? [
                'id' => $activeOrder->id,
                'order_number' => $activeOrder->order_number,
                'status' => $activeOrder->status,
                'deadline_at' => $activeOrder->deadline_at?->toIso8601String(),
                'delivered_at' => $activeOrder->delivered_at?->toIso8601String(),
            ] : null,
            'is_frozen' => (bool) $conversation->is_frozen,
        ]);
    }

    /**
     * Auto-complete delivered order after 3 days without dispute.
     */
    protected function checkAndAutoComplete(?ServiceOrder $order): void
    {
        if (!$order || $order->status !== 'delivered' || !$order->delivered_at) {
            return;
        }

        // Check if 3 days have passed since delivery
        if ($order->delivered_at->addDays(3)->isPast()) {
            $commissionRate = (float) ($order->platform_commission_rate ?: 15.00);
            $platformFee = round((float) $order->total_amount * ($commissionRate / 100), 2);
            $sellerPayout = round((float) $order->total_amount - $platformFee, 2);

            $order->update([
                'status' => 'completed',
                'platform_fee' => $platformFee,
                'seller_payout_amount' => $sellerPayout,
            ]);

            $order->seller->wallet->creditEarnings(
                $sellerPayout,
                "Auto-completed payout for order #{$order->order_number} (after 3 days delivery)",
                'service_order',
                $order->id
            );

            Message::create([
                'conversation_id' => $order->conversation_id,
                'sender_id' => $order->buyer_id,
                'body' => "🎉 Auto-Completion Triggered: Order successfully completed after 3 days without dispute. Escrow released to creator.",
                'message_type' => 'order_status',
                'is_read' => false,
            ]);
        }
    }

    /**
     * Helper to retrieve all active conversations for a user.
     */
    protected function getUserConversations(User $user, ?string $search = null)
    {
        $query = Conversation::where(function ($q) use ($user) {
            $q->where('buyer_id', $user->id)
              ->orWhere('seller_id', $user->id);
        })
        ->with(['buyer', 'seller', 'latestMessage', 'activeOrder'])
        ->withCount([
            'messages as unread_count' => function ($q) use ($user) {
                $q->where('sender_id', '!=', $user->id)->where('is_read', false);
            }
        ]);

        if (!empty($search)) {
            $term = '%' . trim($search) . '%';
            $query->where(function ($q) use ($term) {
                $q->whereHas('buyer', function ($bq) use ($term) {
                    $bq->where('name', 'like', $term)->orWhere('username', 'like', $term);
                })
                ->orWhereHas('seller', function ($sq) use ($term) {
                    $sq->where('name', 'like', $term)->orWhere('username', 'like', $term);
                })
                ->orWhereHas('messages', function ($mq) use ($term) {
                    $mq->where('body', 'like', $term);
                });
            });
        }

        return $query->orderByDesc('last_message_at')->get();
    }
}
