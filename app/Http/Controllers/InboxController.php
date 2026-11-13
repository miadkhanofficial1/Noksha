<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\PlatformReview;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Policies\ConversationPolicy;
use App\Services\NokshaFilePackagerService;
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

        // 1. Auto-Create or Open Conversation When ?recipient={id} is Passed
        if ($request->filled('recipient')) {
            $recipientId = (int) $request->query('recipient');
            $authId = (int) $user->id;

            if ($recipientId && $recipientId !== $authId) {
                $conversation = Conversation::where(function ($q) use ($authId, $recipientId) {
                    $q->where('buyer_id', $authId)->where('seller_id', $recipientId);
                })->orWhere(function ($q) use ($authId, $recipientId) {
                    $q->where('buyer_id', $recipientId)->where('seller_id', $authId);
                })->first();

                if (!$conversation) {
                    $conversation = Conversation::create([
                        'buyer_id' => $authId,
                        'seller_id' => $recipientId,
                        'conversation_type' => 'direct',
                        'is_frozen' => false,
                        'last_message_at' => now(),
                    ]);
                }

                return redirect()->route('inbox.show', $conversation->id);
            }
        }

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
            'sellerServices' => collect(),
            'workHistoryOrders' => collect(),
            'workHistoryCount' => 0,
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

        // Check deadlines and apply overdue alerts or demerits
        $this->checkDeadlinesAndDemerits($conversation->activeOrder);

        $counterpart = $conversation->getOtherParticipant($user);

        // 6. Fetch all conversations for sidebar
        $conversations = $this->getUserConversations($user, $request->input('search'));

        // 7. Load available services offered by the seller for the top strip & proposal builder (max 5)
        $services = Service::where('user_id', $conversation->seller_id)
            ->where(function ($q) {
                $q->where('status', 'active')->orWhere('is_active', true);
            })
            ->take(5)
            ->get();

        // 8. Work History between this buyer and seller
        $workHistoryOrders = ServiceOrder::where(function ($q) use ($conversation) {
            $q->where('buyer_id', $conversation->buyer_id)->where('seller_id', $conversation->seller_id);
        })->orWhere(function ($q) use ($conversation) {
            $q->where('buyer_id', $conversation->seller_id)->where('seller_id', $conversation->buyer_id);
        })
        ->with(['service', 'reviews'])
        ->latest()
        ->get();
        $workHistoryCount = $workHistoryOrders->count();

        return view('inbox.index', [
            'conversations' => $conversations,
            'activeConversation' => $conversation,
            'counterpart' => $counterpart,
            'services' => $services,
            'sellerServices' => $services,
            'workHistoryOrders' => $workHistoryOrders,
            'workHistoryCount' => $workHistoryCount,
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
     * Propose a custom service or submit a creator recommendation offer from chat.
     */
    public function storeServiceProposal(Conversation $conversation, Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        if (!$conversation->isParticipant($user) && !$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:3000',
            'requirements_brief' => 'nullable|string|max:3000',
            'price' => 'nullable|numeric|min:600',
            'amount' => 'nullable|numeric|min:600',
            'duration_days' => 'nullable|integer|min:1|max:90',
            'delivery_days' => 'nullable|integer|min:1|max:90',
            'revisions_allowed' => 'nullable|integer|min:0|max:10',
            'service_id' => 'nullable',
        ]);

        $serviceId = $request->filled('service_id') ? $request->input('service_id') : null;
        $title = trim($request->input('title'));
        $description = trim((string) ($request->input('description') ?? $request->input('requirements_brief') ?? ''));
        $totalAmount = (float) ($request->input('price') ?? $request->input('amount') ?? 600.00);
        $deliveryDays = (int) ($request->input('delivery_days') ?? $request->input('duration_days') ?? 3);
        $revisionsAllowed = (int) ($request->input('revisions_allowed') ?? 2);

        if (!$serviceId) {
            $service = Service::create([
                'user_id' => $conversation->seller_id,
                'title' => $title,
                'slug' => Str::slug($title) . '-' . Str::random(6),
                'description' => !empty($description) ? $description : 'Custom creative service package negotiated via Noksha escrow.',
                'category' => 'Creative',
                'price' => $totalAmount,
                'starting_price' => $totalAmount,
                'duration_days' => $deliveryDays,
                'delivery_days' => $deliveryDays,
                'revisions_count' => $revisionsAllowed,
                'is_active' => true,
                'status' => 'active',
            ]);
            $serviceId = $service->id;
        } else {
            $service = Service::find($serviceId);
        }

        $serviceTitle = $service?->title ?? $title;
        $financials = ServiceOrder::calculateFinancials($totalAmount, 15.00);
        $orderCode = ServiceOrder::generateOrderCode();

        // If creator makes the offer directly, it is awaiting payment from the buyer
        $initialStatus = ($user->id === $conversation->seller_id) ? 'awaiting_payment' : 'pending_approval';

        $order = ServiceOrder::create([
            'order_code' => $orderCode,
            'order_number' => $orderCode,
            'conversation_id' => $conversation->id,
            'service_id' => $serviceId,
            'buyer_id' => $conversation->buyer_id,
            'seller_id' => $conversation->seller_id,
            'custom_title' => $serviceTitle,
            'requirements_brief' => $description,
            'amount' => $totalAmount,
            'total_amount' => $totalAmount,
            'platform_commission_rate' => 15.00,
            'platform_fee' => $financials['platform_fee'],
            'seller_payout' => $financials['seller_payout'],
            'seller_payout_amount' => $financials['seller_payout_amount'],
            'delivery_days' => $deliveryDays,
            'revisions_allowed' => $revisionsAllowed,
            'revisions_used' => 0,
            'status' => $initialStatus,
            'deadline_at' => now()->addDays($deliveryDays),
        ]);

        $conversation->update([
            'active_order_id' => $order->id,
            'last_message_at' => now(),
        ]);

        // Post in-chat proposal card message with order_code and metadata
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => "Service Offer: {$serviceTitle} (#{$orderCode}) — ৳" . number_format($order->total_amount, 2),
            'message_type' => 'service_proposal',
            'is_read' => false,
            'metadata' => [
                'type' => 'custom_offer',
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'order_number' => $order->order_number,
                'title' => $serviceTitle,
                'description' => $description,
                'price' => $order->total_amount,
                'amount' => $order->total_amount,
                'delivery_days' => $order->delivery_days,
                'revisions_allowed' => $order->revisions_allowed,
                'status' => $order->status,
            ],
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Custom service offer dispatched successfully.',
                'order' => $order,
                'redirect_url' => route('inbox.show', $conversation->id),
            ]);
        }

        return redirect()->route('inbox.show', $conversation->id)
            ->with('success', 'Custom service offer created successfully.');
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

        if ($action === 'approve' || $action === 'accept') {
            $order->update(['status' => 'awaiting_payment']);
            $order->conversation->update([
                'active_order_id' => $order->id,
                'last_message_at' => now(),
            ]);

            Message::create([
                'conversation_id' => $order->conversation_id,
                'sender_id' => $user->id,
                'body' => "✅ Proposal Accepted: Creator agreed to terms. Buyer may now proceed to secure escrow payment.",
                'message_type' => 'order_status',
                'is_read' => false,
            ]);

            return redirect()->route('inbox.show', $order->conversation_id)
                ->with('success', 'Proposal accepted! Client can now fund the escrow order.');
        }

        $order->update(['status' => 'declined']);
        $order->conversation->update(['last_message_at' => now()]);

        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "❌ Proposal Declined: Creator was unable to accept this request.",
            'message_type' => 'order_status',
            'is_read' => false,
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('info', 'Proposal declined.');
    }

    /**
     * Fund order escrow simulation & activation with Wallet balance check.
     */
    public function fundOrder(ServiceOrder $order, Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        if ($order->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the client buyer can fund this service order.');
        }

        $buyer = $order->buyer;
        $totalAmount = (float) ($order->amount ?? $order->total_amount);

        // Check wallet balance
        if (!$buyer->wallet->hasBalance($totalAmount)) {
            $shortfall = round($totalAmount - (float) $buyer->wallet->balance, 2);
            $depositUrl = route('wallet.index', ['tab' => 'deposit']);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'insufficient_funds',
                    'message' => "Insufficient wallet balance (৳" . number_format($buyer->wallet->balance, 2) . "). You need ৳" . number_format($shortfall, 2) . " more.",
                    'shortfall' => $shortfall,
                    'balance' => (float) $buyer->wallet->balance,
                    'required' => $totalAmount,
                    'deposit_url' => $depositUrl,
                ], 422);
            }

            return redirect()->route('inbox.show', $order->conversation_id)
                ->with('error', "Insufficient wallet balance (৳" . number_format($buyer->wallet->balance, 2) . "). You need ৳" . number_format($shortfall, 2) . " more. Please deposit funds.")
                ->with('insufficient_funds', true)
                ->with('shortfall', $shortfall);
        }

        // Deduct from buyer wallet balance
        $buyer->wallet->deduct($totalAmount, "Escrow funding for service order #{$order->order_code}", 'service_order', $order->id);

        // Calculate delivery deadline based on delivery days
        $durationDays = $order->delivery_days ?? ($order->service->duration_days ?? 3);
        $deadline = now()->addDays($durationDays);

        $order->update([
            'status' => 'in_progress',
            'deadline_at' => $deadline,
        ]);

        // Increment creator's active_orders_count (which reduces their available slots)
        if ($order->service) {
            $order->service->increment('active_orders_count');
        }

        // Link active order to conversation
        $order->conversation->update([
            'active_order_id' => $order->id,
            'last_message_at' => now(),
        ]);

        // Send Bell Notification to creator
        Notification::create([
            'user_id' => $order->seller_id,
            'title' => 'New Order Funded!',
            'message' => "Order #{$order->order_code} has been funded (৳" . number_format($totalAmount, 2) . "). Delivery timer started ({$durationDays} days).",
            'type' => 'wallet',
            'action_url' => route('inbox.show', $order->conversation_id),
        ]);

        // Post system event bubble in chat
        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $buyer->id,
            'body' => "🔒 Escrow Funded: ৳" . number_format($totalAmount, 2) . " held safely in Noksha Escrow. Delivery timer started. File attachments are now unlocked!",
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'status' => 'in_progress',
                'amount' => $totalAmount,
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
     * Seller delivers work via structured multi-file packager.
     */
    public function deliverWork(ServiceOrder $order, Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($order->seller_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the designated creator can submit work for this order.');
        }

        $request->validate([
            'preview_images' => 'required|array|min:1|max:5',
            'preview_images.*' => 'file|mimes:jpeg,jpg,png,webp|max:15360', // Max 15MB each
            'source_file' => 'required|file|max:204800', // Max 200MB source file / ZIP
            'instruction_pdf' => 'nullable|file|mimes:pdf|max:20480', // Max 20MB
            'delivery_notes' => 'nullable|string|max:2000',
        ]);

        $baseTitle = $order->custom_title ?? $order->service?->title ?? 'Escrow Delivery';

        $packaged = NokshaFilePackagerService::storeStructuredFiles(
            $baseTitle,
            $request->file('preview_images', []),
            $request->file('source_file'),
            $request->file('instruction_pdf'),
            'service_deliveries/' . $order->id
        );

        $notes = trim((string) $request->input('delivery_notes', ''));
        $sourcePath = $packaged['source_file_path'] ?? ($packaged['source_file']['path'] ?? null);
        $instructionPath = $packaged['instruction_pdf_path'] ?? ($packaged['instruction_pdf']['path'] ?? null);
        $previewPaths = $packaged['preview_image_paths'] ?? array_map(fn($p) => is_array($p) ? $p['path'] : $p, $packaged['preview_images']);

        $order->update([
            'status' => 'delivered_preview',
            'delivered_at' => now(),
            'delivery_preview_images' => $previewPaths,
            'delivery_source_file' => $sourcePath,
            'delivery_instruction_pdf' => $instructionPath,
            'delivery_manifest' => $packaged['manifest'],
            'delivery_archive_path' => $sourcePath,
        ]);

        $order->conversation->update(['last_message_at' => now()]);

        // Send Bell Notification to buyer
        Notification::create([
            'user_id' => $order->buyer_id,
            'title' => 'Work Delivered for Review',
            'message' => "Creator submitted delivery for order #{$order->order_code}. Please review preview images.",
            'type' => 'upload',
            'action_url' => route('inbox.show', $order->conversation_id),
        ]);

        // Post delivery preview message in chat
        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "📦 Delivery Preview Submitted! Previews and file manifest are now available for client review. " . ($notes ? "Notes: {$notes}" : ""),
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'order_id' => $order->id,
                'status' => 'delivered_preview',
                'delivery_notes' => $notes,
                'delivered_at' => now()->toIso8601String(),
                'manifest_count' => count($packaged['manifest']),
            ],
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('success', 'Work delivered successfully for client review! Source files remain locked until approval.');
    }

    /**
     * Download secure delivery source package.
     * Enforces strict file lock during preview. Unlocks full ZIP upon completion.
     */
    public function downloadDelivery(ServiceOrder $order)
    {
        $user = Auth::user();

        if ($order->buyer_id !== $user->id && $order->seller_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized to download this delivery archive.');
        }

        // Strict Anti-Theft Lock: Buyer cannot download source during delivered_preview
        if ($order->status === 'delivered_preview' && $order->buyer_id === $user->id && !$user->isAdmin()) {
            abort(403, '🔒 Source files remain strictly locked until delivery is approved and escrow released.');
        }

        // Build list of all available component files
        $filesToBundle = [];
        if (is_array($order->delivery_preview_images)) {
            foreach ($order->delivery_preview_images as $prev) {
                $filesToBundle[] = $prev;
            }
        }
        if ($order->delivery_source_file) {
            $filesToBundle[] = $order->delivery_source_file;
        }
        if ($order->delivery_instruction_pdf) {
            $filesToBundle[] = $order->delivery_instruction_pdf;
        }
        if ($order->delivery_archive_path && !in_array($order->delivery_archive_path, $filesToBundle)) {
            $filesToBundle[] = $order->delivery_archive_path;
        }

        $baseTitle = $order->custom_title ?? $order->service?->title ?? 'Escrow Delivery';
        $sellerUsername = $order->seller?->username ?? 'creator';

        if (!empty($filesToBundle)) {
            try {
                return NokshaFilePackagerService::streamZipDownload(
                    $baseTitle,
                    $sellerUsername,
                    $filesToBundle
                );
            } catch (\Throwable $e) {
                // Fallback below
            }
        }

        if (empty($order->delivery_archive_path)) {
            abort(404, 'Delivery archive not found for this order.');
        }

        $disk = config('filesystems.default', 'local');
        $filename = 'Noksha-' . NokshaFilePackagerService::sanitizeTitle($baseTitle) . '-' . ($order->order_code ?? $order->order_number) . '.zip';

        if (Storage::disk('public')->exists($order->delivery_archive_path)) {
            return Storage::disk('public')->download($order->delivery_archive_path, $filename);
        }

        if (Storage::disk($disk)->exists($order->delivery_archive_path)) {
            return Storage::disk($disk)->download($order->delivery_archive_path, $filename);
        }

        return redirect(Storage::disk('public')->url($order->delivery_archive_path));
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

        $totalAmount = (float) ($order->amount ?? $order->total_amount);
        $commissionRate = (float) ($order->platform_commission_rate ?: 15.00);
        $platformFee = round($totalAmount * ($commissionRate / 100), 2);
        $sellerPayout = round($totalAmount - $platformFee, 2);

        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
            'platform_fee' => $platformFee,
            'seller_payout' => $sellerPayout,
            'seller_payout_amount' => $sellerPayout,
        ]);

        $order->conversation->update(['last_message_at' => now()]);

        // Decrement creator's active_orders_count (re-opening their available slot)
        if ($order->service && $order->service->active_orders_count > 0) {
            $order->service->decrement('active_orders_count');
        }

        // Credit seller wallet earnings
        $order->seller->wallet->creditEarnings(
            $sellerPayout,
            "Payout for completed order #{$order->order_code} (after {$commissionRate}% Noksha platform commission)",
            'service_order',
            $order->id
        );

        // Send Bell Notification to creator
        Notification::create([
            'user_id' => $order->seller_id,
            'title' => '🎉 Order Completed & Escrow Released!',
            'message' => "৳" . number_format($sellerPayout, 2) . " credited to your earnings for order #{$order->order_code}.",
            'type' => 'wallet',
            'action_url' => route('inbox.show', $order->conversation_id),
        ]);

        $serviceTitle = $order->custom_title ?? $order->service?->title ?? 'Creative Service';

        // Post completion divider banner in chat with "Order Again" button
        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "✅ {$serviceTitle} (#{$order->order_code}) — Completed",
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'type' => 'order_completed_banner',
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'service_title' => $serviceTitle,
                'service_id' => $order->service_id,
                'seller_payout' => $sellerPayout,
                'platform_fee' => $platformFee,
                'completed_at' => now()->toIso8601String(),
            ],
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('success', 'Order completed and escrow released! Full source files are now unlocked.')
            ->with('prompt_review_order_id', $order->id);
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

        if ($order->revisions_allowed > 0 && $order->revisions_used >= $order->revisions_allowed) {
            return back()->with('error', "All {$order->revisions_allowed} revision requests for this order have been used.");
        }

        $request->validate([
            'revision_notes' => 'required|string|max:2000',
        ]);

        $notes = trim($request->input('revision_notes'));

        $order->increment('revisions_used');
        $order->update(['status' => 'in_progress']);
        $order->conversation->update(['last_message_at' => now()]);

        // Send Bell Notification to creator
        Notification::create([
            'user_id' => $order->seller_id,
            'title' => 'Revision Requested',
            'message' => "Client requested revision on order #{$order->order_code} (Revision {$order->revisions_used}/{$order->revisions_allowed}).",
            'type' => 'upload',
            'action_url' => route('inbox.show', $order->conversation_id),
        ]);

        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "🔄 Revision Requested ({$order->revisions_used}/{$order->revisions_allowed}): {$notes}",
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'order_id' => $order->id,
                'status' => 'revision_requested',
                'revisions_used' => $order->revisions_used,
                'revisions_allowed' => $order->revisions_allowed,
                'notes' => $notes,
            ],
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('info', "Revision request ({$order->revisions_used}/{$order->revisions_allowed}) dispatched to creator.");
    }

    /**
     * Buyer requests 1 minor adjustment within 48h of order completion.
     */
    public function requestMinorFix(ServiceOrder $order, Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($order->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the client buyer can request a minor fix.');
        }

        if ($order->status !== 'completed' || !$order->completed_at || $order->completed_at->addHours(48)->isPast()) {
            return back()->with('error', 'The 48-hour minor fix window has expired.');
        }

        if ($order->minor_fix_used) {
            return back()->with('error', 'The 1 included minor fix has already been used for this order.');
        }

        $request->validate([
            'minor_fix_notes' => 'required|string|max:1000',
        ]);

        $notes = trim($request->input('minor_fix_notes'));

        $order->update(['minor_fix_used' => true]);
        $order->conversation->update(['last_message_at' => now()]);

        // Send Bell Notification to creator
        Notification::create([
            'user_id' => $order->seller_id,
            'title' => '🔧 Minor Fix Request (48h Window)',
            'message' => "Client requested a minor adjustment on completed order #{$order->order_code}.",
            'type' => 'upload',
            'action_url' => route('inbox.show', $order->conversation_id),
        ]);

        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "🔧 48-Hour Minor Fix Requested: {$notes}",
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'type' => 'minor_fix',
                'order_id' => $order->id,
                'notes' => $notes,
            ],
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('success', 'Minor fix request dispatched to creator.');
    }

    /**
     * Creator requests deadline extension (Strict Max 1 per order, +1 to +3 days).
     */
    public function requestExtension(ServiceOrder $order, Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($order->seller_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the designated creator can request a deadline extension.');
        }

        if ($order->extension_used) {
            return back()->with('error', 'A deadline extension has already been requested for this order (Maximum 1 allowed).');
        }

        if (!$order->isInProgress()) {
            return back()->with('error', 'Extensions can only be requested while the order is in progress.');
        }

        $request->validate([
            'extension_days' => 'required|integer|in:1,2,3',
            'extension_reason' => 'required|string|max:500',
        ]);

        $days = (int) $request->input('extension_days');
        $reason = trim($request->input('extension_reason'));

        // Send Bell Notification to buyer
        Notification::create([
            'user_id' => $order->buyer_id,
            'title' => 'Deadline Extension Requested',
            'message' => "Creator requested +{$days} days extension on order #{$order->order_code}. Please review in chat.",
            'type' => 'upload',
            'action_url' => route('inbox.show', $order->conversation_id),
        ]);

        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "⏳ Creator Requested +{$days} Days Deadline Extension. Reason: {$reason}",
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'type' => 'extension_request',
                'order_id' => $order->id,
                'extension_days' => $days,
                'extension_reason' => $reason,
                'status' => 'pending',
            ],
        ]);

        $order->conversation->update(['last_message_at' => now()]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('success', "Extension request (+{$days} days) dispatched to client for confirmation.");
    }

    /**
     * Buyer approves or declines deadline extension.
     */
    public function respondExtension(ServiceOrder $order, Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($order->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the client buyer can respond to extension requests.');
        }

        $action = $request->input('action', 'accept');
        $days = (int) $request->input('days', 1);

        if ($action === 'accept' || $action === 'approve') {
            $newDeadline = $order->deadline_at ? $order->deadline_at->addDays($days) : now()->addDays($days);

            $order->update([
                'extension_used' => true,
                'deadline_at' => $newDeadline,
                'deadline_notified_12h' => false,
                'deadline_notified_overdue' => false,
            ]);

            Notification::create([
                'user_id' => $order->seller_id,
                'title' => '✅ Extension Approved',
                'message' => "Client approved your +{$days} days extension for order #{$order->order_code}.",
                'type' => 'upload',
                'action_url' => route('inbox.show', $order->conversation_id),
            ]);

            Message::create([
                'conversation_id' => $order->conversation_id,
                'sender_id' => $user->id,
                'body' => "✅ Extension Approved: Deadline extended by {$days} days. New deadline: " . $newDeadline->format('M d, Y H:i'),
                'message_type' => 'order_status',
                'is_read' => false,
            ]);

            return redirect()->route('inbox.show', $order->conversation_id)
                ->with('success', "Extension approved. Deadline extended by {$days} days.");
        }

        $order->update(['extension_used' => true]);

        Notification::create([
            'user_id' => $order->seller_id,
            'title' => '❌ Extension Declined',
            'message' => "Client declined your extension request for order #{$order->order_code}.",
            'type' => 'upload',
            'action_url' => route('inbox.show', $order->conversation_id),
        ]);

        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "❌ Extension Declined: Deadline remains unchanged.",
            'message_type' => 'order_status',
            'is_read' => false,
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('info', 'Extension request declined.');
    }

    /**
     * Buyer reports empty or fake file during preview delivery (halts auto-release, applies 25 demerits).
     */
    public function reportFakeDelivery(ServiceOrder $order, Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($order->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the client buyer can report delivery issues.');
        }

        $request->validate([
            'report_reason' => 'required|string|max:1000',
        ]);

        $reason = trim($request->input('report_reason'));

        $order->update(['status' => 'disputed']);
        $order->conversation->update(['last_message_at' => now()]);

        // Penalize creator with 25 demerits
        $order->seller?->recordDemerit(25, false);

        // Notify creator
        Notification::create([
            'user_id' => $order->seller_id,
            'title' => '🚨 Order Disputed: Fake/Empty File Reported',
            'message' => "Client reported empty/fake delivery on order #{$order->order_code}. +25 demerit points recorded.",
            'type' => 'system',
            'action_url' => route('inbox.show', $order->conversation_id),
        ]);

        // Post dispute bubble in chat
        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "🚨 Empty / Fake Delivery Reported: Order placed in arbitration dispute. Auto-release halted and 25 demerit penalty applied. Reason: {$reason}",
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'type' => 'dispute_reported',
                'order_id' => $order->id,
                'reason' => $reason,
            ],
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('error', 'Dispute filed. Auto-release halted and administrative review initiated.');
    }

    /**
     * Submit verified order review (1 to 5 stars + feedback + optional evidence images + complaint flag).
     */
    public function submitOrderReview(ServiceOrder $order, Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        if ($order->buyer_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the client buyer can review this order.');
        }

        if (!$order->isCompleted()) {
            return back()->with('error', 'Reviews can only be submitted after order completion.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
            'is_complaint' => 'nullable|boolean',
            'evidence_images' => 'nullable|array|max:3',
            'evidence_images.*' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $existing = PlatformReview::where('service_order_id', $order->id)
            ->where('reviewer_id', $user->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'You have already submitted a review for this order.');
        }

        $evidencePaths = [];
        if ($request->hasFile('evidence_images')) {
            $files = $request->file('evidence_images');
            if (!is_array($files)) {
                $files = [$files];
            }
            foreach (array_slice($files, 0, 3) as $file) {
                if ($file && $file->isValid()) {
                    $evidencePaths[] = $file->store('review_evidence', 'public');
                }
            }
        }

        $isComplaint = $request->boolean('is_complaint');

        $review = PlatformReview::create([
            'reviewer_id' => $user->id,
            'creator_id' => $order->seller_id,
            'service_id' => $order->service_id,
            'service_order_id' => $order->id,
            'rating' => (int) $request->input('rating'),
            'comment' => trim($request->input('comment')),
            'is_complaint' => $isComplaint,
            'evidence_images' => $evidencePaths,
            'admin_resolved' => false,
        ]);

        // Post confirmation notice in chat
        $icon = $isComplaint ? '⚠️' : '⭐';
        $label = $isComplaint ? 'Client filed feedback issue / complaint' : 'Client left a verified review';
        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => "{$icon} {$label} ({$review->rating}/5 stars): \"{$review->comment}\"",
            'message_type' => 'order_status',
            'is_read' => false,
            'metadata' => [
                'type' => 'order_review',
                'order_id' => $order->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'is_complaint' => $isComplaint,
                'evidence_count' => count($evidencePaths),
            ],
        ]);

        if ($isComplaint) {
            Notification::create([
                'user_id' => $order->seller_id,
                'title' => '⚠️ Review Complaint Logged',
                'message' => "Client flagged an issue with order #{$order->order_code}. Admin arbitration hub notified.",
                'type' => 'system',
                'action_url' => route('inbox.show', $order->conversation_id),
            ]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Thank you! Your verified review has been published.',
                'review' => $review,
            ]);
        }

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('success', 'Thank you! Your verified review has been published.');
    }

    /**
     * Check deadline alerts and record demerits for overdue orders.
     */
    protected function checkDeadlinesAndDemerits(?ServiceOrder $order): void
    {
        if (!$order || !$order->isInProgress() || !$order->deadline_at) {
            return;
        }

        // Overdue check
        if ($order->deadline_at->isPast() && !$order->deadline_notified_overdue) {
            $order->update(['deadline_notified_overdue' => true]);
            $order->seller?->recordDemerit(10, true);

            Notification::create([
                'user_id' => $order->seller_id,
                'title' => '⚠️ Order Overdue: Delivery Past Deadline',
                'message' => "Order #{$order->order_code} is past its delivery deadline. Late delivery penalty (+10 demerits) recorded.",
                'type' => 'system',
                'action_url' => route('inbox.show', $order->conversation_id),
            ]);
        }
        // 12 hours remaining notice
        elseif (!$order->deadline_notified_12h && $order->deadline_at->isFuture() && $order->deadline_at->diffInHours(now(), false) <= 12) {
            $order->update(['deadline_notified_12h' => true]);

            Notification::create([
                'user_id' => $order->seller_id,
                'title' => '⏳ 12 Hours Remaining for Delivery',
                'message' => "Order #{$order->order_code} has less than 12 hours remaining before its deadline. Please prepare final delivery.",
                'type' => 'upload',
                'action_url' => route('inbox.show', $order->conversation_id),
            ]);
        }
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

        $isFunded = $order->isFunded();
        $totalAmount = (float) ($order->amount ?? $order->total_amount);

        if ($isFunded && in_array($order->status, ['in_progress', 'funded_in_progress', 'awaiting_payment'])) {
            $order->buyer->wallet->deposit($totalAmount, "Refund for cancelled service order #{$order->order_code}");
            if ($order->service && $order->service->active_orders_count > 0) {
                $order->service->decrement('active_orders_count');
            }
        }

        $order->update(['status' => 'cancelled']);
        if ($order->conversation->active_order_id === $order->id) {
            $order->conversation->update(['active_order_id' => null, 'last_message_at' => now()]);
        } else {
            $order->conversation->update(['last_message_at' => now()]);
        }

        $noticeBody = $isFunded
            ? "⚠️ Order Cancelled & Escrow Refunded: ৳" . number_format($totalAmount, 2) . " returned to client wallet."
            : "❌ Proposal Withdrawn / Cancelled.";

        Message::create([
            'conversation_id' => $order->conversation_id,
            'sender_id' => $user->id,
            'body' => $noticeBody,
            'message_type' => 'order_status',
            'is_read' => false,
        ]);

        return redirect()->route('inbox.show', $order->conversation_id)
            ->with('info', $isFunded ? 'Order cancelled and funds returned to buyer balance.' : 'Proposal withdrawn.');
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

        // Check deadlines & demerits
        $this->checkDeadlinesAndDemerits($conversation->activeOrder);

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
                'order_code' => $activeOrder->order_code ?? $activeOrder->order_number,
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
            $totalAmount = (float) ($order->amount ?? $order->total_amount);
            $commissionRate = (float) ($order->platform_commission_rate ?: 15.00);
            $platformFee = round($totalAmount * ($commissionRate / 100), 2);
            $sellerPayout = round($totalAmount - $platformFee, 2);

            $order->update([
                'status' => 'completed',
                'completed_at' => now(),
                'platform_fee' => $platformFee,
                'seller_payout' => $sellerPayout,
                'seller_payout_amount' => $sellerPayout,
            ]);

            // Decrement active orders count on service
            if ($order->service && $order->service->active_orders_count > 0) {
                $order->service->decrement('active_orders_count');
            }

            $order->seller->wallet->creditEarnings(
                $sellerPayout,
                "Auto-completed payout for order #{$order->order_code} (after 3 days delivery)",
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

    /**
     * Delete a conversation thread (delegates to MessageController@destroy).
     */
    public function destroy(Request $request, Conversation|string|int $conversation): RedirectResponse|JsonResponse
    {
        return app(MessageController::class)->destroy($request, $conversation);
    }
}
