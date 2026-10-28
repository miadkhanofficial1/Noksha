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
